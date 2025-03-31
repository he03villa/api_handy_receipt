<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //
    protected $fillable = [
        'empresa_id',
        'categoria_id',
        'nombre',
        'descripcion',
        'imagen',
        'precio',
        'status',
    ];

    /* protected $appends = ['status']; */

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallleOrden::class);
    }

    /* public function getStatusAttribute()
    {
        return $this->attributes['status'] == 1;
    } */
}
