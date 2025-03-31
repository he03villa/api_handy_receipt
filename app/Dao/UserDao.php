<?php

namespace App\Dao;

use App\Models\Empresa;
use App\Models\User;

class UserDao
{
    public function find($id)
    {
        return User::find($id);
    }

    public function findOrFail($id)
    {
        return User::findOrFail($id);
    }

    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function findByEmailOrFail($email)
    {
        return User::where('email', $email)->firstOrFail();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        return User::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return User::where('id', $id)->delete();
    }

    public function all()
    {
        return User::all();
    }

    public function getUserLogin() {
        return auth('api')->user();
    }

    public function logout() {
        auth('api')->logout();
    }

    public function login(array $data) {
        if (! $token = auth('api')->attempt($data)) {
            return response()->json(['error' => 'Unauthorized'], 400);
        }
  
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60
        ]);
    }

    public function registerempresa(array $data) {
        $user = User::create($data);
        $empresa = new Empresa($data['empresa']);
        $user->empresa()->save($empresa);
        return $user;
    }
}
