@extends('layouts.app')

@section('title', 'Citas - Salon Anita')

@section('header')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Gestión de Citas</h2>
            <p class="text-gray-500 font-medium">Administra las reservas y asignaciones de servicios.</p>
        </div>
        @if(auth()->user()->hasRole('recepcionista') || auth()->user()->hasRole('administrador'))
        <a href="{{ route('citas.create') }}" class="bg-rose-500 hover:bg-rose-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-rose-200 transition-all flex items-center gap-2">
            <i class="fas fa-plus"></i> Agendar Nueva Cita
        </a>
        @endif
    </div>
@endsection

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-rose-50 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="p-4 font-bold border-b border-gray-100 rounded-tl-2xl">Fecha y Hora</th>
                    <th class="p-4 font-bold border-b border-gray-100">Cliente</th>
                    <th class="p-4 font-bold border-b border-gray-100">Servicio</th>
                    <th class="p-4 font-bold border-b border-gray-100">Estilista</th>
                    <th class="p-4 font-bold border-b border-gray-100">Estado</th>
                    <th class="p-4 font-bold border-b border-gray-100 rounded-tr-2xl text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($citas as $cita)
                <tr class="hover:bg-rose-50/30 transition-colors group">
                    <td class="p-4 border-b border-gray-50">
                        <span class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($cita->fecha)->format('d M, Y') }}</span><br>
                        <span class="text-gray-500 text-xs">{{ \Carbon\Carbon::parse($cita->hora)->format('h:i A') }}</span>
                    </td>
                    <td class="p-4 border-b border-gray-50 font-bold text-gray-700">
                        {{ $cita->cliente->name ?? 'N/A' }}
                    </td>
                    <td class="p-4 border-b border-gray-50">
                        {{ $cita->servicio->nombre ?? 'N/A' }}
                    </td>
                    <td class="p-4 border-b border-gray-50">
                        {{ $cita->estilista->name ?? 'Sin Asignar' }}
                    </td>
                    <td class="p-4 border-b border-gray-50">
                        @if($cita->estado == 'pendiente')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-bold">Pendiente</span>
                        @elseif($cita->estado == 'confirmada')
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold">Confirmada</span>
                        @elseif($cita->estado == 'completada')
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold">Completada</span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold">Cancelada</span>
                        @endif
                    </td>
                    <td class="p-4 border-b border-gray-50 text-right space-x-2">
                        <a href="{{ route('citas.edit', $cita->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-amber-50 hover:text-amber-500 transition-colors" title="Editar / Asignar / Cambiar Estado">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if(auth()->user()->hasRole('recepcionista') || auth()->user()->hasRole('administrador'))
                        <form action="{{ route('citas.destroy', $cita->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar cita?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-red-50 hover:text-red-500 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-400">
                        No hay citas registradas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-6">
    {{ $citas->links() }}
</div>
@endsection
