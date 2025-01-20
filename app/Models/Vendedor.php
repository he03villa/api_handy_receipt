<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    //
    protected $fillable = [
        'user_id',
        'nombre',
        'identificacion',
        'telefono',
        'direccion',
        'empresa_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function ordens()
    {
        return $this->hasMany(Orden::class);
    }
}
