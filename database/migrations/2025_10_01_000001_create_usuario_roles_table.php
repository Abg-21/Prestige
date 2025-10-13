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
        Schema::create('usuario_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('rol_id')->constrained('roles')->onDelete('cascade');
            $table->timestamps(); // Fecha de asignación del rol
            
            // Evitar que un usuario tenga el mismo rol múltiples veces
            $table->unique(['usuario_id', 'rol_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_roles');
    }
};