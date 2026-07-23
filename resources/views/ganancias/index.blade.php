@extends('layouts.app')

@section('title', 'Ganancias - Salon Anita')

@section('header')
<div>
    <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Ganancias</h2>
    <p class="text-gray-500 font-medium">Consulta la utilidad generada por las ventas registradas.</p>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-3xl shadow-sm border border-rose-50 p-6">
        <p class="text-sm font-semibold text-gray-500">Ventas Totales</p>
        <p class="text-2xl font-extrabold text-gray-900 mt-2">Bs. {{ number_format($ventasTotales, 2) }}</p>
    </div>
    <div class="bg-white rounded-3xl shadow-sm border border-rose-50 p-6">
        <p class="text-sm font-semibold text-gray-500">Ganancia Total</p>
        <p class="text-2xl font-extrabold text-emerald-600 mt-2">Bs. {{ number_format($gananciaTotal, 2) }}</p>
    </div>
    <div class="bg-white rounded-3xl shadow-sm border border-rose-50 p-6">
        <p class="text-sm font-semibold text-gray-500">Margen General</p>
        <p class="text-2xl font-extrabold text-rose-600 mt-2">{{ number_format($margenGeneral, 2) }}%</p>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-rose-50 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-rose-50/30">
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Fecha</th>
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Producto</th>
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Cantidad</th>
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Ingreso</th>
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Costo</th>
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Ganancia</th>
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Margen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-rose-50">
                @forelse($items as $item)
                <tr class="hover:bg-rose-50/10 transition-colors">
                    <td class="p-5 text-sm text-gray-600">{{ $item['fecha']->format('d/m/Y') }}</td>
                    <td class="p-5 font-bold text-gray-800">{{ $item['producto'] }}</td>
                    <td class="p-5 text-sm text-gray-600">{{ $item['cantidad'] }}</td>
                    <td class="p-5 text-sm text-gray-600">Bs. {{ number_format($item['ingreso'], 2) }}</td>
                    <td class="p-5 text-sm text-gray-600">Bs. {{ number_format($item['costo'], 2) }}</td>
                    <td class="p-5 font-bold text-emerald-600">Bs. {{ number_format($item['ganancia'], 2) }}</td>
                    <td class="p-5 font-bold text-rose-600">{{ number_format($item['margen'], 2) }}%</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-10 text-center text-gray-400">
                        <i class="fas fa-chart-line text-4xl mb-3 block"></i>
                        <p class="font-medium">No hay ventas completadas con datos de utilidad.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
