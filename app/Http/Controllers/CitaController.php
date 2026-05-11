<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\User;
use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;

class CitaController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $user = auth()->user();
        
        // Si es estilista, solo ve sus citas
        if ($user->hasRole('estilista')) {
            $citas = Cita::with(['cliente', 'servicio'])->where('estilista_id', $user->id)->orderBy('fecha', 'desc')->orderBy('hora', 'desc')->paginate(15);
        } else {
            // Recepcionista o Admin ven todas
            $citas = Cita::with(['cliente', 'estilista', 'servicio'])->orderBy('fecha', 'desc')->orderBy('hora', 'desc')->paginate(15);
        }

        return view('citas.index', compact('citas'));
    }

    // CU11 - Agendar Cita
    public function create(Request $request)
    {
        $clientes = User::whereHas('role', function($q) { $q->where('slug', 'cliente'); })->get();
        $estilistas = User::whereHas('role', function($q) { $q->where('slug', 'estilista'); })->get();
        $servicios = Servicio::all();
        
        // Si se pasa un cliente_id preseleccionado desde la vista de clientes
        $selectedClienteId = $request->query('cliente_id');
        
        return view('citas.create', compact('clientes', 'estilistas', 'servicios', 'selectedClienteId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:users,id',
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'estilista_id' => 'nullable|exists:users,id', // Opcional al crear, puede asignarse luego (CU12)
            'notas' => 'nullable|string'
        ]);

        $cita = Cita::create($request->all());

        $this->logActivity('CREATE', "Cita agendada para el cliente ID: {$cita->cliente_id}", $cita->toArray());

        return redirect()->route('citas.index')->with('success', 'Cita agendada exitosamente.');
    }

    public function edit(Cita $cita)
    {
        $clientes = User::whereHas('role', function($q) { $q->where('slug', 'cliente'); })->get();
        $estilistas = User::whereHas('role', function($q) { $q->where('slug', 'estilista'); })->get();
        $servicios = Servicio::all();
        
        return view('citas.edit', compact('cita', 'clientes', 'estilistas', 'servicios'));
    }

    public function update(Request $request, Cita $cita)
    {
        $request->validate([
            'cliente_id' => 'required|exists:users,id',
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'estilista_id' => 'nullable|exists:users,id', // CU12
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada', // CU19
            'notas' => 'nullable|string'
        ]);

        $oldData = $cita->toArray();
        $cita->update($request->all());

        $this->logActivity('UPDATE', "Cita actualizada ID: {$cita->id}", [
            'old' => $oldData,
            'new' => $cita->fresh()->toArray()
        ]);

        return redirect()->route('citas.index')->with('success', 'Cita actualizada exitosamente.');
    }

    public function destroy(Cita $cita)
    {
        $id = $cita->id;
        $cita->delete();

        $this->logActivity('DELETE', "Cita eliminada ID: {$id}", []);

        return redirect()->route('citas.index')->with('success', 'Cita eliminada exitosamente.');
    }
}
