<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Interaccion;
use App\Models\Compra;
use App\Models\Canje;

class InicioController extends Controller
{
    public function index()
    {
        // Contadores principales
        $totalClientes = Cliente::count();
        $totalInteracciones = Interaccion::count();
        $totalCompras = Compra::count();
        $totalCanjes = Canje::count();

        // Gráfico de compras por mes
        $comprasPorMes = Compra::selectRaw('DATE_FORMAT(fecha, "%Y-%m") as mes, SUM(monto) as total')
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        // Enviar datos a la vista
        return view('inicio', [
            'totalClientes' => $totalClientes,
            'totalInteracciones' => $totalInteracciones,
            'totalCompras' => $totalCompras,
            'totalCanjes' => $totalCanjes,
            'labels' => $comprasPorMes->keys(),
            'data' => $comprasPorMes->values()
        ]);
    }
}
