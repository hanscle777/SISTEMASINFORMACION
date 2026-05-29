<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use Illuminate\Http\Request;

class AlertaController extends Controller
{
    public function index()
    {
        $alertas = Alerta::with('producto')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('alertas.index', compact('alertas'));
    }

    public function marcarLeida(Alerta $alerta)
    {
        $alerta->update(['leido' => true]);
        return back()->with('success', 'Alerta marcada como leída.');
    }

    public function marcarTodasLeidas()
    {
        Alerta::where('leido', false)->update(['leido' => true]);
        return back()->with('success', 'Todas las alertas fueron marcadas como leídas.');
    }
}
