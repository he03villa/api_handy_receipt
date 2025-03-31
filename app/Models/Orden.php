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
        'tipo_ordens_id',
        'total',
        'numero_factura',
    ];

    protected $appends = [
        'numero_detalle',
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

    public function getNumeroDetalleAttribute()
    {
        return $this->detalles()->count();
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'detallle_ordens', 'orden_id', 'producto_id')->withPivot('cantidad', 'precio', 'observaciones');
    }
}
