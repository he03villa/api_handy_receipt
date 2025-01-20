<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //
    protected $fillable = [
        'nombre',
        'descripcion',
        'empresa_id',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
