<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Recompensa;

class CanjePuntosController extends Controller
{
    // Mostrar formulario de canje
    public function show($clienteId)
    {
        $cliente = Cliente::findOrFail($clienteId);
        $recompensas = Recompensa::all();

        return view('canje.index', compact('cliente', 'recompensas'));
    }

    // Procesar el canje
    public function canjear(Request $request, $clienteId)
    {
        $cliente = Cliente::findOrFail($clienteId);
        $recompensa = Recompensa::findOrFail($request->recompensa_id);

        if ($cliente->puntos >= $recompensa->puntos_requeridos) {
            $cliente->puntos -= $recompensa->puntos_requeridos;
            $cliente->save();

            return back()->with('success', '¡Canje realizado con éxito!');
        }

        return back()->with('error', 'Puntos insuficientes.');
    }
}
