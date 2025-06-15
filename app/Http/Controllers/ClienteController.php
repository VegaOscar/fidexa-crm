<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    // Muestra el formulario
    public function create()
    {
        return view('clientes.create');
    }

    // Guarda el cliente
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'cedula' => 'required|unique:clientes',
            'genero' => 'required',
        ]);

        Cliente::create($request->all());


        return redirect('/clientes')->with('success', 'Cliente registrado correctamente.');
    }

    // Lista todos los clientes
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }
    
}
