<?php

namespace App\Imports;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Cliente([
            'nombre'    => $row['nombre'],
            'cedula'    => $row['cedula'],
            'genero'    => $row['genero'],
            'email'     => $row['email'],
            'telefono'  => $row['telefono'],
            'direccion' => $row['direccion'],
        ]);
    }
}
