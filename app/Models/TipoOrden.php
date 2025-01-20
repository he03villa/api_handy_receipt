<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoOrden extends Model
{
    //
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function ordenes()
    {
        return $this->hasMany(Orden::class);
    }
}
