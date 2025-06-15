<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable = [
        'cliente_id', 'fecha', 'monto', 'sucursal', 'tipo_movimiento', 'documento_referencia'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
