<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    //
    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'ciudad',
    ];

    public function ordens()
    {
        return $this->hasMany(Orden::class);
    }
}
