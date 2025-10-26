<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('asistencia', function (Blueprint $table) {
            // Cambiar todos los campos de día de boolean a string
            for ($i = 1; $i <= 31; $i++) {
                $table->string("dia_$i", 20)->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asistencia', function (Blueprint $table) {
            // Revertir cambios
            for ($i = 1; $i <= 31; $i++) {
                $table->boolean("dia_$i")->nullable()->change();
            }
        });
    }
};
