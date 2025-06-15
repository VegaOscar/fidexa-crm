<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Interaccion;

class ReporteController extends Controller
{
    // 📌 Muestra el dashboard general de reportes
    public function index()
    {
        $totalClientes = Cliente::count();
        $totalInteracciones = Interaccion::count();

        $interaccionesPorTipo = Interaccion::selectRaw('tipo, COUNT(*) as total')
            ->groupBy('tipo')
            ->pluck('total', 'tipo');

        return view('reportes.index', compact('totalClientes', 'totalInteracciones', 'interaccionesPorTipo'));
    }
    

    // 📌 Muestra el reporte individual de un cliente
    public function cliente(Request $request, $id)
{
    $cliente = Cliente::findOrFail($id);
    $comprasOriginales = $cliente->compras()->orderBy('fecha', 'desc')->get();

    // 🔍 Aplicar filtros por fecha y sucursal
    $compras = $comprasOriginales->filter(function ($compra) use ($request) {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');
        $sucursal = $request->input('sucursal');

        $fechaValida = true;
        $sucursalValida = true;

        if ($desde) {
            $fechaValida = $fechaValida && $compra->fecha >= $desde;
        }

        if ($hasta) {
            $fechaValida = $fechaValida && $compra->fecha <= $hasta;
        }

        if ($sucursal) {
            $sucursalValida = $compra->sucursal === $sucursal;
        }

        return $fechaValida && $sucursalValida;
    })->values();

    // 📊 Cálculos con las compras filtradas
    $totalCompras = $compras->count();
    $montoTotal = $compras->sum('monto');
    $ultimaCompra = $compras->first()?->fecha;

    // 🎯 Cálculo de puntos por monto
    $puntosBase = floor($montoTotal / 100);

    // 🎁 Bonificación por actividad en el mes actual
    $comprasMesActual = $compras->filter(function ($compra) {
        return \Carbon\Carbon::parse($compra->fecha)->format('Y-m') === now()->format('Y-m');
    })->count();

    $puntosExtra = $comprasMesActual >= 3 ? 5 : 0;

    $totalPuntos = $puntosBase + $puntosExtra;


    // 📅 Calcular frecuencia promedio de compra
    $frecuencias = [];
    for ($i = 1; $i < $compras->count(); $i++) {
        $fechaActual = \Carbon\Carbon::parse($compras[$i - 1]->fecha);
        $fechaAnterior = \Carbon\Carbon::parse($compras[$i]->fecha);
        $frecuencias[] = $fechaAnterior->diffInDays($fechaActual);
    }
    $frecuenciaPromedio = $frecuencias ? round(array_sum($frecuencias) / count($frecuencias)) : null;

    // 🏬 Sucursal más frecuente
    $sucursalMasFrecuente = $compras->groupBy('sucursal')->sortByDesc(function ($grupo) {
        return count($grupo);
    })->keys()->first();

    // 📈 Datos para gráfico de compras por mes
    $comprasPorMes = $compras->groupBy(function ($compra) {
        return \Carbon\Carbon::parse($compra->fecha)->format('Y-m');
    })->map(function ($grupo) {
        return $grupo->sum('monto');
    });

    $labels = $comprasPorMes->keys();
    $data = $comprasPorMes->values();



    // ✅ Enviar también todas las compras originales para el filtro de sucursal
    return view('reportes.cliente', compact(
        'cliente',
        'comprasOriginales',
        'totalCompras',
        'montoTotal',
        'ultimaCompra',
        'frecuenciaPromedio',
        'sucursalMasFrecuente',
        'labels',
        'data',
        'totalPuntos'
    ));

}

}
