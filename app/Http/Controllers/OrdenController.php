<?php

namespace App\Http\Controllers;

use App\Dao\OrdenDao;
use App\Dao\UserDao;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdenController extends Controller
{
    //
    protected $_ordenDao;
    protected $_userDao;

    public function __construct(OrdenDao $ordenDao, UserDao $userDao)
    {
        $this->_ordenDao = $ordenDao;
        $this->_userDao = $userDao;
    }

    public function index(Request $request)
    {
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
        $orden = $this->_ordenDao->allByEmpresa($id_empresa, $dataFiltro);
        return response()->json($orden, 200);
    }

    public function store(Request $request)
    {
        $id_empresa = $this->_userDao->getUserLogin()->empresa->id;
        $data = [
            'empresa_id' => $id_empresa,
            'detalles' => $request->detalles,
            'total' => $request->total,
            'numero_factura' => $this->_ordenDao->getNumeroOrden(),
        ];
        $orden = $this->_ordenDao->create($data);
        return response()->json($orden, 201);
    }

    public function update(Request $request, $id)
    {
        $id_empresa = $this->_userDao->getUserLogin()->empresa->id;
        $data = [
            'detalles' => $request->detalles,
            'total' => $request->total,
        ];
        $orden = $this->_ordenDao->update($id, $data);
        return response()->json($orden, 200);
    }

    public function destroy($id)
    {
        return $this->_ordenDao->delete($id);
    }

    public function show($id)
    {
        $orden = $this->_ordenDao->find($id);
        return response()->json($orden, 200);
    }

    public function updateStatus(Request $request, $id) {
        $req = $request->all();
        $data = [
            'status' => $req['status'],
        ];
        $orden = $this->_ordenDao->update($id, $data);
        return response()->json($orden, 200);
    }

    public function getDashboard(Request $request) {
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
        $orden = $this->_ordenDao->allByDashboard($id_empresa, $dataFiltro);
        return response()->json($orden, 200);
    }
}
