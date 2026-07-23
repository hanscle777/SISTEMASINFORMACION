@extends('layouts.app')

@section('title', 'Nueva Compra - Salon Anita')

@section('header')
<div>
    <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Registrar Compra</h2>
    <p class="text-gray-500 font-medium">Agrega productos al inventario desde un proveedor.</p>
</div>
@endsection

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-rose-50 p-6">
    <form action="{{ route('compras.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Proveedor</label>
                <input type="text" name="proveedor" value="{{ old('proveedor') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de compra</label>
                <input type="date" name="fecha_compra" value="{{ old('fecha_compra', now()->format('Y-m-d')) }}" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
            </div>
        </div>

        <div class="mt-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Observaciones</label>
            <textarea name="observaciones" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">{{ old('observaciones') }}</textarea>
        </div>

        <div class="mt-8 border border-rose-50 rounded-2xl p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Productos incluidos</h3>
                <span class="text-xs font-black uppercase tracking-widest text-rose-400">Se sumará al stock</span>
            </div>

            <div id="items-container" class="space-y-3">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end item-row">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-1">Producto</label>
                        <select name="items[0][producto_id]" class="w-full px-4 py-3 border border-gray-200 rounded-2xl" required>
                            <option value="">Selecciona un producto</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}">{{ $producto->nombre }} (Stock: {{ $producto->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-1">Cantidad</label>
                        <input type="number" name="items[0][cantidad]" min="1" value="1" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-1">Precio unitario</label>
                        <input type="number" step="0.01" name="items[0][precio_unitario]" min="0" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl">
                    </div>
                </div>
            </div>

            <button type="button" onclick="addItemRow()" class="mt-4 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl font-semibold">+ Agregar otro producto</button>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-rose-100">Guardar compra</button>
        </div>
    </form>
</div>

<script>
let itemIndex = 1;
function addItemRow() {
    const container = document.getElementById('items-container');
    const row = document.createElement('div');
    row.className = 'grid grid-cols-1 md:grid-cols-3 gap-3 items-end item-row';
    row.innerHTML = `
        <div>
            <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-1">Producto</label>
            <select name="items[${itemIndex}][producto_id]" class="w-full px-4 py-3 border border-gray-200 rounded-2xl" required>
                <option value="">Selecciona un producto</option>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}">{{ $producto->nombre }} (Stock: {{ $producto->stock }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-1">Cantidad</label>
            <input type="number" name="items[${itemIndex}][cantidad]" min="1" value="1" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-1">Precio unitario</label>
            <input type="number" step="0.01" name="items[${itemIndex}][precio_unitario]" min="0" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl">
        </div>`;
    container.appendChild(row);
    itemIndex++;
}
</script>
@endsection
