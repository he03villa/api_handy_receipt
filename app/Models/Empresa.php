<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    //
    protected $fillable = [
        'user_id',
        'name',
        'logo',
        'description',
    ];

    public function ordens()
    {
        return $this->hasMany(Orden::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function vendedors(){
        return $this->hasMany(Vendedor::class);
    }

    public function categorias(){
        return $this->hasMany(Categoria::class);
    }

}
