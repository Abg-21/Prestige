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
        Schema::create('eliminados', function (Blueprint $table) {
            $table->id('idEliminados');
            $table->string('eliminable_type');  // ✅ Para la relación polimórfica
            $table->unsignedBigInteger('eliminable_id');  // ✅ Para la relación polimórfica
            $table->string('tipo');
            $table->string('motivo', 250)->nullable();
            $table->timestamp('eliminado_en')->nullable();
            $table->timestamp('restaurado_en')->nullable();
            $table->string('restaurado_por')->nullable();
            $table->foreignId('idUsuarioEliminadoFK')
                  ->nullable()  // ✅ Permitir nulos
                  ->constrained('usuarios', 'id')
                  ->onDelete('no action')
                  ->onUpdate('no action');
            $table->timestamps();
            
            // Índice para consultas polimórficas
            $table->index(['eliminable_type', 'eliminable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eliminados');
    }
};
