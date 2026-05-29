@extends('layouts.app')

@section('title', 'Detalle de Venta - Salon Anita')

@section('header')
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('ventas.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-rose-500 shadow-sm border border-gray-100 transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Venta #{{ $venta->id }}</h2>
                <p class="text-gray-500 font-medium">Visualiza los detalles de la transacción de productos.</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('ventas.ticket', $venta->id) }}" target="_blank" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 transition-all flex items-center gap-2">
                <i class="fas fa-print"></i> Imprimir Ticket
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Tabla de Productos Comprados -->
    <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-rose-50 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-box text-rose-500"></i> Productos Adquiridos
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="p-4 font-bold border-b border-gray-100">Producto</th>
                        <th class="p-4 font-bold border-b border-gray-100">Precio Unitario</th>
                        <th class="p-4 font-bold border-b border-gray-100">Cantidad</th>
                        <th class="p-4 font-bold border-b border-gray-100">Descuento</th>
                        <th class="p-4 font-bold border-b border-gray-100 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($venta->detalles as $detalle)
                    <tr class="hover:bg-rose-50/10">
                        <td class="p-4 border-b border-gray-50">
                            <span class="font-bold text-gray-800">{{ $detalle->producto->nombre }}</span><br>
                            <span class="text-xs text-gray-400">Código: {{ $detalle->producto->codigo }}</span>
                        </td>
                        <td class="p-4 border-b border-gray-50 text-gray-600 font-semibold">
                            Bs{{ number_format($detalle->precio_unitario, 2) }}
                        </td>
                        <td class="p-4 border-b border-gray-50 text-gray-600 font-bold">
                            {{ $detalle->cantidad }}
                        </td>
                        <td class="p-4 border-b border-gray-50 text-rose-500 font-bold">
                            -Bs{{ number_format($detalle->descuento, 2) }}
                        </td>
                        <td class="p-4 border-b border-gray-50 text-right text-gray-800 font-black">
                            Bs{{ number_format($detalle->subtotal, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Resumen del Pedido -->
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-rose-50">
        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fas fa-file-invoice-dollar text-rose-500"></i> Resumen de Transacción
        </h3>

        <div class="space-y-4 text-sm">
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-400 font-bold">Cliente:</span>
                <span class="font-bold text-gray-700">{{ $venta->cliente_nombre }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-400 font-bold">Vendedor:</span>
                <span class="font-bold text-gray-700">{{ $venta->vendedor->name }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-400 font-bold">Método Pago:</span>
                <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs font-extrabold capitalize">
                    {{ $venta->metodo_pago === 'stripe' ? 'Tarjeta en Línea (Stripe)' : $venta->metodo_pago }}
                </span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-400 font-bold">Estado Pago:</span>
                @if($venta->estado_pago === 'completado')
                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded text-xs font-extrabold capitalize">Completado</span>
                @elseif($venta->estado_pago === 'pendiente')
                    <span class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded text-xs font-extrabold capitalize animate-pulse">Pendiente</span>
                @else
                    <span class="px-2 py-0.5 bg-rose-100 text-rose-800 rounded text-xs font-extrabold capitalize">{{ $venta->estado_pago }}</span>
                @endif
            </div>
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-400 font-bold">Fecha:</span>
                <span class="font-bold text-gray-700">{{ $venta->fecha_venta->format('d M, Y h:i A') }}</span>
            </div>
            
            <div class="pt-4 space-y-2">
                <div class="flex justify-between text-gray-500 font-semibold">
                    <span>Subtotal:</span>
                    <span>Bs{{ number_format($venta->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-rose-500 font-bold">
                    <span>Descuento:</span>
                    <span>-Bs{{ number_format($venta->descuento, 2) }}</span>
                </div>
                <div class="border-t border-gray-100 pt-3 flex justify-between text-xl text-gray-800 font-black">
                    <span>Total Pagado:</span>
                    <span class="text-rose-500">Bs{{ number_format($venta->total, 2) }}</span>
                </div>
            </div>
            
            @if($venta->estado_pago === 'pendiente' && auth()->user()->hasPermission('manage_sales'))
                <div class="mt-8 pt-6 border-t border-gray-100 space-y-3">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Acciones de Pago</h4>
                    
                    <form action="{{ route('ventas.update-status', $venta->id) }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="estado_pago" value="completado">
                        <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold shadow-lg shadow-emerald-100 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle"></i> Confirmar Pago Recibido
                        </button>
                    </form>

                    <form action="{{ route('ventas.update-status', $venta->id) }}" method="POST" class="w-full" onsubmit="return confirm('¿Está seguro de que desea cancelar esta transacción? Esta acción no se puede deshacer.')">
                        @csrf
                        <input type="hidden" name="estado_pago" value="cancelado">
                        <button type="submit" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-bold shadow-lg shadow-rose-100 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-times-circle"></i> Cancelar Transacción
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
