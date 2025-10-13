<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\App;
use App\Helpers\PermissionHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Eliminar esta línea problemática o corregirla
        // App::bind('role', function () {
        //     return new Role();
        // });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Directiva Blade para verificar permisos
        Blade::directive('can', function ($expression) {
            list($modulo, $accion) = explode(',', str_replace(['(', ')', "'", '"'], '', $expression));
            return "<?php if(\\App\\Helpers\\PermissionHelper::hasPermission('$modulo', '$accion')): ?>";
        });

        Blade::directive('endcan', function () {
            return '<?php endif; ?>';
        });
    }
}
