@extends('layouts.app')

@section('title', 'Compras - Salon Anita')

@section('header')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Compras de Inventario</h2>
        <p class="text-gray-500 font-medium">Registra abastecimiento de productos y revisa el historial de compras.</p>
    </div>
    <a href="{{ route('compras.create') }}" class="bg-rose-500 hover:bg-rose-600 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-rose-100 flex items-center justify-center space-x-2">
        <i class="fas fa-plus text-xs"></i>
        <span>Nueva Compra</span>
    </a>
</div>
@endsection

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-rose-50 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-rose-50/30">
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Proveedor</th>
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Fecha</th>
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Registrado por</th>
                    <th class="p-5 text-xs font-black text-rose-400 uppercase tracking-widest border-b border-rose-50">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-rose-50">
                @forelse($compras as $compra)
                <tr class="hover:bg-rose-50/10 transition-colors">
                    <td class="p-5">
                        <p class="font-bold text-gray-800">{{ $compra->proveedor }}</p>
                        @if($compra->observaciones)
                        <p class="text-xs text-gray-400 mt-1">{{ $compra->observaciones }}</p>
                        @endif
                    </td>
                    <td class="p-5 text-sm text-gray-600">{{ $compra->fecha_compra->format('d/m/Y') }}</td>
                    <td class="p-5 text-sm text-gray-600">{{ $compra->usuario->name ?? $compra->usuario->email }}</td>
                    <td class="p-5 font-bold text-rose-600">Bs. {{ number_format($compra->total, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-10 text-center text-gray-400">
                        <i class="fas fa-shopping-cart text-4xl mb-3 block"></i>
                        <p class="font-medium">No hay compras registradas aún.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
