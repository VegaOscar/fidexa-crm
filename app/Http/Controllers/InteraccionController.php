<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Interaccion;
use App\Models\Cliente;

class InteraccionController extends Controller
{
    public function index()
    {
        $interacciones = Interaccion::with('cliente')->orderBy('fecha', 'desc')->paginate(10);
        return view('interacciones.index', compact('interacciones'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        return view('interacciones.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo' => 'required',
            'descripcion' => 'required|string',
            'fecha' => 'required|date',
        ]);

        Interaccion::create($request->all());

        return redirect('/interacciones')->with('success', 'Interacción registrada correctamente.');
    }
}
