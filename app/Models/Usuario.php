<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'empleado_id',
        'nombre_usuario',
        'password',
        'activo',
    ];

    /**
     * Los atributos que deben ocultarse en los arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos.
     *
     * @var array
     */
    protected $casts = [
        'ultimo_acceso' => 'datetime',
        'activo' => 'boolean',
    ];
    
    /**
     * Obtener el empleado asociado al usuario.
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
    
    /**
     * Obtener los roles asociados al usuario.
     */
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'usuario_roles');
    }
    
    /**
     * Verificar si el usuario tiene un rol específico.
     *
     * @param string $rolNombre
     * @return bool
     */
    public function hasRole($rolNombre)
    {
        return $this->roles()->where('nombre', $rolNombre)->exists();
    }
    
    /**
     * Verificar si el usuario tiene un permiso específico sobre un módulo.
     *
     * @param string $permiso (ver, crear, editar, eliminar)
     * @param string $modulo
     * @return bool
     */
    public function hasPermission($permiso, $modulo)
    {
        // Si es administrador, tiene todos los permisos
        if ($this->hasRole('administrador')) {
            return true;
        }
        
        // Si es gerencia, tiene permiso de ver todo
        if ($this->hasRole('gerencia') && $permiso === 'ver') {
            return true;
        }
        
        // Para otros roles, verificar en la tabla rol_modulo_permisos
        $rolesIds = $this->roles()->pluck('roles.id')->toArray();
        $moduloId = Modulo::where('nombre', $modulo)->value('id');
        $permisoId = Permiso::where('nombre', $permiso)->value('id');
        
        if (!$moduloId || !$permisoId) {
            return false;
        }
        
        return \DB::table('rol_modulo_permisos')
            ->whereIn('rol_id', $rolesIds)
            ->where('modulo_id', $moduloId)
            ->where('permiso_id', $permisoId)
            ->exists();
    }
}