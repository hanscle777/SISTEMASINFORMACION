@extends('layouts.app')

@section('title', 'Reportes - Salon Anita')

@section('header')
<div>
    <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Módulo de Reportes</h2>
    <p class="text-gray-500 font-medium">Accede a métricas clave de ventas, ganancias y stock.</p>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-3xl shadow-sm border border-rose-50 p-6">
        <p class="text-sm font-semibold text-gray-500">Ventas realizadas</p>
        <p class="text-2xl font-extrabold text-gray-900 mt-2">Bs. {{ number_format($ventasTotales, 2) }}</p>
    </div>
    <div class="bg-white rounded-3xl shadow-sm border border-rose-50 p-6">
        <p class="text-sm font-semibold text-gray-500">Ganancia total</p>
        <p class="text-2xl font-extrabold text-emerald-600 mt-2">Bs. {{ number_format($gananciaTotal, 2) }}</p>
    </div>
    <div class="bg-white rounded-3xl shadow-sm border border-rose-50 p-6">
        <p class="text-sm font-semibold text-gray-500">Margen general</p>
        <p class="text-2xl font-extrabold text-rose-600 mt-2">{{ number_format($margenGeneral, 2) }}%</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-3xl shadow-sm border border-rose-50 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-5">Productos más vendidos</h3>
        <div class="space-y-4">
            @forelse($productosTop as $producto)
            <div class="border border-rose-50 rounded-3xl p-4">
                <p class="font-bold text-gray-800">{{ $producto->nombre }}</p>
                <p class="text-sm text-gray-500">Vendidos: {{ $producto->total_vendido }}</p>
                <p class="text-sm text-gray-500">Ingresos: Bs. {{ number_format($producto->ingresos, 2) }}</p>
            </div>
            @empty
            <p class="text-sm text-gray-400">No hay datos de ventas.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-rose-50 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-5">Stock bajo</h3>
        <div class="space-y-4">
            @forelse($stockBajo as $producto)
            <div class="border border-rose-50 rounded-3xl p-4">
                <p class="font-bold text-gray-800">{{ $producto->nombre }}</p>
                <p class="text-sm text-gray-500">Stock: {{ $producto->stock }} / mínimo: {{ $producto->stock_minimo }}</p>
            </div>
            @empty
            <p class="text-sm text-gray-400">No hay productos con stock bajo.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <a href="{{ route('reportes.ventas') }}" class="block bg-white rounded-3xl border border-rose-50 shadow-sm p-6 hover:border-rose-200 transition">
        <p class="text-sm uppercase tracking-widest text-rose-400 font-black">Ventas</p>
        <p class="mt-4 font-bold text-gray-900">Ver reporte completo</p>
    </a>
    <a href="{{ route('reportes.ganancias') }}" class="block bg-white rounded-3xl border border-rose-50 shadow-sm p-6 hover:border-rose-200 transition">
        <p class="text-sm uppercase tracking-widest text-rose-400 font-black">Ganancias</p>
        <p class="mt-4 font-bold text-gray-900">Ver márgenes</p>
    </a>
    <a href="{{ route('reportes.stock') }}" class="block bg-white rounded-3xl border border-rose-50 shadow-sm p-6 hover:border-rose-200 transition">
        <p class="text-sm uppercase tracking-widest text-rose-400 font-black">Stock</p>
        <p class="mt-4 font-bold text-gray-900">Ver productos críticos</p>
    </a>
</div>
@endsection
