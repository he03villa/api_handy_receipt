<?php

namespace App\Providers;

use App\Dao\CategoriaDao;
use App\Dao\EmpresaDao;
use App\Dao\OrdenDao;
use App\Dao\ProductoDao;
use App\Dao\UserDao;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class DaoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(UserDao::class, function () {
            return new UserDao();
        });
        $this->app->singleton(EmpresaDao::class, function () {
            return new EmpresaDao();
        });
        $this->app->singleton(CategoriaDao::class, function () {
            return new CategoriaDao();
        });
        $this->app->singletonIf(ProductoDao::class, function () {
            return new ProductoDao();
        });
        $this->app->singleton(OrdenDao::class, function () {
            return new OrdenDao();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
