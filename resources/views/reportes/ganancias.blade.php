@extends('layouts.app')

@section('title', 'Reporte de Ganancias - Salon Anita')

@section('header')
<div>
    <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Reporte de Ganancias</h2>
    <p class="text-gray-500 font-medium">Consulta margen y utilidad generados por ventas.</p>
</div>
@endsection

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-rose-50 overflow-hidden mb-8">
    <div class="p-6 border-b border-rose-50">
        <p class="text-sm text-gray-500">Ganancia total</p>
        <p class="text-2xl font-extrabold text-emerald-600">Bs. {{ number_format($gananciaTotal, 2) }}</p>
        <p class="text-sm text-gray-500 mt-2">Margen general: {{ number_format($margenGeneral, 2) }}%</p>
    </div>
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
                        <p class="font-medium">No hay datos de ganancias para mostrar.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
