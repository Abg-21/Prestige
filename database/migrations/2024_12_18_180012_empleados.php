<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id('idEmpleado');
            $table->string('Nombre', 50);
            $table->string('Apellido_P', 30);
            $table->string('Apellido_M', 30);
            $table->string('Telefono_M', 15)->nullable();
            $table->string('Telefono_F', 15)->nullable();
            $table->string('Ciudad', 30)->nullable();
            $table->string('Estado', 30)->nullable();
            $table->enum('Escolaridad', [
                'Primaria', 'Secundaria terminada', 'Bachillerato trunco', 
                'Bachillerato terminado', 'Técnico superior', 
                'Licenciatura trunca', 'Licenciatura terminada', 'Postgrado'
            ]);
            $table->string('Correo', 30)->nullable();
            $table->unsignedInteger('Experiencia'); // `unsigned` ya no es necesario por separado
            $table->string('Comentarios', 50)->nullable();
            $table->unsignedBigInteger('id_PuestoEmpleadoFK'); // Llave foránea
            $table->foreign('id_PuestoEmpleadoFK')
                  ->references('idPuestos')
                  ->on('puestos')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('empleados');
    }
};
