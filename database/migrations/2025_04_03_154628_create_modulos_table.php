<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',50);
            $table->string('descripcion', 50)->nullable();
            $table->string('ruta', 100)->unique();
            $table->string('icono', 50)->nullable();
            $table->unsignedInteger('orden')->default(0); // 🔹 SUGERENCIA: evita valores negativos si es para ordenamiento
            $table->boolean('activo')->default(true)->index(); // 🔹 SUGERENCIA: index para mejorar filtrado por activos/inactivos
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modulos');
    }
};
