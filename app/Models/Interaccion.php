<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaccion extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'tipo',
        'descripcion',
        'fecha',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
