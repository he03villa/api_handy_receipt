<?php

namespace App\Dao;

use App\Models\Empresa;

class EmpresaDao {

    public function save(array $data) {
        return Empresa::create($data);
    }

    public function update($id, array $data) {
        return Empresa::where('id', $id)->update($data);
    }

    public function delete($id) {
        return Empresa::where('id', $id)->delete();
    }

    public function all() {
        return Empresa::all();
    }

    public function find($id) {
        return Empresa::find($id);
    }

    public function findOrFail($id) {
        return Empresa::findOrFail($id);
    }

    public function saveProducto(array $data) {
        $empresa = $this->find($data['empresa_id']);
        return $empresa->productos()->create($data['producto']);
    }
}