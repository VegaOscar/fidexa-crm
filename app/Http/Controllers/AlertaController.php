<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Carbon\Carbon;

class AlertaController extends Controller
{
    /**
     * 📌 Muestra los clientes inactivos (sin compras en los últimos 30 días).
     */
    public function clientesInactivos()
    {
        // Fecha límite para considerar inactividad (30 días atrás desde hoy)
        $fechaLimite = Carbon::now()->subDays(30);

        // Filtrar clientes cuya última compra fue hace más de 30 días
        $clientes = Cliente::with('compras')->get()->filter(function ($cliente) use ($fechaLimite) {
            $ultima = $cliente->compras->sortByDesc('fecha')->first();
            return $ultima && Carbon::parse($ultima->fecha)->lt($fechaLimite);
        })->map(function ($cliente) {
            $ultimaCompra = $cliente->compras->sortByDesc('fecha')->first();
            return (object)[
                'nombre' => $cliente->nombre,
                'email' => $cliente->email,
                'ultima_compra' => $ultimaCompra->fecha->format('Y-m-d'),
                'dias_inactivo' => Carbon::parse($ultimaCompra->fecha)->diffInDays(Carbon::now()),
            ];
        });

        // Enviar la colección a la vista
        return view('alertas.inactivos', ['clientes' => $clientes]);
    }
}
