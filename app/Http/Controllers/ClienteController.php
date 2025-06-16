<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClientesImport;

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

            public function destroy($id)
        {
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();

            // redirige con flash para notificar
            return redirect()->route('clientes.index')
                            ->with('success', 'Cliente eliminado correctamente.');
        }

        public function importar(Request $request)
        {
            $request->validate([
                'archivo_excel' => 'required|mimes:xlsx,xls,csv'
            ]);

            Excel::import(new ClientesImport, $request->file('archivo_excel'));

            return redirect('/clientes')->with('success', 'Clientes importados exitosamente.');
        }

        public function update(Request $request, $id)
        {
            $cliente = Cliente::findOrFail($id);

            $request->validate([
                'nombre' => 'required|string|max:255',
                'cedula' => 'required|string|max:20',
                'genero' => 'required|in:M,F',
                'email' => 'required|email|max:255',
                'telefono' => 'required|string|max:20',
                'direccion' => 'required|string|max:255',
            ]);

            $cliente->update($request->all());

            return redirect('/clientes')->with('success', 'Cliente actualizado exitosamente.');
        }


    // Lista todos los clientes
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    // Panel de KPIs por cliente
    public function kpis()
    {
        $clientes = Cliente::with(['compras', 'interacciones'])->get();

        $clientesData = $clientes->map(function ($cliente) {
            $totalGastado = $cliente->compras->sum('monto');
            $ultimaCompra = optional($cliente->compras->sortByDesc('fecha')->first())->fecha;
            $nivel = match (true) {
                $cliente->puntos >= 1000 => 'Oro',
                $cliente->puntos >= 500 => 'Plata',
                default => 'Bronce',
            };

            // Datos falsos para ejemplo de gráfico
            $labels = collect(range(5, 0))->map(fn($i) => now()->subMonths($i)->format('M'));
            $data = $labels->map(fn() => rand(0, 100));

            $interaccionesPorTipo = $cliente->interacciones
                ->groupBy('tipo')
                ->map(fn($g) => $g->count());

            return [
                'cliente' => $cliente,
                'totalGastado' => $totalGastado,
                'nivel' => $nivel,
                'ultimaCompra' => $ultimaCompra,
                'labels' => $labels->values(),
                'data' => $data->values(),
                'interaccionesLabels' => $interaccionesPorTipo->keys()->values(),
                'interaccionesData' => $interaccionesPorTipo->values(),
            ];
        });

        return view('clientes.kpis', compact('clientesData'));
    }
            
}
