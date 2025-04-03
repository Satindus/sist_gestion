<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToEmpleadosTable extends Migration
{
    public function up(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable() // Puedes quitar esto si todos los empleados tendrán sí o sí un usuario
                ->constrained()
                ->onDelete('cascade'); // Si se elimina un usuario, se elimina el empleado asociado
        });
    }

    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
