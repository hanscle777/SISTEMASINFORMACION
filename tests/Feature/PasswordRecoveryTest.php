<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class PasswordRecoveryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create standard role
        $role = Role::create([
            'name' => 'Administrador',
            'slug' => 'administrador',
        ]);

        // Create user
        $this->user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('CorrectPassword123'),
            'role_id' => $role->id,
        ]);
    }

    public function test_failed_login_attempts_are_tracked_in_cache_with_limit_of_three()
    {
        $cacheKey = 'login_failed_john@example.com';
        $this->assertFalse(Cache::has($cacheKey));

        // Attempt 1: Failed
        $response = $this->post(route('login.post'), [
            'email' => 'john@example.com',
            'password' => 'WrongPassword',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertEquals(1, Cache::get($cacheKey));
        $response->assertSessionMissing('show_forgot_password');

        // Attempt 2: Failed
        $response = $this->post(route('login.post'), [
            'email' => 'john@example.com',
            'password' => 'WrongPassword2',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertEquals(2, Cache::get($cacheKey));
        $response->assertSessionMissing('show_forgot_password');

        // Attempt 3: Failed (Exceeds limit)
        $response = $this->post(route('login.post'), [
            'email' => 'john@example.com',
            'password' => 'WrongPassword3',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertEquals(3, Cache::get($cacheKey));
        $response->assertSessionHas('show_forgot_password', true);
        $response->assertSessionHas('failed_email', 'john@example.com');
    }

    public function test_successful_login_clears_failed_attempts()
    {
        $cacheKey = 'login_failed_john@example.com';

        // Increment attempts to 2
        Cache::put($cacheKey, 2, 60);

        // Perform a successful login
        $response = $this->post(route('login.post'), [
            'email' => 'john@example.com',
            'password' => 'CorrectPassword123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertFalse(Cache::has($cacheKey));
    }

    public function test_can_request_reset_link_and_reset_password_with_token()
    {
        // 1. Send password reset link request
        $response = $this->post(route('password.email'), [
            'email' => 'john@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        // 2. Generate a valid token for testing reset (since DB stores a hash of the token)
        $token = Password::broker()->createToken($this->user);

        // 3. Perform the reset using the token
        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'john@example.com',
            'password' => 'NewSecurePassword123',
            'password_confirmation' => 'NewSecurePassword123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status');

        // 4. Verify user can now log in with the new password
        $response = $this->post(route('login.post'), [
            'email' => 'john@example.com',
            'password' => 'NewSecurePassword123',
        ]);
        $response->assertRedirect(route('dashboard'));
    }
}
