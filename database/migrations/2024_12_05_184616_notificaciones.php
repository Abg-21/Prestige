<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('tipo'); // "Nuevo candidato", "Nuevo empleado"
            $table->boolean('leido')->default(false); // Para saber si la notificación fue vista
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notificaciones');
    }
};
