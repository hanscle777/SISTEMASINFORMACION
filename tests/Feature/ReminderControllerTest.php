<?php

namespace Tests\Feature;

use App\Mail\ClienteReminderMail;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReminderControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createManager()
    {
        $permission = Permission::create([
            'name' => 'Gestionar Citas',
            'slug' => 'manage_appointments',
        ]);

        $role = Role::create([
            'name' => 'Recepcionista',
            'slug' => 'recepcionista',
        ]);
        $role->permissions()->sync([$permission->id]);

        return User::create([
            'nombre' => 'Ana',
            'apellido' => 'Martinez',
            'name' => 'Ana Martinez',
            'email' => 'ana@example.com',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
        ]);
    }

    private function createCliente(Role $clienteRole = null)
    {
        if (!$clienteRole) {
            $clienteRole = Role::firstOrCreate([
                'slug' => 'cliente',
            ], [
                'name' => 'Cliente',
            ]);
        }

        $email = sprintf('cliente_%s@example.com', Str::random(8));

        return User::create([
            'nombre' => 'Laura',
            'apellido' => 'Gomez',
            'name' => 'Laura Gomez',
            'email' => $email,
            'password' => bcrypt('password123'),
            'role_id' => $clienteRole->id,
        ]);
    }

    public function test_recordatorio_module_is_accessible_by_user_with_manage_appointments_permission()
    {
        $user = $this->createManager();
        $cliente = $this->createCliente();

        $response = $this->actingAs($user)->get(route('reminders.index'));

        $response->assertOk();
        $response->assertSee('Módulo de Recordatorios');
        $response->assertSee('Laura Gomez');
        $response->assertSee('Enviar mensaje a todos los clientes');
    }

    public function test_send_reminder_email_to_registered_client()
    {
        Mail::fake();

        $user = $this->createManager();
        $cliente = $this->createCliente();

        $response = $this->actingAs($user)->post(route('reminders.send', ['client' => $cliente]), [
            'subject' => 'Recordatorio personalizado',
            'message' => "Hola {$cliente->name},\n\nEste es un recordatorio especial para ti.",
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Mensaje enviado al correo del cliente.');

        Mail::assertSent(ClienteReminderMail::class, 1);
        Mail::assertSent(ClienteReminderMail::class, function ($mail) use ($cliente) {
            return $mail->hasTo($cliente->email);
        });
    }

    public function test_send_reminder_email_to_all_registered_clients()
    {
        Mail::fake();

        $user = $this->createManager();
        $this->createCliente();
        $this->createCliente();

        $response = $this->actingAs($user)->post(route('reminders.sendAll'), [
            'subject' => 'Oferta especial',
            'message' => 'No te pierdas nuestras promociones.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        Mail::assertSent(ClienteReminderMail::class, 2);
    }
}
