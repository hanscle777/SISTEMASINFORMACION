@extends('layouts.app')

@section('title', 'Reporte de Stock - Salon Anita')

@section('header')
<div>
    <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Reporte de Stock</h2>
    <p class="text-gray-500 font-medium">Revisa los productos con stock bajo para reabastecerlos a tiempo.</p>
</div>
@endsection

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-rose-50 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-rose-50/30">
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Producto</th>
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Stock</th>
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Stock mínimo</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-rose-50">
                @forelse($stockBajo as $producto)
                <tr class="hover:bg-rose-50/10 transition-colors">
                    <td class="p-5 font-bold text-gray-800">{{ $producto->nombre }}</td>
                    <td class="p-5 text-sm text-gray-600">{{ $producto->stock }}</td>
                    <td class="p-5 text-sm text-gray-600">{{ $producto->stock_minimo }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="p-10 text-center text-gray-400">
                        <i class="fas fa-archive text-4xl mb-3 block"></i>
                        <p class="font-medium">No hay productos con stock bajo.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
