<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    //
    protected $fillable = [
        'empresa_id',
        'vendedor_id',
        'cliente_id',
        'status',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallleOrden::class);
    }

    public function tipo_orden()
    {
        return $this->belongsTo(TipoOrden::class);
    }
}
