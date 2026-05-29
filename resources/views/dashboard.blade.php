@extends('layouts.app')

@section('title', 'Inicio - Salón de Belleza Anita')

@section('header')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Inicio</h1>
        <p class="text-gray-500 font-medium">Panel de control de tu salón de belleza premium.</p>
    </div>
    <div class="bg-white px-5 py-3 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-2">
        <i class="far fa-calendar text-rose-500"></i>
        <span class="text-gray-700 font-bold text-sm tracking-wide">{{ now()->translatedFormat('d \d\e F, Y') }}</span>
    </div>
</div>
@endsection

@section('content')
<!-- Grid de Estadísticas Premium -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Bienvenida -->
    <div class="bg-gradient-to-br from-rose-500 to-rose-600 p-6 rounded-3xl text-white shadow-lg shadow-rose-100 flex flex-col justify-between col-span-1 md:col-span-2">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-2xl font-bold mb-1">¡Hola, {{ explode(' ', auth()->user()->name ?? auth()->user()->email)[0] }}!</h3>
                <p class="text-rose-150 text-xs font-semibold">
                    @if(auth()->user()->hasRole('administrador'))
                        Panel de Administración General.
                    @elseif(auth()->user()->hasRole('recepcionista'))
                        Gestión de reservas y caja diaria.
                    @elseif(auth()->user()->hasRole('estilista'))
                        Consulta tu agenda y comisiones del día.
                    @else
                        Bienvenido a tu cuenta.
                    @endif
                </p>
            </div>
            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-xl">
                <i class="fas fa-sparkles"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-white/15 flex items-center justify-between text-xs font-bold uppercase tracking-wider text-rose-100">
            <span>Rol: {{ auth()->user()->role->name ?? 'Usuario' }}</span>
            <span class="bg-white/20 px-2.5 py-1 rounded-lg text-white">Sesión Activa</span>
        </div>
    </div>

    <!-- Citas de Hoy -->
    <a href="{{ route('citas.index') }}" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center text-lg transition-transform group-hover:scale-110">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-2 py-1 rounded-md">Citas Hoy</span>
        </div>
        <div>
            <h3 class="text-3xl font-extrabold text-gray-900 leading-none mb-1">{{ $stats['appointments_today'] }}</h3>
            <p class="text-xs text-gray-500 font-semibold flex items-center gap-1">
                Ver agenda
                <i class="fas fa-arrow-right text-[10px] text-gray-400 group-hover:translate-x-1 transition-transform"></i>
            </p>
        </div>
    </a>

    <!-- Comisiones Pendientes -->
    <a href="{{ route('comisiones.index') }}" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-lg transition-transform group-hover:scale-110">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <span class="text-[10px] font-bold text-amber-500 uppercase tracking-widest bg-amber-50 px-2 py-1 rounded-md">Comisión Pendiente</span>
        </div>
        <div>
            <h3 class="text-3xl font-extrabold text-gray-900 leading-none mb-1">Bs{{ number_format($stats['pending_commissions'], 2) }}</h3>
            <p class="text-xs text-gray-500 font-semibold flex items-center gap-1">
                Ver reporte
                <i class="fas fa-arrow-right text-[10px] text-gray-400 group-hover:translate-x-1 transition-transform"></i>
            </p>
        </div>
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @if(!auth()->user()->hasRole('estilista'))
    <!-- Ventas Totales -->
    <a href="{{ route('ventas.index') }}" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-lg transition-transform group-hover:scale-110">
                <i class="fas fa-cash-register"></i>
            </div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-2 py-1 rounded-md">Caja Total</span>
        </div>
        <div>
            <h3 class="text-3xl font-extrabold text-gray-900 leading-none mb-1">Bs{{ number_format($stats['total_sales'], 2) }}</h3>
            <p class="text-xs text-gray-500 font-semibold flex items-center gap-1">
                Historial de ventas
                <i class="fas fa-arrow-right text-[10px] text-gray-400 group-hover:translate-x-1 transition-transform"></i>
            </p>
        </div>
    </a>

    <!-- Alertas Stock Bajo -->
    <a href="{{ route('alertas.index') }}" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center text-lg transition-transform group-hover:scale-110">
                <i class="fas fa-bell"></i>
            </div>
            @if($stats['unread_alerts_count'] > 0)
            <span class="text-[10px] font-bold text-white uppercase tracking-widest bg-rose-600 px-2 py-1 rounded-md animate-pulse">Crítico</span>
            @else
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-2 py-1 rounded-md">Estable</span>
            @endif
        </div>
        <div>
            <h3 class="text-3xl font-extrabold text-gray-900 leading-none mb-1">{{ $stats['unread_alerts_count'] }}</h3>
            <p class="text-xs text-gray-500 font-semibold flex items-center gap-1">
                Alertas pendientes
                <i class="fas fa-arrow-right text-[10px] text-gray-400 group-hover:translate-x-1 transition-transform"></i>
            </p>
        </div>
    </a>
    @endif

    <!-- Catálogo Productos -->
    <a href="{{ route('productos.index') }}" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-violet-50 text-violet-500 rounded-2xl flex items-center justify-center text-lg transition-transform group-hover:scale-110">
                <i class="fas fa-box-open"></i>
            </div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-2 py-1 rounded-md">Catálogo</span>
        </div>
        <div>
            <h3 class="text-3xl font-extrabold text-gray-900 leading-none mb-1">{{ $stats['productos_count'] }}</h3>
            <p class="text-xs text-gray-500 font-semibold flex items-center gap-1">
                Gestionar productos
                <i class="fas fa-arrow-right text-[10px] text-gray-400 group-hover:translate-x-1 transition-transform"></i>
            </p>
        </div>
    </a>

    <!-- Proveedores (Promotores) -->
    <a href="{{ route('promotores.index') }}" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center text-lg transition-transform group-hover:scale-110">
                <i class="fas fa-truck"></i>
            </div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-2 py-1 rounded-md">Proveedores</span>
        </div>
        <div>
            <h3 class="text-3xl font-extrabold text-gray-900 leading-none mb-1">{{ $stats['promotores_count'] }}</h3>
            <p class="text-xs text-gray-500 font-semibold flex items-center gap-1">
                Ver proveedores
                <i class="fas fa-arrow-right text-[10px] text-gray-400 group-hover:translate-x-1 transition-transform"></i>
            </p>
        </div>
    </a>
</div>

<!-- Grid de Información Operativa -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Columna Izquierda: Alertas y Registro de Ventas -->
    <div class="space-y-6">
        @if(!auth()->user()->hasRole('estilista'))
        <!-- Panel Alertas de Stock Recientes -->
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Alertas de Stock Crítico</h2>
                    <p class="text-xs text-gray-500 font-medium">Acción rápida para reposición de productos.</p>
                </div>
                <a href="{{ route('alertas.index') }}" class="text-rose-500 text-xs font-bold uppercase hover:underline">Ver todas</a>
            </div>

            <div class="space-y-4">
                @forelse($unread_alerts as $alerta)
                <div class="p-4 bg-rose-50/50 rounded-2xl border border-rose-100 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-rose-100 text-rose-600 rounded-xl flex items-center justify-center shrink-0">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">{{ $alerta->producto->nombre ?? 'Producto' }}</h4>
                            <p class="text-xs text-gray-500">Mínimo: {{ $alerta->producto->stock_minimo ?? 0 }} | Disponible: <span class="text-rose-600 font-bold">{{ $alerta->producto->stock ?? 0 }}</span></p>
                        </div>
                    </div>
                    <form action="{{ route('alertas.leer', $alerta->id) }}" method="POST" class="shrink-0">
                        @csrf
                        <button type="submit" class="bg-white hover:bg-rose-500 hover:text-white text-rose-600 border border-rose-200 hover:border-rose-500 font-bold px-3 py-1.5 rounded-xl text-xs transition-all shadow-sm">
                            Marcar Leída
                        </button>
                    </form>
                </div>
                @empty
                <div class="text-center py-8 border border-dashed border-gray-100 rounded-2xl flex flex-col items-center justify-center">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-lg mb-2">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <p class="text-gray-400 text-sm font-medium">Todo al día. No hay alertas de stock bajo.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Ventas Recientes -->
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Ventas Recientes</h2>
                    <p class="text-xs text-gray-500 font-medium">Últimos movimientos registrados en el día.</p>
                </div>
                <a href="{{ route('ventas.create') }}" class="bg-rose-500 hover:bg-rose-600 text-white font-bold text-xs uppercase px-4 py-2 rounded-xl transition-all shadow-md shadow-rose-100 flex items-center gap-1">
                    <i class="fas fa-plus"></i> Nueva Venta
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">
                            <th class="pb-3">Cliente</th>
                            <th class="pb-3">Pago</th>
                            <th class="pb-3 text-right">Total</th>
                            <th class="pb-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-semibold text-gray-700">
                        @forelse($recent_sales as $sale)
                        <tr class="border-t border-gray-50 hover:bg-gray-50/50 transition-colors">
                            <td class="py-3.5">
                                <span class="font-bold text-gray-800">{{ $sale->cliente_nombre }}</span><br>
                                <span class="text-[10px] text-gray-400 font-medium">{{ $sale->fecha_venta->format('d/m/Y h:i A') }}</span>
                            </td>
                            <td class="py-3.5 capitalize text-xs text-gray-500">
                                {{ $sale->metodo_pago }}
                            </td>
                            <td class="py-3.5 text-right font-black text-gray-950">
                                Bs{{ number_format($sale->total, 2) }}
                            </td>
                            <td class="py-3.5 text-right">
                                <div class="inline-flex gap-1.5">
                                    <a href="{{ route('ventas.show', $sale->id) }}" class="w-7 h-7 bg-gray-50 hover:bg-rose-50 text-gray-400 hover:text-rose-500 rounded-lg flex items-center justify-center transition-colors" title="Detalle">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('ventas.ticket', $sale->id) }}" target="_blank" class="w-7 h-7 bg-gray-50 hover:bg-rose-50 text-gray-400 hover:text-rose-500 rounded-lg flex items-center justify-center transition-colors" title="Ticket">
                                        <i class="fas fa-print text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-400 text-sm italic">Sin ventas registradas hoy.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <!-- Sección informativa para Estilistas -->
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-cut text-rose-500"></i> Mi Agenda
            </h2>
            <div class="p-6 bg-rose-50/50 rounded-2xl border border-rose-100 text-center">
                <i class="far fa-calendar-star text-rose-500 text-4xl mb-3"></i>
                <h4 class="font-bold text-gray-800 text-sm">Control de Citas</h4>
                <p class="text-gray-500 text-xs mt-1">Revisa tu agenda programada haciendo clic en la sección Citas del menú.</p>
                <a href="{{ route('citas.index') }}" class="inline-flex items-center gap-2 mt-4 bg-rose-500 hover:bg-rose-600 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-md">
                    Ver Mis Citas <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- Columna Derecha: Bitácora de Actividad o Publicidad Interna -->
    <div class="space-y-6">
        @if(auth()->user()->hasPermission('view_audit_log'))
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Bitácora del Sistema</h2>
                    <p class="text-xs text-gray-500 font-medium">Últimos eventos registrados por el personal.</p>
                </div>
                <a href="{{ route('activity_logs.index') }}" class="text-rose-500 text-xs font-bold uppercase hover:underline">Ver todo</a>
            </div>

            <div class="space-y-4">
                @forelse($recent_logs as $log)
                <div class="flex items-start gap-4 p-3 hover:bg-gray-50/50 rounded-2xl transition">
                    <div class="w-1.5 h-10 rounded-full shrink-0 mt-0.5
                        @if($log->action === 'CREATE' || $log->action === 'REGISTER') bg-emerald-400
                        @elseif($log->action === 'DELETE') bg-rose-400
                        @elseif($log->action === 'LOGIN') bg-blue-400
                        @else bg-amber-400 @endif">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-800 truncate" title="{{ $log->description }}">{{ $log->description }}</p>
                        <p class="text-[10px] text-gray-400 font-semibold uppercase mt-0.5">
                            {{ $log->created_at->diffForHumans() }}
                            @if($log->user)
                            <span class="mx-1 text-gray-200">•</span>
                            <span class="text-gray-400">Por: {{ explode('@', $log->user->email)[0] }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-400 py-10 text-sm italic">Sin movimientos recientes.</p>
                @endforelse
            </div>
        </div>
        @else
        <!-- Banner Catálogo para Estilistas y otros roles -->
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm flex flex-col h-full">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Catálogo de Productos</h2>
            <p class="text-gray-500 text-sm mb-6">Consulta los productos y cosméticos de alta gama disponibles para la venta o uso en tratamientos.</p>
            <div class="flex-1 flex flex-col items-center justify-center py-8 text-center border-2 border-dashed border-rose-100 bg-rose-50/20 rounded-2xl">
                <i class="fas fa-shopping-bag text-rose-200 text-5xl mb-3 animate-bounce"></i>
                <p class="text-gray-400 text-xs mb-4 max-w-[220px]">Monitorea las existencias, marcas y precios vigentes.</p>
                <a href="{{ route('productos.index') }}" class="bg-gray-950 text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:scale-105 transition-all text-xs">
                    Ver Productos
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
