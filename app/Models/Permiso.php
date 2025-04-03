<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Relación muchos a muchos con roles (a través de permiso_rol_modulo).
     */
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'permiso_rol_modulo')
            ->withPivot('modulo_id')
            ->withTimestamps();
    }
}
