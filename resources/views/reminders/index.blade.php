@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Módulo de Recordatorios</h1>
            <p class="text-sm text-gray-500">Envía mensajes por correo a tus clientes registrados.</p>
        </div>
        <form action="{{ route('reminders.sendAll') }}" method="POST" class="w-full md:w-auto bg-white rounded-xl shadow p-4">
            @csrf
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-semibold">Asunto</label>
                    <input type="text" name="subject" value="Recordatorio del salón de belleza" class="w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-semibold">Mensaje</label>
                    <textarea name="message" rows="4" class="w-full border rounded px-3 py-2">Hola,\n\nTe recordamos que puedes visitar nuestro salón para agendar tu próxima cita o conocer nuestras promociones.</textarea>
                </div>
                <button type="submit" class="btn btn-primary w-full">Enviar mensaje a todos los clientes</button>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif

    <div class="bg-white shadow rounded p-4">
        <h2 class="text-lg font-semibold mb-4">Clientes registrados</h2>

        @if($clients->isEmpty())
            <p>No hay clientes registrados disponibles.</p>
        @else
            <div class="space-y-3">
                @foreach($clients as $client)
                    <div class="border rounded-lg p-3 flex flex-col md:flex-row md:items-center justify-between gap-3">
                        <div>
                            <p class="font-semibold">{{ $client->name }}</p>
                            <p class="text-sm text-gray-600">{{ $client->email }}</p>
                        </div>
                        <form action="{{ route('reminders.send', ['client' => $client]) }}" method="POST" class="w-full md:w-auto">
                            @csrf
                            <input type="hidden" name="subject" value="Recordatorio para {{ $client->name }}" />
                            <input type="hidden" name="message" value="Hola {{ $client->name }},\n\nTe recordamos que puedes visitar nuestro salón para agendar tu próxima visita o conocer nuestras promociones." />
                            <button type="submit" class="btn btn-secondary w-full md:w-auto">Enviar mensaje</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
