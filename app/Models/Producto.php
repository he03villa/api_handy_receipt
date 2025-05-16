<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    protected $appends = ['imagen_url'];

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

    public function getImagenUrlAttribute()
    {
        $value = $this->attributes['imagen'];
        $url = "images/producto{$value}";
        $storage = Storage::url($url);
        return asset($storage);
    }
}
