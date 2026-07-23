@extends('layouts.app')

@section('title', 'Nueva Devolución - Salon Anita')

@section('header')
<div>
    <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Registrar Devolución</h2>
    <p class="text-gray-500 font-medium">Suma unidades al stock cuando un producto regresa al inventario.</p>
</div>
@endsection

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-rose-50 p-6">
    <form action="{{ route('devoluciones.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Producto</label>
                <select name="producto_id" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                    <option value="">Selecciona un producto</option>
                    @foreach($productos as $producto)
                        <option value="{{ $producto->id }}">{{ $producto->nombre }} (Stock: {{ $producto->stock }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Cantidad</label>
                <input type="number" name="cantidad" min="1" value="1" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Motivo</label>
                <input type="text" name="motivo" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de devolución</label>
                <input type="date" name="fecha_devolucion" value="{{ now()->format('Y-m-d') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-rose-100">Guardar devolución</button>
        </div>
    </form>
</div>
@endsection
