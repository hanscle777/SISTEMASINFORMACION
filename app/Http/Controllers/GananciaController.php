<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;

class GananciaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with(['detalles.producto'])
            ->where('estado_pago', 'completado')
            ->orderBy('fecha_venta', 'desc')
            ->get();

        $items = [];
        $gananciaTotal = 0;
        $ventasTotales = 0;

        foreach ($ventas as $venta) {
            foreach ($venta->detalles as $detalle) {
                $producto = $detalle->producto;
                if (!$producto) {
                    continue;
                }

                $costo = ($producto->precio_compra ?? 0) * $detalle->cantidad;
                $ingreso = $detalle->subtotal;
                $ganancia = $ingreso - $costo;
                $margen = $ingreso > 0 ? ($ganancia / $ingreso) * 100 : 0;

                $gananciaTotal += $ganancia;
                $ventasTotales += $ingreso;

                $items[] = [
                    'venta_id' => $venta->id,
                    'producto' => $producto->nombre,
                    'cantidad' => $detalle->cantidad,
                    'ingreso' => $ingreso,
                    'costo' => $costo,
                    'ganancia' => $ganancia,
                    'margen' => $margen,
                    'fecha' => $venta->fecha_venta,
                ];
            }
        }

        $margenGeneral = $ventasTotales > 0 ? ($gananciaTotal / $ventasTotales) * 100 : 0;

        return view('ganancias.index', compact('items', 'gananciaTotal', 'ventasTotales', 'margenGeneral'));
    }
}
