@extends('layouts.app')

@section('title', 'Devoluciones - Salon Anita')

@section('header')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Devoluciones de Productos</h2>
        <p class="text-gray-500 font-medium">Registra devoluciones y mantén el inventario actualizado.</p>
    </div>
    <a href="{{ route('devoluciones.create') }}" class="bg-rose-500 hover:bg-rose-600 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-rose-100 flex items-center justify-center space-x-2">
        <i class="fas fa-undo text-xs"></i>
        <span>Nueva Devolución</span>
    </a>
</div>
@endsection

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-rose-50 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-rose-50/30">
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Producto</th>
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Cantidad</th>
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Motivo</th>
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Fecha</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-rose-50">
                @forelse($devoluciones as $devolucion)
                <tr class="hover:bg-rose-50/10 transition-colors">
                    <td class="p-5 font-bold text-gray-800">{{ $devolucion->producto->nombre ?? 'Producto eliminado' }}</td>
                    <td class="p-5 text-sm text-gray-600">{{ $devolucion->cantidad }}</td>
                    <td class="p-5 text-sm text-gray-600">{{ $devolucion->motivo }}</td>
                    <td class="p-5 text-sm text-gray-600">{{ $devolucion->fecha_devolucion->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-10 text-center text-gray-400">
                        <i class="fas fa-box-open text-4xl mb-3 block"></i>
                        <p class="font-medium">No hay devoluciones registradas.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
