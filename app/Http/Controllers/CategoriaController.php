<?php

namespace App\Http\Controllers;

use App\Dao\CategoriaDao;
use App\Dao\UserDao;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    //
    protected $_categoriaDao;
    protected $_userDao;

    public function __construct(CategoriaDao $categoriaDao, UserDao $userDao)
    {
        $this->_categoriaDao = $categoriaDao;
        $this->_userDao = $userDao;
    }

    public function index(Request $request) {
        $limit = 10;
        $buscar = $request->get('search');
        if ($request->get('limit')) {
            $limit = $request->get('limit');
        }
        $dataFiltro = [
            'page' => $limit,
            'buscar' => $buscar,
        ];
        $id_empresa = $this->_userDao->getUserLogin()->empresa->id;
        return $this->_categoriaDao->allByEmpresa($id_empresa, $dataFiltro);
    }

    public function show($id) {
        $categoria = $this->_categoriaDao->find($id);
        return response()->json($categoria, 200);
    }

    public function store(Request $request) {
        $data = $request->all();
        $data['empresa_id'] = $this->_userDao->getUserLogin()->empresa->id;
        $categoria = $this->_categoriaDao->save($data);
        return response()->json($categoria, 201);
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        $categoria = $this->_categoriaDao->update($id, $data);
        return response()->json($categoria, 200);
    }

    public function destroy($id) {
        return $this->_categoriaDao->delete($id);
    }

    public function allCategoriaActivos() {
        $id_empresa = $this->_userDao->getUserLogin()->empresa->id;
        $categoria = $this->_categoriaDao->allByCategoriaActivos($id_empresa);
        return response()->json($categoria, 200);
    }
}
