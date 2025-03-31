<?php

namespace App\Dao;

use App\Models\Producto;

class ProductoDao
{
    public function save(array $data) {
        return Producto::create($data);
    }

    public function update($id, array $data) {
        Producto::where('id', $id)->update($data);
        $producto = $this->find($id);
        return $producto;
    }

    public function delete($id) {
        return Producto::where('id', $id)->delete();
    }

    public function all() {
        return Producto::all();
    }

    public function find($id) {
        return Producto::with('categoria')->find($id);
    }

    public function findOrFail($id) {
        return Producto::findOrFail($id);
    }

    public function allByEmpresa($id, $filter = null) {
        $buscar = "";
        if ($filter) {
            $buscar = $filter['buscar'] ?? "";
        }
        $producto = Producto::query();
        $productoActivo = Producto::query();
        $productoInactivo = Producto::query();
        if ($buscar) {
            $producto = $producto->where('nombre', 'like', "%$buscar%");
        }
        $activo = $productoActivo->where('empresa_id', $id)->where('status', 1)->count();
        $inactivo = $productoInactivo->where('empresa_id', $id)->where('status', 0)->count();
        $producto = $producto->with('categoria')->where('empresa_id', $id)->orderBy('created_at', 'desc')->paginate($filter['page'], $columns = ['*'], $pageName = 'page');
        return ['data' => $producto->items(), 'total_pages' => $producto->lastPage(), 'page' => $producto->currentPage(), 'total_activo' => $activo, 'total_inactivo' => $inactivo];
    }

    public function countActivoInactivo($id) {
        $activo = Producto::where('empresa_id', $id)->where('status', 1)->count();
        $inactivo = Producto::where('empresa_id', $id)->where('status', 0)->count();
        return ['total_activo' => $activo, 'total_inactivo' => $inactivo];
    }

    public function getProductosActivos($id) {
        return Producto::where('empresa_id', $id)->where('status', 1)->get();
    }

    public function getAllByProductoEmpresa($id) {
        return Producto::where('empresa_id', $id)->get();
    }

}