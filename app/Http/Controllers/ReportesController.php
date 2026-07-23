<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;

class ReportesController extends Controller
{
    public function index()
    {
        $ventas = Venta::with('detalles.producto')
            ->where('estado_pago', 'completado')
            ->get();

        $ventasTotales = $ventas->sum('total');
        $gananciaTotal = 0;
        $ingresos = 0;

        foreach ($ventas as $venta) {
            foreach ($venta->detalles as $detalle) {
                $costo = ($detalle->producto->precio_compra ?? 0) * $detalle->cantidad;
                $gananciaTotal += $detalle->subtotal - $costo;
                $ingresos += $detalle->subtotal;
            }
        }

        $margenGeneral = $ingresos > 0 ? ($gananciaTotal / $ingresos) * 100 : 0;

        $productosTop = Producto::select('productos.id', 'productos.nombre')
            ->join('venta_detalles', 'productos.id', '=', 'venta_detalles.producto_id')
            ->join('ventas', 'ventas.id', '=', 'venta_detalles.venta_id')
            ->where('ventas.estado_pago', 'completado')
            ->groupBy('productos.id', 'productos.nombre')
            ->selectRaw('SUM(venta_detalles.cantidad) as total_vendido, SUM(venta_detalles.subtotal) as ingresos')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        $stockBajo = Producto::whereColumn('stock', '<=', 'stock_minimo')
            ->orWhereRaw('stock <= stock_minimo + 5')
            ->orderBy('stock')
            ->get();

        return view('reportes.index', compact('ventasTotales', 'gananciaTotal', 'margenGeneral', 'productosTop', 'stockBajo'));
    }

    public function ventas(Request $request)
    {
        $query = Venta::where('estado_pago', 'completado');

        if ($request->filled('desde')) {
            $query->whereDate('fecha_venta', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->whereDate('fecha_venta', '<=', $request->hasta);
        }

        $ventas = $query->orderBy('fecha_venta', 'desc')->get();
        $totalVentas = $ventas->sum('total');

        return view('reportes.ventas', compact('ventas', 'totalVentas', 'request'));
    }

    public function ganancias()
    {
        $ventas = Venta::with('detalles.producto')
            ->where('estado_pago', 'completado')
            ->orderBy('fecha_venta', 'desc')
            ->get();

        $items = [];
        $gananciaTotal = 0;
        $ingresos = 0;

        foreach ($ventas as $venta) {
            foreach ($venta->detalles as $detalle) {
                $costo = ($detalle->producto->precio_compra ?? 0) * $detalle->cantidad;
                $ganancia = $detalle->subtotal - $costo;
                $margen = $detalle->subtotal > 0 ? ($ganancia / $detalle->subtotal) * 100 : 0;

                $items[] = [
                    'venta_id' => $venta->id,
                    'producto' => $detalle->producto->nombre,
                    'cantidad' => $detalle->cantidad,
                    'ingreso' => $detalle->subtotal,
                    'costo' => $costo,
                    'ganancia' => $ganancia,
                    'margen' => $margen,
                    'fecha' => $venta->fecha_venta,
                ];

                $gananciaTotal += $ganancia;
                $ingresos += $detalle->subtotal;
            }
        }

        $margenGeneral = $ingresos > 0 ? ($gananciaTotal / $ingresos) * 100 : 0;

        return view('reportes.ganancias', compact('items', 'gananciaTotal', 'margenGeneral'));
    }

    public function productos()
    {
        $productosTop = Producto::select('productos.id', 'productos.nombre')
            ->join('venta_detalles', 'productos.id', '=', 'venta_detalles.producto_id')
            ->join('ventas', 'ventas.id', '=', 'venta_detalles.venta_id')
            ->where('ventas.estado_pago', 'completado')
            ->groupBy('productos.id', 'productos.nombre')
            ->selectRaw('SUM(venta_detalles.cantidad) as total_vendido, SUM(venta_detalles.subtotal) as ingresos')
            ->orderByDesc('total_vendido')
            ->get();

        return view('reportes.productos', compact('productosTop'));
    }

    public function stock()
    {
        $stockBajo = Producto::whereColumn('stock', '<=', 'stock_minimo')
            ->orWhereRaw('stock <= stock_minimo + 5')
            ->orderBy('stock')
            ->get();

        return view('reportes.stock', compact('stockBajo'));
    }
}
