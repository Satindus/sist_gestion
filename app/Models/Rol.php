<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'permiso_rol_modulo')
            ->withPivot('modulo_id')
            ->withTimestamps();
    }

    public function modulos()
    {
        return $this->belongsToMany(Modulo::class, 'permiso_rol_modulo')
            ->withPivot('permiso_id')
            ->withTimestamps();
    }

}
