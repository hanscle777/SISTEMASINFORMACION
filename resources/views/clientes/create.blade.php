@extends('layouts.app')

@section('title', 'Registrar Cliente - Salon Anita')

@section('header')
    <div class="flex items-center space-x-4">
        <a href="{{ route('clientes.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-rose-500 shadow-sm border border-gray-100 transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Registrar Nuevo Cliente</h2>
            <p class="text-gray-500 font-medium">Completa los datos para registrar a un cliente en el sistema.</p>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-sm border border-rose-50 overflow-hidden">
    <div class="p-8">
        <form action="{{ route('clientes.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nombre Completo <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium @error('name') border-red-500 @enderror"
                           placeholder="Ej. María López">
                    @error('name') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Correo Electrónico <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-200 focus:border-rose-400 transition-all text-gray-700 font-medium @error('email') border-red-500 @enderror"
                           placeholder="ejemplo@correo.com">
                    @error('email') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('clientes.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-bold transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-8 py-3 bg-rose-500 hover:bg-rose-600 text-white rounded-xl font-bold shadow-lg shadow-rose-200 transition-all">
                    Registrar Cliente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
