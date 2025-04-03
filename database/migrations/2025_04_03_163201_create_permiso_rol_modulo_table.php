<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermisoRolModuloTable extends Migration
{
    public function up(): void
    {
        Schema::create('permiso_rol_modulo', function (Blueprint $table) {
            // Claves foráneas
            $table->unsignedBigInteger('rol_id');
            $table->unsignedBigInteger('modulo_id');
            $table->unsignedBigInteger('permiso_id');

            $table->timestamps();

            // Clave primaria compuesta
            $table->primary(['rol_id', 'modulo_id', 'permiso_id']);

            // Claves foráneas
            $table->foreign('rol_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('modulo_id')->references('id')->on('modulos')->onDelete('cascade');
            $table->foreign('permiso_id')->references('id')->on('permisos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permiso_rol_modulo');
    }
}
