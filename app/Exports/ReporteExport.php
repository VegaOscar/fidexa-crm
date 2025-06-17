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

    // ✅ Cálculos adicionales (si deseas mostrarlos en la vista)
    $totalCompras = $compras->count();
    $montoTotal = $compras->sum('monto');
    $ultimaCompra = $compras->first();
    $frecuenciaPromedio = $compras->groupBy('cliente_id')->count();
    $sucursalMasFrecuente = $compras->groupBy('sucursal')->sortDesc()->keys()->first();
    $totalPuntos = floor($montoTotal / 100);

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
