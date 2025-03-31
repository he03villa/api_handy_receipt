<?php

namespace App\Http\Controllers;

use App\Dao\ProductoDao;
use App\Dao\UserDao;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    //
    protected $_productoDao;
    protected $_userDao;

    public function __construct(ProductoDao $productoDao, UserDao $userDao)
    {
        $this->_productoDao = $productoDao;
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
        $producto = $this->_productoDao->allByEmpresa($id_empresa, $dataFiltro);
        return response()->json($producto, 200);
    }

    public function show($id) {
        $producto = $this->_productoDao->findOrFail($id);
        return response()->json($producto, 200);
    }

    public function store(Request $request) {
        $req = $request->all();
        $data = [
            'nombre' => $req['nombre'],
            'descripcion' => $req['descripcion'],
            'precio' => $req['precio'],
            'categoria_id' => $req['categoria_id'],
        ];
        $data['empresa_id'] = $this->_userDao->getUserLogin()->empresa->id;
        $producto = $this->_productoDao->save($data);
        if ($request->imagen != null) {
            $imag1Base64 = $req['imagen']['base64'];

            $folderPath = public_path("storage/images/producto/".$producto->id);
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true); // Crear la carpeta si no existe
            }
            $imag1Base64 = explode(',', $imag1Base64)[1];
            $image = base64_decode($imag1Base64);
            $imageName = $req['imagen']['name'];
            $imagePath = $folderPath . '/' . $imageName;
            file_put_contents($imagePath, $image);
            $imageUrl1 = "/{$producto->id}/" . $imageName;
            $this->_productoDao->update($producto->id, ['imagen' => $imageUrl1]);
        }
        return response()->json($producto, 201);
    }

    public function update(Request $request, $id) {
        $req = $request->all();
        $data = [
            'nombre' => $req['nombre'],
            'descripcion' => $req['descripcion'],
            'precio' => $req['precio'],
            'categoria_id' => $req['categoria_id'],
        ];
        if ($request->imagen != null) {
            $imag1Base64 = $req['imagen']['base64'];
            $pos = strpos($imag1Base64, 'base64');
            if ($pos !== false) {
                $folderPath = public_path("storage/images/producto/".$id);
                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0755, true); // Crear la carpeta si no existe
                }
                $imag1Base64 = explode(',', $imag1Base64)[1];
                $image = base64_decode($imag1Base64);
                $imageName = $req['imagen']['name'];
                $imagePath = $folderPath . '/' . $imageName;
                file_put_contents($imagePath, $image);
                $imageUrl1 = "/{$id}/" . $imageName;
                $this->_productoDao->update($id, ['imagen' => $imageUrl1]);
            }
        }
        $producto = $this->_productoDao->update($id, $data);
        return response()->json($producto, 200);
    }

    public function destroy($id) {
        $producto = $this->_productoDao->delete($id);
        return response()->json($producto, 200);
    }

    public function countActivoInactivo() {
        $id_empresa = $this->_userDao->getUserLogin()->empresa->id;
        $producto = $this->_productoDao->countActivoInactivo($id_empresa);
        return response()->json($producto, 200);
    }

    public function updateStatus(Request $request, $id) {
        $req = $request->all();
        $data = [
            'status' => $req['status'],
        ];
        $producto = $this->_productoDao->update($id, $data);
        return response()->json($producto, 200);
    }

    public function getProductosActivos() {
        $id_empresa = $this->_userDao->getUserLogin()->empresa->id;
        $producto = $this->_productoDao->getProductosActivos($id_empresa);
        return response()->json($producto, 200);
    }
}
