<?php

namespace App\Providers;

use App\Dao\UserDao;
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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
