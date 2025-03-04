<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('candidatos', function (Blueprint $table) {
            $table->id('idCandidatos');
            $table->string('Nombre', 50);
            $table->string('Apellido_P', 30);
            $table->string('Apellido_M', 30);
            $table->string('Telefono_M', 15)->nullable();
            $table->string('Telefono_F', 15)->nullable();
            $table->string('Ciudad', 30)->nullable();
            $table->string('Estado', 30)->nullable();
            $table->enum('Escolaridad', [
                'Primaria', 'Secundaria terminada', 'Bachillerato trunco',
                'Bachillerato terminado', 'Técnico superior', 'Licenciatura trunca',
                'Licenciatura terminada', 'Postgrado'
            ]);
            $table->string('Correo', 30)->nullable();
            $table->tinyInteger('Experiencia');
            $table->string('Comentarios', 50)->nullable();
            $table->text('P_Aux', 100)->nullable();
            $table->text('S_Aux', 100)->nullable();
            $table->text('T_Aux', 100)->nullable();
            $table->unsignedBigInteger('id_PuestoCandidatoFK')->nullable();
            $table->timestamps();
        
            $table->foreign('id_PuestoCandidatoFK')->references('idPuestos')->on('puestos')->onDelete('no action')->onUpdate('no action');
        });        
    }

    public function down()
    {
        Schema::dropIfExists('candidatos');
    }
};
