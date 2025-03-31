<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'saveUser']);
Route::get('/me', [UserController::class, 'getUserLogin'])->middleware('auth:api');

Route::group(['prefix' => 'empresa'], function () {
   Route::post('/register', [EmpresaController::class, 'registerempresa'])->middleware('validarCrearEmpresa');
});

Route::group(['prefix' => 'categoria', 'middleware' => 'auth:api'], function () {
   Route::get('/', [CategoriaController::class, 'index']);
   Route::get('/activos', [CategoriaController::class, 'allCategoriaActivos']);
   Route::get('/{id}', [CategoriaController::class, 'show']);
   Route::post('/', [CategoriaController::class, 'store'])->middleware('validarCrearCategoria');
   Route::put('/{id}', [CategoriaController::class, 'update'])->middleware('validarCrearCategoria');
   Route::delete('/{id}', [CategoriaController::class, 'destroy']);
});

Route::group(['prefix' => 'producto', 'middleware' => 'auth:api'], function () {
   Route::get('/', [ProductoController::class, 'index']);
   Route::get('/countActivoInactivo', [ProductoController::class, 'countActivoInactivo']);
   Route::get('/productosActivos', [ProductoController::class, 'getProductosActivos']);
   Route::get('/{id}', [ProductoController::class, 'show']);
   Route::post('/', [ProductoController::class, 'store'])->middleware('validarCrearCategoria');
   Route::put('/{id}', [ProductoController::class, 'update'])->middleware('validarCrearCategoria');
   Route::put('/{id}/status', [ProductoController::class, 'updateStatus']);
   Route::delete('/{id}', [ProductoController::class, 'destroy']);
});

Route::group(['prefix' => 'orden', 'middleware' => 'auth:api'], function () {
   Route::get('/', [OrdenController::class, 'index']);
   Route::get('/dashboard', [OrdenController::class, 'getDashboard']);
   Route::get('/{id}', [OrdenController::class, 'show']);
   Route::post('/', [OrdenController::class, 'store']);
   Route::put('/{id}', [OrdenController::class, 'update']);
   Route::delete('/{id}', [OrdenController::class, 'destroy']);
   Route::put('/{id}/status', [OrdenController::class, 'updateStatus']);
});