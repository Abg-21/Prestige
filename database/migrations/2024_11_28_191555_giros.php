<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('giros', function (Blueprint $table) {
            $table->id('idGiros');
            $table->string('Nombre', 30);
            $table->string('Descripcion', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('giros');
    }
};

