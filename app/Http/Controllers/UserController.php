<?php

namespace App\Http\Controllers;

use App\Dao\UserDao;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    protected $_userDao;

    public function __construct(UserDao $userDao) {
        $this->_userDao = $userDao;
    }

    public function login(Request $request) {
        return $this->_userDao->login($request->all());
    }

    public function logout() {
        return $this->_userDao->logout();
    }

    public function getUserLogin() {
        $user = $this->_userDao->getUserLogin();
        $user['empresa'] = $user->empresa;
        return $user;
    }

    public function saveUser(Request $request) {
        $res = $request->all();
        $data = [
            'name' => $res['name'],
            'email' => $res['email'],
            'password' => bcrypt($res['password']),
        ];
        $user = $this->_userDao->create($data);
        return response()->json($user, 201);
    }
}
