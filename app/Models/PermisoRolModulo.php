<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermisoRolModulo extends Model
{
    protected $table = 'permiso_rol_modulo';
    
    // Deshabilitamos el auto incremento
    public $incrementing = false;
    
    // Como no tenemos una clave primaria simple, Laravel no la gestionará automáticamente.
    protected $primaryKey = null;
    
    protected $fillable = [
        'rol_id',
        'modulo_id',
        'permiso_id'
    ];
}
