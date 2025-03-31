<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class validarCrearEmpresa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:8|confirmed',
            'nombre_empresa' => 'required',
        ], [
            'required' => 'El :attribute es requerido.',
            'in' => 'El :attribute debe ser un valor válido.',
            'unique' => 'El :attribute debe ser único.',
            'email' => 'El :attribute debe ser una dirección de correo electrónico válida.',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        return $next($request);
    }
}
