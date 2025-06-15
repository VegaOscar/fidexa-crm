<?php

// app/Http/Controllers/CanjeController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Canje;
use App\Models\Cliente;
use Carbon\Carbon;

class CanjeController extends Controller
{
    public function create($clienteId)
    {
        $cliente = Cliente::findOrFail($clienteId);
        return view('canjes.create', compact('cliente'));
    }

    public function store(Request $request, $clienteId)
    {
        $request->validate([
            'puntos_canjeados' => 'required|integer|min:1',
        ]);

        $cliente = Cliente::findOrFail($clienteId);

        if ($cliente->puntos < $request->puntos_canjeados) {
            return back()->with('error', 'El cliente no tiene suficientes puntos.');
        }

        // Registrar el canje
        Canje::create([
            'cliente_id' => $cliente->id,
            'puntos_canjeados' => $request->puntos_canjeados,
            'fecha' => Carbon::now(),
        ]);

        // Descontar puntos
        $cliente->puntos -= $request->puntos_canjeados;
        $cliente->save();

        return redirect('/clientes')->with('success', 'Canje realizado correctamente.');
    }
}

