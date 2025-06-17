<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Compra;
use App\Models\Interaccion;

class ReporteController extends Controller
{
    // 📌 Muestra el dashboard general de reportes
    public function index()
    {
        // Datos generales
        $totalClientes = Cliente::count();
        $totalCompras = Compra::count();
        $montoTotal = Compra::sum('monto');
        $promedioGasto = round(Compra::avg('monto'), 2);

        // Clientes activos (últimos 30 días)
        $clientesActivos = Cliente::whereHas('compras', function ($query) {
            $query->where('fecha', '>=', now()->subDays(30));
        })->count();
        $clientesInactivos = $totalClientes - $clientesActivos;

        // Frecuencia promedio de compra
        $frecuencias = [];
        $clientes = Cliente::with('compras')->get();
        foreach ($clientes as $cliente) {
            $compras = $cliente->compras->sortBy('fecha');
            for ($i = 1; $i < $compras->count(); $i++) {
                $frecuencias[] = \Carbon\Carbon::parse($compras[$i]->fecha)
                    ->diffInDays(\Carbon\Carbon::parse($compras[$i - 1]->fecha));
            }
        }
        $frecuenciaPromedio = count($frecuencias) ? round(array_sum($frecuencias) / count($frecuencias)) : 0;

        // Interacciones por tipo
        $interaccionesPorTipo = Interaccion::selectRaw('tipo, COUNT(*) as total')
            ->groupBy('tipo')
            ->pluck('total', 'tipo');

        // Compras por mes
        $comprasPorMes = Compra::selectRaw("DATE_FORMAT(fecha, '%Y-%m') as mes, SUM(monto) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        $labelsMeses = $comprasPorMes->keys();
        $valoresMeses = $comprasPorMes->values();

        // Top 5 clientes rentables
        $clientesRentables = Compra::selectRaw('cliente_id, SUM(monto) as monto')
            ->groupBy('cliente_id')
            ->orderByDesc('monto')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $cliente = Cliente::find($item->cliente_id);
                return (object)[
                    'nombre' => $cliente?->nombre ?? 'Desconocido',
                    'monto' => floatval($item->monto),
                ];
            });

        return view('reportes.index', compact(
            'totalClientes',
            'totalCompras',
            'montoTotal',
            'promedioGasto',
            'clientesActivos',
            'clientesInactivos',
            'frecuenciaPromedio',
            'interaccionesPorTipo',
            'labelsMeses',
            'valoresMeses',
            'clientesRentables'
        ));
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

    public function create()
{
    $clientes = Cliente::all();
    $sucursales = Compra::select('sucursal')->distinct()->pluck('sucursal');
    $tiposInteraccion = Interaccion::select('tipo')->distinct()->pluck('tipo');

    return view('reportes.create', compact('clientes', 'sucursales', 'tiposInteraccion'));
}

// 🛠️ Genera el reporte según filtros seleccionados
public function generate(Request $request)
{
    $comprasQuery = Compra::query();
    $interaccionesQuery = Interaccion::query();

    if ($request->filled('cliente_id')) {
        $comprasQuery->where('cliente_id', $request->cliente_id);
        $interaccionesQuery->where('cliente_id', $request->cliente_id);
    }

    if ($request->filled('desde')) {
        $comprasQuery->where('fecha', '>=', $request->desde);
        $interaccionesQuery->where('fecha', '>=', $request->desde);
    }

    if ($request->filled('hasta')) {
        $comprasQuery->where('fecha', '<=', $request->hasta);
        $interaccionesQuery->where('fecha', '<=', $request->hasta);
    }

    if ($request->filled('sucursal')) {
        $comprasQuery->where('sucursal', $request->sucursal);
    }

    if ($request->filled('tipo_interaccion')) {
        $interaccionesQuery->where('tipo', $request->tipo_interaccion);
    }

    $compras = $comprasQuery->get();
    $interacciones = $interaccionesQuery->get();

    $resultado = [
        'total_compras' => $compras->count(),
        'monto_total' => $compras->sum('monto'),
        'total_interacciones' => $interacciones->count(),
        'puntos' => floor($compras->sum('monto') / 100),
    ];

    $comprasPorMes = $compras->groupBy(function ($c) {
        return \Carbon\Carbon::parse($c->fecha)->format('Y-m');
    })->map->sum('monto');

    $labels = $comprasPorMes->keys();
    $monto = $comprasPorMes->values();

    $validated = $request->validate([
        'desde' => ['required', 'date'],
        'hasta' => ['required', 'date', 'after_or_equal:desde'],
        'metricas' => ['required', 'array', 'min:1'],
    ]);

    if ($request->has('exportar_excel')) {
        $rows = $compras->map(function ($compra) {
            return [
                $compra->cliente->nombre ?? '',
                $compra->fecha,
                $compra->monto,
                $compra->sucursal,
            ];
        });

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ReporteExport($rows), 'reporte.xlsx');
    }

    $clientes = Cliente::all();
    $sucursales = Compra::select('sucursal')->distinct()->pluck('sucursal');
    $tiposInteraccion = Interaccion::select('tipo')->distinct()->pluck('tipo');

    return view('reportes.create', compact(
        'clientes',
        'sucursales',
        'tiposInteraccion',
        'resultado',
        'labels',
        'monto'
    ));
}

// 📤 Exporta el reporte filtrado a Excel
public function export(Request $request)
{
    return $this->generate($request->merge(['exportar_excel' => true]));
}
}
