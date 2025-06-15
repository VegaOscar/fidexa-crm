<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class PuntoController extends Controller
{
    // 🟦 Muestra formulario para canjear puntos
    public function mostrarFormulario($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.canjear', compact('cliente'));
    }

    // 🟩 Procesa el canje de puntos
    public function canjear(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'puntos_a_canjear' => 'required|integer|min:1|max:' . $cliente->puntos,
        ]);

        $cliente->puntos -= $request->puntos_a_canjear;
        $cliente->save();

        return redirect('/clientes')->with('success', 'Puntos canjeados correctamente.');
    }
}
