@extends('layouts.app')

@section('title', 'Gestionar Cita - Salon Anita')

@section('header')
    <div class="flex items-center space-x-4">
        <a href="{{ route('citas.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-rose-500 shadow-sm border border-gray-100 transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Gestionar Cita #{{ $cita->id }}</h2>
            <p class="text-gray-500 font-medium">Asigna estilista, cambia estado o edita detalles.</p>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-sm border border-rose-50 overflow-hidden">
    <div class="p-8">
        <form action="{{ route('citas.update', $cita->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Cliente <span class="text-rose-500">*</span></label>
                    <select name="cliente_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ $cita->cliente_id == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Servicio <span class="text-rose-500">*</span></label>
                    <select name="servicio_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                        @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}" {{ $cita->servicio_id == $servicio->id ? 'selected' : '' }}>
                                {{ $servicio->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Fecha <span class="text-rose-500">*</span></label>
                    <input type="date" name="fecha" value="{{ $cita->fecha }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Hora <span class="text-rose-500">*</span></label>
                    <input type="time" name="hora" value="{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                </div>

                <!-- CU12 Asignar Estilista -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Estilista Asignado</label>
                    <select name="estilista_id" class="w-full px-4 py-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all font-bold">
                        <option value="">Sin asignar</option>
                        @foreach($estilistas as $estilista)
                            <option value="{{ $estilista->id }}" {{ $cita->estilista_id == $estilista->id ? 'selected' : '' }}>
                                {{ $estilista->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- CU19 Cambiar a Completado -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Estado de la Cita</label>
                    <select name="estado" required class="w-full px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl focus:ring-2 focus:ring-green-300 focus:border-green-400 transition-all font-bold">
                        <option value="pendiente" {{ $cita->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="confirmada" {{ $cita->estado == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                        <option value="completada" {{ $cita->estado == 'completada' ? 'selected' : '' }}>Completada (Servicio Realizado)</option>
                        <option value="cancelada" {{ $cita->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Notas / Observaciones</label>
                    <textarea name="notas" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">{{ $cita->notas }}</textarea>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('citas.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-bold transition-colors">Cancelar</a>
                <button type="submit" class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold shadow-lg shadow-amber-200 transition-all">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
