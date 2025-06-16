<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Compra;
use App\Models\Interaccion;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class InicioController extends Controller
{
    public function index()
    {

        
        // Total de clientes
        $totalClientes = Cliente::count();

        // Total de compras
        $totalCompras = Compra::count();

        // Monto total gastado
        $montoTotal = Compra::sum('monto');

        // Promedio de gasto por compra
        $promedioGasto = round(Compra::avg('monto'), 2);

        // Clientes activos: compraron en los últimos 30 días
        $clientesActivos = Cliente::whereHas('compras', function ($query) {
            $query->where('fecha', '>=', Carbon::now()->subDays(30));
        })->count();

        // Clientes inactivos
        $clientesInactivos = $totalClientes - $clientesActivos;

        // Frecuencia promedio
        $frecuencias = [];
        $clientes = Cliente::with('compras')->get();
        foreach ($clientes as $cliente) {
            $compras = $cliente->compras->sortBy('fecha');
            for ($i = 1; $i < $compras->count(); $i++) {
                $fechaAnterior = Carbon::parse($compras[$i - 1]->fecha);
                $fechaActual = Carbon::parse($compras[$i]->fecha);
                $frecuencias[] = $fechaActual->diffInDays($fechaAnterior);
            }
        }
        $frecuenciaPromedio = count($frecuencias) > 0 ? round(array_sum($frecuencias) / count($frecuencias)) : null;

        // Total de interacciones
        $totalInteracciones = Interaccion::count();

        // Clientes más rentables (estructura compatible con blade)
            $clientesRentables = Compra::selectRaw('cliente_id, SUM(monto) as monto')
                ->groupBy('cliente_id')
                ->orderByDesc('monto')
                ->take(5)
                ->get()
                ->map(function ($item) {
                    $cliente = Cliente::find($item->cliente_id);
                    return (object) [
                        'nombre' => $cliente ? $cliente->nombre : 'Desconocido',
                        'monto' => floatval($item->monto),
                    ];
                });


        // Interacciones por tipo (asegura estructura de Collection, no pluck directo)
        $interaccionesPorTipo = Interaccion::selectRaw('tipo, COUNT(*) as total')
            ->groupBy('tipo')
            ->get()
            ->pluck('total', 'tipo');

        // Evolución mensual de compras
        $comprasPorMes = Compra::selectRaw("DATE_FORMAT(fecha, '%Y-%m') as mes, SUM(monto) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        $labelsMeses = $comprasPorMes->keys();
        $valoresMeses = $comprasPorMes->values()->map(function ($valor) {
            return floatval($valor);
        });


        // Historial de comportamiento
        $ultimasCompras = Compra::orderByDesc('fecha')->take(5)->get()->map(function ($compra) {
            return (object) [
                'fecha' => Carbon::parse($compra->fecha)->format('d/m/Y'),
                'tipo' => 'Compra'
            ];
        });

        $ultimasInteracciones = Interaccion::orderByDesc('fecha')->take(5)->get()->map(function ($int) {
            return (object) [
                'fecha' => Carbon::parse($int->fecha)->format('d/m/Y'),
                'tipo' => ucfirst($int->tipo)
            ];
        });

        $historial = $ultimasCompras->merge($ultimasInteracciones)->sortByDesc('fecha')->take(10);



        // Luego asegúrate de pasarlas así a la vista:
        return view('inicio', compact(
            'totalClientes',
            'totalCompras',
            'montoTotal',
            'promedioGasto',
            'clientesActivos',
            'clientesInactivos',
            'frecuenciaPromedio',
            'totalInteracciones',
            'clientesRentables',
            'historial',
            'labelsMeses',
            'valoresMeses',
            'interaccionesPorTipo'
        ));
    }
    
}
