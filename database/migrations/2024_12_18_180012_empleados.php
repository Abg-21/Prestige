<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
    Schema::create('empleados', function (Blueprint $table) {
            $table->bigIncrements('IdEmpleados');
            $table->string('Nombre', 25);
            $table->string('Apellido_Paterno', 20);
            $table->string('Apellido_Materno', 20);
            $table->unsignedTinyInteger('Edad');
            $table->string('Telefono', 15);
            $table->enum('Estado', [
                'Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 'Chihuahua', 'Ciudad de México', 'Coahuila', 'Colima', 'Durango', 'Estado de México', 'Guanajuato', 'Guerrero', 'Hidalgo', 'Jalisco', 'Michoacán', 'Morelos', 'Nayarit', 'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 'Yucatán', 'Zacatecas'
            ]);
            $table->string('Ruta', 30)->nullable();
            $table->enum('Escolaridad', [
                'Primaria', 'Secundaria terminada', 'Bachillerato trunco', 'Bachillerato terminado', 'Técnico superior', 'Licenciatura trunca', 'Licenciatura terminada', 'Postgrado'
            ]);
            $table->string('Correo', 30);
            $table->string('Experiencia', 10);
            $table->date('Fecha_Ingreso');
            $table->date('Fecha_Egreso')->nullable();
            $table->string('Curp', 18)->nullable();
            $table->string('NSS', 11)->nullable();
            $table->string('RFC', 13)->nullable();
            $table->string('Codigo_Postal', 5)->nullable();
            $table->string('Folio', 10)->nullable();
            $table->string('Codigo', 10)->nullable();
            $table->string('No_Cuenta', 10)->nullable();
            $table->string('Tipo_Cuenta', 15)->nullable();
            $table->decimal('Sueldo', 9, 2)->nullable();
            $table->unsignedBigInteger('IdPuestoEmpleadoFK')->nullable();
            $table->timestamp('Eliminado_en')->nullable();
            $table->timestamps();

            $table->foreign('IdPuestoEmpleadoFK')
                  ->references('idPuestos')
                  ->on('puestos')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        // Simplemente elimina la tabla, no necesitas eliminar columnas primero
        Schema::dropIfExists('empleados');
    }
};
