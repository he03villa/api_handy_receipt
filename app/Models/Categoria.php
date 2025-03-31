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

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['numero_productos'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function getNumeroProductosAttribute()
    {
        return $this->productos()->count();
    }
}
