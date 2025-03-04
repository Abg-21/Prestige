<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        App::bind('role', function () {
            return new Role();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->app->bind('role', function ($app) {
            return new Role();
        });
    }
}
