<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class validarCrarProductoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'descripcion' => 'nullable',
            'imagen' => 'nullable',
            'categoria_id' => 'required',
            'precio' => 'required|numeric',
        ], [
            'required' => 'El :attribute es requerido.',
            'numeric' => 'El :attribute debe ser numerico.',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        return $next($request);
    }
}
