<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission  El permiso requerido (ver, crear, editar, eliminar)
     * @param  string  $module  El módulo sobre el que se requiere permiso
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission, $module)
    {
        $user = Auth::user();
        
        if (!$user) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }
        
        // Verificar si el usuario tiene el permiso necesario en el módulo especificado
        if (!$user->hasPermission($permission, $module)) {
            if ($request->ajax()) {
                return response()->json(['error' => 'No autorizado.'], 403);
            }
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        
        return $next($request);
    }
}