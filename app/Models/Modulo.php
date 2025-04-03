<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modulo extends Model
{
    use SoftDeletes;

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'modulos';

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'ruta',
        'icono',
        'orden',
        'activo',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer',
    ];

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'permiso_rol_modulo')
            ->withPivot('rol_id')
            ->withTimestamps();
    }

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'permiso_rol_modulo')
            ->withPivot('permiso_id')
            ->withTimestamps();
    }


}
