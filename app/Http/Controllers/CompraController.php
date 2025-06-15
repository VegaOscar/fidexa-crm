<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\Cliente;

class CompraController extends Controller
{
    // 🧾 Mostrar compras de un cliente específico
    public function index($clienteId)
    {
        $cliente = Cliente::findOrFail($clienteId);
        $compras = $cliente->compras()->orderBy('fecha', 'desc')->get();

        return view('compras.index', compact('cliente', 'compras'));
    }

    // ➕ Formulario para crear una compra
    public function create($clienteId)
    {
        $cliente = Cliente::findOrFail($clienteId);
        return view('compras.create', compact('cliente'));
    }

    // 💾 Guardar una compra
    public function store(Request $request, $clienteId)
    {
        $request->validate([
            'fecha' => 'required|date',
            'monto' => 'required|numeric',
            'sucursal' => 'nullable|string',
            'tipo_movimiento' => 'nullable|string',
            'documento_referencia' => 'nullable|string',
        ]);

        Compra::create([
            'cliente_id' => $clienteId,
            'fecha' => $request->fecha,
            'monto' => $request->monto,
            'sucursal' => $request->sucursal,
            'tipo_movimiento' => $request->tipo_movimiento,
            'documento_referencia' => $request->documento_referencia,
        ]);
        // 🎯 Actualizar puntos del cliente
        $cliente = Cliente::find($clienteId);
        $puntosGanados = floor($request->monto / 100); // 1 punto por cada Bs. 100
        $cliente->puntos += $puntosGanados;
        $cliente->save();

        return redirect("/clientes/{$clienteId}/compras")->with('success', 'Compra registrada y puntos actualizados.');
    }
}
