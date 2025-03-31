<?php

namespace App\Http\Controllers;

use App\Dao\EmpresaDao;
use App\Dao\UserDao;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    //

    protected $_userDao;
    protected $_empresaDao;

    public function __construct(UserDao $userDao, EmpresaDao $empresaDao)
    {
        $this->_userDao = $userDao;
        $this->_empresaDao = $empresaDao;
    }

    public function registerempresa(Request $request) {
        $res = $request->all();
        $data = [
            'name' => $request->name ?? explode('@', $res['email'])[0],
            'email' => $res['email'],
            'password' => bcrypt($res['password']),
            'empresa' => [
                'name' => $res['nombre_empresa'],
                'description' => $request->description,
            ],
        ];
        $empresa = $this->_userDao->registerempresa($data);
        if ($request->logo != null) {
            $imag1Base64 = $res['logo']['base64'];

            $folderPath = public_path("storage/images/empresa/".$empresa->id);
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true); // Crear la carpeta si no existe
            }
            $imag1Base64 = explode(',', $imag1Base64)[1];
            $image = base64_decode($imag1Base64);
            $imageName = $res['logo']['name'];
            $imagePath = $folderPath . '/' . $imageName;
            file_put_contents($imagePath, $image);
            $imageUrl1 = "/{$empresa->id}/" . $imageName;
            $this->_empresaDao->update($empresa->id, ['logo' => $imageUrl1]);
        }
        return response()->json($empresa, 201);
    }
}
