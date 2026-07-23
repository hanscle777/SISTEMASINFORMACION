<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ClienteReminderMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Traits\LogsActivity;

class ReminderController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $clients = User::whereHas('role', function ($query) {
                $query->where('slug', 'cliente');
            })
            ->whereNotNull('email')
            ->orderBy('name')
            ->get();

        return view('reminders.index', compact('clients'));
    }

    public function send(Request $request, User $client)
    {
        if (!filter_var($client->email, FILTER_VALIDATE_EMAIL)) {
            return back()->with('error', 'El cliente no tiene un correo electrónico válido.');
        }

        $subject = $request->input('subject', 'Recordatorio del salón de belleza');
        $message = $request->input('message', "Hola {$client->name},\n\nTe recordamos que puedes visitar nuestro salón para agendar tu próxima cita o conocer nuestras promociones.");

        Mail::to($client->email)->send(new ClienteReminderMail($client, $subject, $message));

        $this->logActivity('NOTIFY', "Mensaje enviado a cliente {$client->email}", [
            'cliente_id' => $client->id,
            'cliente_email' => $client->email,
            'subject' => $subject,
        ]);

        return back()->with('success', 'Mensaje enviado al correo del cliente.');
    }

    public function sendAll(Request $request)
    {
        $subject = $request->input('subject', 'Recordatorio del salón de belleza');
        $message = $request->input('message', 'Hola,\n\nTe recordamos que puedes visitar nuestro salón para agendar tu próxima cita o conocer nuestras promociones.');

        $clients = User::whereHas('role', function ($query) {
                $query->where('slug', 'cliente');
            })
            ->whereNotNull('email')
            ->get();

        $sent = 0;

        foreach ($clients as $client) {
            if (filter_var($client->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($client->email)->send(new ClienteReminderMail($client, $subject, $message));
                $sent++;
            }
        }

        $this->logActivity('NOTIFY', "Mensajes enviados a {$sent} clientes", ['count' => $sent]);

        return back()->with('success', "Mensajes enviados a {$sent} clientes exitosamente.");
    }
}
