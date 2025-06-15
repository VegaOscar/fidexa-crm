<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canje extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'puntos_canjeados', 'fecha'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
