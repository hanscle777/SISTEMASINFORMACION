<?php

namespace App\Http\Controllers;

use App\Models\Devolucion;
use App\Models\Producto;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;

class DevolucionController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $devoluciones = Devolucion::with(['producto', 'usuario'])
            ->orderBy('fecha_devolucion', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('devoluciones.index', compact('devoluciones'));
    }

    public function create()
    {
        $productos = Producto::orderBy('nombre')->get();
        return view('devoluciones.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'required|string|max:255',
            'fecha_devolucion' => 'required|date',
        ]);

        $producto = Producto::findOrFail($request->producto_id);
        $producto->stock += (int) $request->cantidad;
        $producto->save();

        $devolucion = Devolucion::create([
            'producto_id' => $producto->id,
            'cantidad' => (int) $request->cantidad,
            'motivo' => $request->motivo,
            'fecha_devolucion' => $request->fecha_devolucion,
            'usuario_id' => auth()->id(),
        ]);

        $this->logActivity('CREATE', "Devolución registrada: {$producto->nombre}", $devolucion->toArray());

        return redirect()->route('devoluciones.index')->with('success', 'Devolución registrada exitosamente.');
    }
}
