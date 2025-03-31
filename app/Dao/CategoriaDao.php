<?php

namespace App\Dao;

use App\Models\Categoria;

class CategoriaDao
{
    public function all() {
        return Categoria::all();
    }

    public function find($id) {
        return Categoria::find($id);
    }

    public function findOrFail($id) {
        return Categoria::findOrFail($id);
    }

    public function save(array $data) {
        return Categoria::create($data);
    }

    public function update($id, array $data) {
        return Categoria::where('id', $id)->update($data);
    }

    public function delete($id) {
        return Categoria::where('id', $id)->delete();
    }

    public function allByEmpresa($id, $filter = null) {
        $buscar = "";
        if ($filter) {
            $buscar = $filter['buscar'] ?? "";
        }
        $categoria = Categoria::query();
        if ($buscar) {
            $categoria = $categoria->where('nombre', 'like', "%$buscar%");
        } 
        $categoria = $categoria->where('empresa_id', $id)->orderBy('created_at', 'desc')->paginate($filter['page'], $columns = ['*'], $pageName = 'page');
        return ['data' => $categoria->items(), 'total_pages' => $categoria->lastPage(), 'page' => $categoria->currentPage()];
    }

    public function allByCategoriaActivos($id) {
        $categoria = Categoria::query();
        $categoria = $categoria->where('empresa_id', $id)->orderBy('nombre', 'asc')->get();
        return $categoria;
    }
}