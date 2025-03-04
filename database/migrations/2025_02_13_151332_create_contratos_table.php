<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id(); // Campo id (auto incremental)
            $table->unsignedBigInteger('empleado_id');
            $table->text('contenido')->nullable(); // Aquí se almacenará el contenido del contrato
            $table->timestamps();

            // Llave foránea que referencia al empleado
            $table->foreign('empleado_id')->references('idEmpleado')->on('empleados')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contratos');
    }
};
