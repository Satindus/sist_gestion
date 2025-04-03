<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id(); // id INT(11) AUTO_INCREMENT PRIMARY KEY
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('email', 100)->unique(); // normalmente se asegura que el email sea único
            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->date('fecha_contratacion')->nullable();
            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
}
