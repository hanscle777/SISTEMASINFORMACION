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
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Servicio <span class="text-rose-500">*</span></label>
                    <select name="servicio_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                        <option value="">Seleccione un servicio</option>
                        @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}">{{ $servicio->nombre }} - Bs{{ $servicio->precio }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Fecha <span class="text-rose-500">*</span></label>
                    <input type="date" name="fecha" value="{{ old('fecha') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Hora <span class="text-rose-500">*</span></label>
                    <input type="time" name="hora" value="{{ old('hora') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Estilista (Opcional)</label>
                    <select name="estilista_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                        <option value="">Sin asignar / Asignar más tarde</option>
                        @foreach($estilistas as $estilista)
                            <option value="{{ $estilista->id }}">{{ $estilista->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Notas / Observaciones</label>
                    <textarea name="notas" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium" placeholder="Opcional..."></textarea>
                </div>
            </div>

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
