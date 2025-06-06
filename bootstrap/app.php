<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //$middleware->prepend(\App\Http\Middleware\XAuthorizationHeader::class);
        $middleware->alias([
            'validarCrearEmpresa' => App\Http\Middleware\validarCrearEmpresa::class,
            'validarCrearCategoria' => App\Http\Middleware\validarCrearCategoriaMiddleware::class,
            'validarCrarProducto' => App\Http\Middleware\validarCrarProductoMiddleware::class,
            'validarUpdateEmpresa' => App\Http\Middleware\validarUpdateEmpresaMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 401);
            }
        });
    })->create();
