<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use App\Models\Usuario;
use App\Models\Modulo;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Registrar gate para verificar permisos
        Gate::define('ver', function (Usuario $usuario, $modulo) {
            return $usuario->hasPermission('ver', $modulo);
        });
        
        Gate::define('crear', function (Usuario $usuario, $modulo) {
            return $usuario->hasPermission('crear', $modulo);
        });
        
        Gate::define('editar', function (Usuario $usuario, $modulo) {
            return $usuario->hasPermission('editar', $modulo);
        });
        
        Gate::define('eliminar', function (Usuario $usuario, $modulo) {
            return $usuario->hasPermission('eliminar', $modulo);
        });
        
        // Directivas Blade para permisos
        Blade::if('can', function ($permission, $module) {
            return auth()->check() && auth()->user()->hasPermission($permission, $module);
        });
    }
}