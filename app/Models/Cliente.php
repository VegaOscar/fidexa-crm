<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'cedula',
        'genero',
        'email',
        'telefono',
        'direccion',
        'sucursal_id',
    ];

    public function interacciones()
{
    return $this->hasMany(Interaccion::class);
}

    public function compras()
    {
        return $this->hasMany(Compra::class);
    }

    public function canjes()
{
    return $this->hasMany(Canje::class);
}

}

