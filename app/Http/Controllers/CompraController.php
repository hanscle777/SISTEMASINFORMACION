<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\CompraDetalle;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\LogsActivity;

class CompraController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $compras = Compra::with(['usuario', 'detalles.producto'])
            ->orderBy('fecha_compra', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('compras.index', compact('compras'));
    }

    public function create()
    {
        $productos = Producto::orderBy('nombre')->get();
        return view('compras.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedor' => 'required|string|max:255',
            'fecha_compra' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $compra = Compra::create([
                'proveedor' => $request->proveedor,
                'fecha_compra' => $request->fecha_compra,
                'total' => 0,
                'usuario_id' => auth()->id(),
                'observaciones' => $request->observaciones,
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $producto = Producto::findOrFail($item['producto_id']);
                $cantidad = (int) $item['cantidad'];
                $precioUnitario = (float) $item['precio_unitario'];
                $subtotal = $cantidad * $precioUnitario;

                CompraDetalle::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $subtotal,
                ]);

                $producto->stock = $producto->stock + $cantidad;
                $producto->save();

                $total += $subtotal;
            }

            $compra->total = $total;
            $compra->save();

            DB::commit();

            $this->logActivity('CREATE', "Compra registrada: {$request->proveedor}", $compra->load('detalles.producto')->toArray());

            return redirect()->route('compras.index')->with('success', 'Compra registrada exitosamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'No se pudo registrar la compra: ' . $e->getMessage());
        }
    }
}
