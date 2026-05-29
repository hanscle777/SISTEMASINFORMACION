@extends('layouts.app')

@section('title', 'Registrar Venta de Producto - Salon Anita')

@section('header')
    <div class="flex items-center space-x-4">
        <a href="{{ route('ventas.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-rose-500 shadow-sm border border-gray-100 transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Registrar Venta de Producto</h2>
            <p class="text-gray-500 font-medium">Selecciona los productos y genera la boleta de venta.</p>
        </div>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Formulario Izquierda (Detalle Productos) -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-rose-50">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-shopping-bag text-rose-500"></i> Productos en la Venta
            </h3>
            
            <div id="venta-items-container" class="space-y-4">
                <!-- Filas dinámicas se insertan aquí -->
            </div>

            <div class="mt-6 pt-6 border-t border-gray-100">
                <button type="button" onclick="addProductoRow()" class="px-5 py-2.5 bg-gray-900 hover:bg-rose-500 text-white rounded-xl font-bold transition-all text-sm flex items-center gap-2">
                    <i class="fas fa-plus"></i> Agregar Producto
                </button>
            </div>
        </div>
    </div>

    <!-- Panel Derecha (Totales y Cliente) -->
    <div class="space-y-6">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-rose-50 sticky top-6">
            <form id="venta-form" action="{{ route('ventas.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-user-tag text-rose-500"></i> Cliente y Pago
                </h3>

                <!-- Cliente -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Cliente Registrado</label>
                    <select name="cliente_id" id="cliente_id" onchange="toggleClientType()" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                        <option value="">-- Cliente Casual (No Registrado) --</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->name }} ({{ $cliente->email }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nombre Cliente Casual -->
                <div id="casual-client-name-container">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nombre del Cliente Casual</label>
                    <input type="text" name="cliente_nombre" id="cliente_nombre" placeholder="Ej. Juan Pérez"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                </div>

                <!-- Método de Pago -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Método de Pago <span class="text-rose-500">*</span></label>
                    <select name="metodo_pago" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                        <option value="transferencia">Transferencia / QR</option>
                        <option value="stripe">Tarjeta en Línea (Stripe)</option>
                    </select>
                </div>

                <!-- Resumen de Costos -->
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 space-y-3">
                    <div class="flex justify-between text-sm text-gray-500 font-semibold">
                        <span>Subtotal:</span>
                        <span id="label-subtotal">Bs0.00</span>
                    </div>
                    <div class="flex justify-between text-sm text-rose-500 font-bold">
                        <span>Descuento Promos:</span>
                        <span id="label-descuento">-Bs0.00</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 flex justify-between text-lg text-gray-800 font-black">
                        <span>Total:</span>
                        <span id="label-total">Bs0.00</span>
                    </div>
                </div>

                <!-- Inputs ocultos para enviar los items -->
                <div id="hidden-inputs-container"></div>

                <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white p-4 rounded-xl font-bold transition-all shadow-lg shadow-rose-200 flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> Registrar Venta y Ticket
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Plantilla Fila Producto -->
<template id="producto-row-template">
    <div class="producto-row grid grid-cols-1 md:grid-cols-12 gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-150 relative group">
        <!-- Selector Producto -->
        <div class="md:col-span-6">
            <label class="block text-xs font-bold text-gray-500 mb-1">Producto</label>
            <select class="select-producto w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-rose-200 focus:border-rose-400 font-semibold" onchange="calculateRowTotal(this)">
                <option value="">Selecciona un producto</option>
                @foreach($productos as $prod)
                    <option value="{{ $prod->id }}">{{ $prod->nombre }} (Stock: {{ $prod->stock }})</option>
                @endforeach
            </select>
        </div>

        <!-- Cantidad -->
        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-gray-500 mb-1">Cantidad</label>
            <input type="number" class="input-cantidad w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-rose-200 focus:border-rose-400 font-bold" value="1" min="1" oninput="calculateRowTotal(this)" onchange="calculateRowTotal(this)">
        </div>

        <!-- Desglose de Costo en la Fila -->
        <div class="md:col-span-3 flex flex-col justify-center">
            <span class="text-xs text-gray-400 font-bold">Subtotal: <span class="row-subtotal font-bold text-gray-600">Bs0.00</span></span>
            <span class="text-xs text-rose-500 font-bold">Descuento: <span class="row-descuento font-bold">Bs0.00</span></span>
            <span class="text-sm text-gray-800 font-black">Total: <span class="row-total">Bs0.00</span></span>
        </div>

        <!-- Eliminar Fila -->
        <div class="md:col-span-1 flex items-center justify-end">
            <button type="button" onclick="removeRow(this)" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition-colors">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>
</template>
@endsection

@section('scripts')
<script>
    // Datos serializados de productos y promociones activas
    const productos = @json($productos->keyBy('id'));
    const promociones = @json($promociones); // Keyed by product_id

    document.addEventListener('DOMContentLoaded', () => {
        // Añadir una fila vacía al cargar
        addProductoRow();
        toggleClientType();
    });

    function toggleClientType() {
        const clienteSelect = document.getElementById('cliente_id');
        const container = document.getElementById('casual-client-name-container');
        if (clienteSelect.value) {
            container.style.display = 'none';
        } else {
            container.style.display = 'block';
        }
    }

    function addProductoRow() {
        const container = document.getElementById('venta-items-container');
        const template = document.getElementById('producto-row-template');
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
    }

    function removeRow(button) {
        const rows = document.querySelectorAll('.producto-row');
        if (rows.length <= 1) {
            alert('Debe tener al menos un producto en la venta.');
            return;
        }
        button.closest('.producto-row').remove();
        calculateAllTotals();
    }

    function calculateRowTotal(element) {
        const row = element.closest('.producto-row');
        const prodId = row.querySelector('.select-producto').value;
        const cantidad = parseInt(row.querySelector('.input-cantidad').value) || 0;
        
        let subtotal = 0;
        let descuento = 0;
        let total = 0;

        if (prodId && productos[prodId]) {
            const prod = productos[prodId];
            
            // Check stock limit in frontend
            if (cantidad > prod.stock) {
                alert(`Stock insuficiente. Solo quedan ${prod.stock} unidades de este producto.`);
                row.querySelector('.input-cantidad').value = prod.stock;
                calculateRowTotal(element);
                return;
            }

            const precio = parseFloat(prod.precio_venta);
            subtotal = precio * cantidad;

            // Apply promotion if active
            const promo = promociones[prodId];
            if (promo) {
                const porcentaje = parseFloat(promo.descuento_porcentaje);
                descuento = (subtotal * porcentaje) / 100;
            }

            total = subtotal - descuento;
        }

        row.querySelector('.row-subtotal').innerText = `Bs${subtotal.toFixed(2)}`;
        row.querySelector('.row-descuento').innerText = `Bs${descuento.toFixed(2)}`;
        row.querySelector('.row-total').innerText = `Bs${total.toFixed(2)}`;

        calculateAllTotals();
    }

    function calculateAllTotals() {
        const rows = document.querySelectorAll('.producto-row');
        let subtotalAcumulado = 0;
        let descuentoAcumulado = 0;
        let totalAcumulado = 0;

        const hiddenContainer = document.getElementById('hidden-inputs-container');
        hiddenContainer.innerHTML = ''; // Limpiar inputs ocultos

        rows.forEach((row, index) => {
            const prodId = row.querySelector('.select-producto').value;
            const cantidad = parseInt(row.querySelector('.input-cantidad').value) || 0;

            if (prodId && cantidad > 0) {
                const prod = productos[prodId];
                const precio = parseFloat(prod.precio_venta);
                const sub = precio * cantidad;

                const promo = promociones[prodId];
                let desc = 0;
                if (promo) {
                    desc = (sub * parseFloat(promo.descuento_porcentaje)) / 100;
                }

                subtotalAcumulado += sub;
                descuentoAcumulado += desc;
                totalAcumulado += (sub - desc);

                // Crear inputs ocultos para enviar en el POST
                hiddenContainer.innerHTML += `
                    <input type="hidden" name="items[${index}][producto_id]" value="${prodId}">
                    <input type="hidden" name="items[${index}][cantidad]" value="${cantidad}">
                `;
            }
        });

        document.getElementById('label-subtotal').innerText = `Bs${subtotalAcumulado.toFixed(2)}`;
        document.getElementById('label-descuento').innerText = `-Bs${descuentoAcumulado.toFixed(2)}`;
        document.getElementById('label-total').innerText = `Bs${totalAcumulado.toFixed(2)}`;
    }
</script>
@endsection
