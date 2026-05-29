<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;
use App\Traits\LogsActivity;

class AuthController extends Controller
{
    use LogsActivity;

    public function showLogin()
    {
        // Asegurar que existan roles y el administrador (Lazy Init Robusto)
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('roles') || \App\Models\Role::count() === 0) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                $seeder = new \Database\Seeders\RolePermissionSeeder();
                $seeder->run();
            }
        } catch (\Exception $e) {
            // Si algo falla, dejamos que el usuario intente loguearse o vea el error luego
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Reset failed attempts counter on successful login
            Cache::forget('login_failed_'.$request->email);

            // Prueba directa sin trait
            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'LOGIN_DIRECT',
                'description' => 'Inicio de sesión (prueba directa)',
                'ip_address' => request()->ip()
            ]);

            $this->logActivity('LOGIN', 'Inicio de sesión exitoso');
            if (Auth::user()->hasRole('cliente')) {
                return redirect()->route('landing');
            }
            return redirect()->route('dashboard');
        }

        // Log failed attempt (Auth::id() will be null here)
        $this->logActivity('LOGIN_FAILED', "Intento de inicio de sesión fallido para: {$request->email}");

        // Increment failed attempts counter with a 15-minute TTL
        $key = 'login_failed_'.$request->email;
        if (!Cache::has($key)) {
            Cache::put($key, 1, now()->addMinutes(15));
            $attempts = 1;
        } else {
            $attempts = Cache::increment($key);
        }

        // If attempts >= 3, show the option to reset password
        if ($attempts >= 3) {
            return back()->withErrors([
                'email' => 'Has superado el número máximo de intentos permitidos (3) para iniciar sesión.',
            ])->with([
                'show_forgot_password' => true,
                'failed_email' => $request->email,
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)/',
        ], [
            'password.regex' => 'La contraseña necesita mayúscula, minúscula y número.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Asignar rol de cliente por defecto si existe
        $role = \App\Models\Role::where('slug', 'cliente')->first();
        if ($role) {
            $user->update(['role_id' => $role->id]);
        }

        Auth::login($user);

        $this->logActivity('REGISTER', "Nuevo usuario registrado: {$user->email}", $user->toArray());

        if ($user->hasRole('cliente')) {
            return redirect()->route('landing');
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $this->logActivity('LOGOUT', 'Cierre de sesión');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // Password reset methods

    public function showForgetPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                    'remember_token' => Str::random(60),
                ])->save();

                $this->logActivity('PASSWORD_RESET', "Contraseña restablecida para: {$user->email}");
            }
        );

        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
}