<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('sessions');
    }
    
    public function down()
    {
        // Si necesitas revertir esta migración, puedes recrear la tabla
    }
    
};
