@extends('layouts.app')

@section('title', 'Alertas de Stock - Salon Anita')

@section('header')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Alertas del Sistema</h2>
            <p class="text-gray-500 font-medium">Revisa las advertencias automáticas de stock bajo en inventario.</p>
        </div>
        @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('recepcionista'))
        <form action="{{ route('alertas.leer-todas') }}" method="POST">
            @csrf
            <button type="submit" class="bg-gray-900 hover:bg-rose-500 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-md flex items-center gap-2">
                <i class="fas fa-check-double"></i> Marcar todas como leídas
            </button>
        </form>
        @endif
    </div>
@endsection

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-rose-50 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="p-4 font-bold border-b border-gray-100 rounded-tl-2xl">Fecha</th>
                    <th class="p-4 font-bold border-b border-gray-100">Tipo</th>
                    <th class="p-4 font-bold border-b border-gray-100">Detalle del Mensaje</th>
                    <th class="p-4 font-bold border-b border-gray-100">Estado</th>
                    <th class="p-4 font-bold border-b border-gray-100 rounded-tr-2xl text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($alertas as $alerta)
                <tr class="hover:bg-rose-50/10 transition-colors {{ !$alerta->leido ? 'bg-rose-50/20 font-bold' : '' }}">
                    <td class="p-4 border-b border-gray-50 text-gray-500">
                        {{ $alerta->created_at->format('d M, Y h:i A') }}
                    </td>
                    <td class="p-4 border-b border-gray-50">
                        <span class="px-2 py-0.5 bg-rose-100 text-rose-700 rounded text-xs font-black uppercase">
                            {{ str_replace('_', ' ', $alerta->tipo) }}
                        </span>
                    </td>
                    <td class="p-4 border-b border-gray-50 text-gray-800">
                        {{ $alerta->mensaje }}
                    </td>
                    <td class="p-4 border-b border-gray-50">
                        @if(!$alerta->leido)
                            <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-black">Nuevo</span>
                        @else
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-500 rounded-lg text-xs font-medium">Leído</span>
                        @endif
                    </td>
                    <td class="p-4 border-b border-gray-50 text-right">
                        @if(!$alerta->leido)
                        <form action="{{ route('alertas.leer', $alerta->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold px-3 py-1.5 rounded-lg text-xs transition-colors">
                                <i class="fas fa-check"></i> Marcar Leída
                            </button>
                        </form>
                        @else
                        <span class="text-gray-400 text-xs"><i class="fas fa-check-circle"></i> Aceptado</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-400">
                        No hay alertas registradas en el sistema.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-6">
    {{ $alertas->links() }}
</div>
@endsection
