<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClientesImport;

class ClienteImportController extends Controller
{
    public function showForm()
    {
        return view('clientes.importar');
    }

    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new ClientesImport, $request->file('archivo'));

        return redirect('/clientes')->with('success', 'Clientes importados correctamente.');
    }
}
