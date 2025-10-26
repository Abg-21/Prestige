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
        Schema::create('asistencia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->year('año');
            $table->tinyInteger('mes')->unsigned(); // 1-12
            $table->enum('periodo', ['primera_quincena', 'segunda_quincena']); // 1-15 o 16-30/31
            
            // Campos para cada día (1-15 o 16-30/31) - null = no marcado, 1 = presente, 0 = ausente
            $table->boolean('dia_1')->nullable();
            $table->boolean('dia_2')->nullable();
            $table->boolean('dia_3')->nullable();
            $table->boolean('dia_4')->nullable();
            $table->boolean('dia_5')->nullable();
            $table->boolean('dia_6')->nullable();
            $table->boolean('dia_7')->nullable();
            $table->boolean('dia_8')->nullable();
            $table->boolean('dia_9')->nullable();
            $table->boolean('dia_10')->nullable();
            $table->boolean('dia_11')->nullable();
            $table->boolean('dia_12')->nullable();
            $table->boolean('dia_13')->nullable();
            $table->boolean('dia_14')->nullable();
            $table->boolean('dia_15')->nullable();
            $table->boolean('dia_16')->nullable();
            
            // Campos adicionales
            $table->double('bono', 8, 2)->default(0);
            $table->double('prestamo', 8, 2)->default(0);
            $table->double('fonacot', 8, 2)->default(0);
            $table->double('estatus_finiquito', 8, 2)->default(0);
            $table->string('observaciones', 180)->nullable();
            
            $table->timestamps();
            
            // Relaciones
            $table->foreign('empleado_id')->references('IdEmpleados')->on('empleados')->onDelete('cascade');
            
            // Índice único para evitar duplicados por empleado, año, mes y periodo
            $table->unique(['empleado_id', 'año', 'mes', 'periodo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencia');
    }
};
