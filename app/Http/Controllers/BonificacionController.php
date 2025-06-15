<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Carbon\Carbon;

class BonificacionController extends Controller
{
    /**
     * 📌 Otorga bonificaciones a clientes que compran con frecuencia igual o menor a 7 días.
     */
    public function otorgarBonificaciones()
    {
        $clientes = Cliente::with('compras')->get();
        $bonificados = [];

        foreach ($clientes as $cliente) {
            $compras = $cliente->compras->sortByDesc('fecha');

            if ($compras->count() < 2) {
                continue;
            }

            $frecuencias = [];
            for ($i = 1; $i < $compras->count(); $i++) {
                $actual = Carbon::parse($compras[$i - 1]->fecha);
                $anterior = Carbon::parse($compras[$i]->fecha);
                $frecuencias[] = $anterior->diffInDays($actual);
            }

            $promedio = count($frecuencias) ? round(array_sum($frecuencias) / count($frecuencias)) : null;

            if ($promedio !== null && $promedio <= 7) {
                // ✅ Otorga 10 puntos si cumple la frecuencia
                $cliente->puntos += 10;
                $cliente->save();
                $bonificados[] = $cliente->nombre;
            }
        }

        return view('bonificaciones.resultado', compact('bonificados'));
    }
}
