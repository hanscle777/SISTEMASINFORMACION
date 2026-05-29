<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use App\Models\Promotor;
use App\Models\Producto;
use App\Models\Cita;
use App\Models\Venta;
use App\Models\Alerta;
use App\Models\Comision;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('cliente')) {
            return redirect()->route('landing');
        }
        
        $logsCount = ActivityLog::count();
        $promotoresCount = Promotor::count();
        $productosCount = Producto::count();
        
        $today = Carbon::today()->toDateString();
        
        // Appointments count depending on role
        if ($user->hasRole('estilista')) {
            $appointmentsToday = Cita::where('estilista_id', $user->id)->whereDate('fecha', $today)->count();
            $totalSales = 0; // Stylists don't see general sales
            $unreadAlertsCount = 0;
            $commissionsSummary = Comision::where('estilista_id', $user->id)->where('estado', 'pendiente')->sum('monto_comision');
        } else {
            $appointmentsToday = Cita::whereDate('fecha', $today)->count();
            $totalSales = Venta::sum('total');
            $unreadAlertsCount = Alerta::where('leido', false)->count();
            $commissionsSummary = Comision::where('estado', 'pendiente')->sum('monto_comision');
        }

        \Illuminate\Support\Facades\Log::info("Dashboard accedido por {$user->email}. Alertas activas: " . $unreadAlertsCount);
        
        return view('dashboard', [
            'user' => $user,
            'stats' => [
                'users_count' => User::count(),
                'roles_count' => Role::count(),
                'logs_count' => $logsCount,
                'promotores_count' => $promotoresCount,
                'productos_count' => $productosCount,
                'appointments_today' => $appointmentsToday,
                'total_sales' => $totalSales,
                'unread_alerts_count' => $unreadAlertsCount,
                'pending_commissions' => $commissionsSummary
            ],
            'recent_logs' => ActivityLog::with('user')->latest()->take(5)->get(),
            'recent_sales' => !$user->hasRole('estilista') ? Venta::latest()->take(5)->get() : collect(),
            'unread_alerts' => !$user->hasRole('estilista') ? Alerta::with('producto')->where('leido', false)->latest()->take(5)->get() : collect(),
        ]);
    }
}
