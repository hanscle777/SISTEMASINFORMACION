@extends('layouts.app')

@section('title', 'Reporte de Ventas - Salon Anita')

@section('header')
<div>
    <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Reporte de Ventas</h2>
    <p class="text-gray-500 font-medium">Consulta ventas por periodo y revisa montos totales.</p>
</div>
@endsection

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-rose-50 p-6 mb-8">
    <form action="{{ route('reportes.ventas') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="text-xs font-black text-rose-400 uppercase tracking-widest">Desde</label>
            <input type="date" name="desde" value="{{ $request->desde }}" class="w-full px-4 py-3 border border-gray-200 rounded-2xl">
        </div>
        <div>
            <label class="text-xs font-black text-rose-400 uppercase tracking-widest">Hasta</label>
            <input type="date" name="hasta" value="{{ $request->hasta }}" class="w-full px-4 py-3 border border-gray-200 rounded-2xl">
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white rounded-2xl py-3 font-bold">Filtrar</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-rose-50 overflow-hidden">
    <div class="p-6 border-b border-rose-50">
        <p class="text-sm text-gray-500">Total de ventas filtradas</p>
        <p class="text-2xl font-extrabold text-gray-900">Bs. {{ number_format($totalVentas, 2) }}</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-rose-50/30">
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Fecha</th>
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Cliente</th>
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-rose-50">
                @forelse($ventas as $venta)
                <tr class="hover:bg-rose-50/10 transition-colors">
                    <td class="p-5 text-sm text-gray-600">{{ $venta->fecha_venta->format('d/m/Y') }}</td>
                    <td class="p-5 text-sm text-gray-600">{{ $venta->cliente_nombre }}</td>
                    <td class="p-5 font-bold text-rose-600">Bs. {{ number_format($venta->total, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="p-10 text-center text-gray-400">
                        <i class="fas fa-file-alt text-4xl mb-3 block"></i>
                        <p class="font-medium">No hay ventas en el periodo seleccionado.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
