@extends('layouts.app')

@section('title', 'Agendar Cita - Salon Anita')

@section('header')
    <div class="flex items-center space-x-4">
        <a href="{{ route('citas.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-rose-500 shadow-sm border border-gray-100 transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Agendar Cita</h2>
            <p class="text-gray-500 font-medium">Programa un servicio para un cliente.</p>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-sm border border-rose-50 overflow-hidden">
    <div class="p-8">
        <form action="{{ route('citas.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cliente -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Cliente <span class="text-rose-500">*</span></label>
                    <select name="cliente_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                        <option value="">Seleccione un cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ (isset($selectedClienteId) && $selectedClienteId == $cliente->id) ? 'selected' : '' }}>
                                {{ $cliente->name }} ({{ $cliente->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Servicio -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Servicio <span class="text-rose-500">*</span></label>
                    <select name="servicio_id" id="servicio_id" onchange="checkPromotion()" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                        <option value="">Seleccione un servicio</option>
                        @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}" data-precio="{{ $servicio->precio }}">{{ $servicio->nombre }} - Bs{{ $servicio->precio }}</option>
                        @endforeach
                    </select>
                    
                    <!-- Contenedor Promoción Reactiva -->
                    <div id="promo-badge-container" class="mt-2.5 hidden">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 text-rose-600 text-xs font-black border border-rose-100">
                            <i class="fas fa-percentage animate-pulse"></i>
                            <span id="promo-text">Promoción Activa</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1 font-bold">
                            Precio Final: <span class="text-emerald-600 font-black text-sm" id="promo-price">Bs0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Fecha -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Fecha <span class="text-rose-500">*</span></label>
                    <input type="date" name="fecha" value="{{ old('fecha') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                </div>

                <!-- Hora -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Hora <span class="text-rose-500">*</span></label>
                    <input type="time" name="hora" value="{{ old('hora') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                </div>

                <!-- Estilista -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Estilista (Opcional)</label>
                    <select name="estilista_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                        <option value="">Sin asignar / Asignar más tarde</option>
                        @foreach($estilistas as $estilista)
                            <option value="{{ $estilista->id }}">{{ $estilista->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Notas -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Notas / Observaciones</label>
                    <textarea name="notas" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium" placeholder="Notas sobre el servicio o requerimiento especial..."></textarea>
                </div>
            </div>

            <!-- Botones -->
            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('citas.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-bold transition-colors">Cancelar</a>
                <button type="submit" class="px-8 py-3 bg-rose-500 hover:bg-rose-600 text-white rounded-xl font-bold shadow-lg shadow-rose-200 transition-all">
                    Guardar Cita
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // active service promotions keyed by service_id
    const promociones = @json($promociones);

    function checkPromotion() {
        const select = document.getElementById('servicio_id');
        const selectedOption = select.options[select.selectedIndex];
        const container = document.getElementById('promo-badge-container');
        
        if (!selectedOption || !selectedOption.value) {
            container.classList.add('hidden');
            return;
        }

        const precio = parseFloat(selectedOption.dataset.precio) || 0;
        const serviceId = selectedOption.value;
        const promo = promociones[serviceId];

        if (promo) {
            const descPorcentaje = parseFloat(promo.descuento_porcentaje);
            const descuentoVal = (precio * descPorcentaje) / 100;
            const precioFinal = precio - descuentoVal;

            document.getElementById('promo-text').innerText = `¡Promoción Activa! ${descPorcentaje}% de descuento`;
            document.getElementById('promo-price').innerText = `Bs${precioFinal.toFixed(2)} (Ahorras Bs${descuentoVal.toFixed(2)})`;
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }
</script>
@endsection
