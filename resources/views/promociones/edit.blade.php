@extends('layouts.app')

@section('title', 'Editar Promoción - Salon Anita')

@section('header')
    <div class="flex items-center space-x-4">
        <a href="{{ route('promociones.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-rose-500 shadow-sm border border-gray-100 transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Editar Promoción</h2>
            <p class="text-gray-500 font-medium">Modifica los detalles de la promoción "{{ $promocion->nombre }}".</p>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-sm border border-rose-50 overflow-hidden">
    <div class="p-8">
        <form action="{{ route('promociones.update', $promocion->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nombre de la Promoción <span class="text-rose-500">*</span></label>
                    <input type="text" name="nombre" value="{{ old('nombre', $promocion->nombre) }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Descripción</label>
                    <textarea name="descripcion" rows="3"
                              class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">{{ old('descripcion', $promocion->descripcion) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Porcentaje de Descuento (%) <span class="text-rose-500">*</span></label>
                    <input type="number" name="descuento_porcentaje" value="{{ old('descuento_porcentaje', number_format($promocion->descuento_porcentaje, 0)) }}" required min="1" max="100"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                </div>

                <div class="flex items-end pb-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="activo" value="1" {{ $promocion->activo ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-rose-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-500"></div>
                        <span class="ml-3 text-sm font-bold text-gray-700">Activa</span>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Fecha Inicio <span class="text-rose-500">*</span></label>
                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $promocion->fecha_inicio->format('Y-m-d')) }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Fecha Fin <span class="text-rose-500">*</span></label>
                    <input type="date" name="fecha_fin" value="{{ old('fecha_fin', $promocion->fecha_fin->format('Y-m-d')) }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                </div>

                <div class="border-t border-rose-50 pt-6 md:col-span-2">
                    <h3 class="text-sm font-bold text-rose-500 mb-4 uppercase tracking-widest">Asociar a (Opcional - Elegir uno)</h3>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Asociar a Servicio</label>
                    <select name="servicio_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                        <option value="">Ninguno</option>
                        @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}" {{ old('servicio_id', $promocion->servicio_id) == $servicio->id ? 'selected' : '' }}>
                                {{ $servicio->nombre }} (Bs{{ $servicio->precio }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Asociar a Producto</label>
                    <select name="producto_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium">
                        <option value="">Ninguno</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}" {{ old('producto_id', $promocion->producto_id) == $producto->id ? 'selected' : '' }}>
                                {{ $producto->nombre }} (Bs{{ $producto->precio_venta }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('promociones.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-bold transition-colors">Cancelar</a>
                <button type="submit" class="px-8 py-3 bg-rose-500 hover:bg-rose-600 text-white rounded-xl font-bold shadow-lg shadow-rose-200 transition-all">
                    Actualizar Promoción
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
