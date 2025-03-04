<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id('idDocumento');
            $table->unsignedBigInteger('idEmpleadoFK'); // Llave foránea
            $table->foreign('idEmpleadoFK')
                  ->references('idEmpleado')
                  ->on('empleados')
                  ->onDelete('cascade');
            $table->enum('TipoArchivo', ['PDF']); // Solo tipo PDF
            $table->string('RutaArchivo'); // Ruta del archivo en el servidor
            $table->dateTime('FechaSubida');
            $table->dateTime('FechaEdicion')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documentos');
    }
};
