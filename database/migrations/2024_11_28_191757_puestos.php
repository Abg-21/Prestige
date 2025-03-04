<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('puestos', function (Blueprint $table) {
            $table->id('idPuestos');
            $table->enum('Categoría', ['Promovendedor', 'Promotor', 'Supervisor', 'Otro']);
            $table->string('Puesto', 45);
            $table->string('Zona', 30)->nullable();
            $table->enum('Estado', [
                'Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 
                'Chihuahua', 'Ciudad de México', 'Coahuila', 'Colima', 'Durango', 'Guanajuato', 
                'Guerrero', 'Hidalgo', 'Jalisco', 'México', 'Michoacán', 'Morelos', 'Nayarit', 
                'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 
                'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 'Yucatán', 'Zacatecas'
            ])->nullable();
            $table->string('Edad', 55)->nullable();
            $table->enum('Escolaridad', [
                'Primaria', 'Secundaria terminada', 'Bachillerato trunco',
                'Bachillerato terminado', 'Técnico superior', 'Licenciatura trunca',
                'Licenciatura terminada', 'Postgrado'
            ]);
            $table->string('Experiencia', 40);
            $table->string('Conocimientos', 400);
            $table->string('Funciones', 400);
            $table->string('Habilidades', 400);
            $table->json('P_Aux')->nullable();
            $table->json('S_Aux')->nullable();
            $table->json('T_Aux')->nullable();
            $table->unsignedBigInteger('id_GiroPuestoFK');
            $table->unsignedBigInteger('id_ClientePuestoFK');
            $table->timestamps();
        
            $table->foreign('id_GiroPuestoFK')->references('idGiros')->on('giros')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('id_ClientePuestoFK')->references('idClientes')->on('clientes')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('puestos');
    }
};
