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
        });

        // 📊 Agrupar compras por mes para gráfico
        $comprasPorMes = $compras->groupBy(function ($compra) {
            return \Carbon\Carbon::parse($compra->fecha)->format('Y-m');
        })->map(function ($grupo) {
            return $grupo->sum('monto');
        });

        $labels = $comprasPorMes->keys();
        $data = $comprasPorMes->values();

        // 🧮 Cálculos adicionales
        $totalCompras = $compras->count();
        $montoTotal = $compras->sum('monto');
        $ultimaCompra = $compras->first();
        $frecuenciaPromedio = $compras->count() > 0 ? round($compras->count() / $compras->unique('cliente_id')->count(), 2) : 0;
        $sucursalMasFrecuente = $compras->groupBy('sucursal')->sortByDesc(function ($group) {
            return count($group);
        })->keys()->first();
        $totalPuntos = floor($montoTotal / 100);

        // ✅ Enviar a la vista
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

    // 📝 Muestra el formulario para generar un reporte personalizado
    public function crear()
    {
        return view('reportes.crear', [
            'resultados' => null,
            'metricasSeleccionadas' => [],
            'filtros' => [],
        ]);
    }

    // 📊 Genera un reporte personalizado según las métricas seleccionadas
    public function generar(Request $request)
    {
        $request->validate([
            'desde' => 'nullable|date',
            'hasta' => 'nullable|date',
            'metricas' => 'required|array',
        ]);

        $metricas = $request->input('metricas', []);

        $query = Compra::query();

        if ($request->filled('desde')) {
            $query->where('fecha', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->where('fecha', '<=', $request->hasta);
        }

        $compras = $query->get();

        $resultados = [];

        if (in_array('total_compras', $metricas)) {
            $resultados['total_compras'] = $compras->count();
        }

        if (in_array('monto_total', $metricas)) {
            $resultados['monto_total'] = $compras->sum('monto');
        }

        if (in_array('total_puntos', $metricas)) {
            $resultados['total_puntos'] = floor($compras->sum('monto') / 100);
        }

        return view('reportes.crear', [
            'resultados' => $resultados,
            'metricasSeleccionadas' => $metricas,
            'filtros' => $request->only('desde', 'hasta'),
        ]);
    }
}
