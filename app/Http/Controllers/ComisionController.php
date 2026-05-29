<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;

class ComisionController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Comision::with(['estilista', 'cita.cliente', 'cita.servicio']);

        // Filter by logged user if stylist
        if ($user->hasRole('estilista')) {
            $query->where('estilista_id', $user->id);
            $estilistas = collect([$user]);
        } else {
            // Admin or Recepcionista can filter by stylist
            $estilistas = User::whereHas('role', function($q) { $q->where('slug', 'estilista'); })->get();
            if ($request->filled('estilista_id')) {
                $query->where('estilista_id', $request->estilista_id);
            }
        }

        // Filter by state
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Stats calculation
        $statsQuery = Comision::query();
        if ($user->hasRole('estilista')) {
            $statsQuery->where('estilista_id', $user->id);
        } elseif ($request->filled('estilista_id')) {
            $statsQuery->where('estilista_id', $request->estilista_id);
        }

        $totalComisiones = (clone $statsQuery)->sum('monto_comision');
        $comisionesPendientes = (clone $statsQuery)->where('estado', 'pendiente')->sum('monto_comision');
        $comisionesPagadas = (clone $statsQuery)->where('estado', 'pagado')->sum('monto_comision');

        $comisiones = $query->orderBy('fecha_calculo', 'desc')->paginate(15);

        return view('comisiones.index', compact(
            'comisiones', 
            'estilistas', 
            'totalComisiones', 
            'comisionesPendientes', 
            'comisionesPagadas'
        ));
    }

    public function pagar(Request $request, Comision $comision)
    {
        // Only Admin can mark commissions as paid
        if (!auth()->user()->hasRole('administrador')) {
            return back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        $comision->update(['estado' => 'pagado']);

        $this->logActivity('UPDATE', "Comisión ID: {$comision->id} marcada como PAGADA", $comision->toArray());

        return back()->with('success', 'Comisión pagada exitosamente.');
    }
}
