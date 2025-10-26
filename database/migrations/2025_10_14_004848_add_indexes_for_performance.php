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
        // Índices para tabla candidatos
        Schema::table('candidatos', function (Blueprint $table) {
            $table->index(['Eliminado_en'], 'idx_candidatos_eliminado');
            $table->index(['IdPuestoCandidatoFK'], 'idx_candidatos_puesto');
            $table->index(['Fecha_Postulacion'], 'idx_candidatos_fecha');
            $table->index(['Nombre', 'Apellido_Paterno'], 'idx_candidatos_nombre');
        });

        // Índices para tabla empleados
        Schema::table('empleados', function (Blueprint $table) {
            $table->index(['Eliminado_en'], 'idx_empleados_eliminado');
            $table->index(['IdPuestoEmpleadoFK'], 'idx_empleados_puesto');
            $table->index(['Fecha_Ingreso'], 'idx_empleados_ingreso');
            $table->index(['Nombre', 'Apellido_Paterno'], 'idx_empleados_nombre');
            $table->index(['NSS'], 'idx_empleados_nss');
            $table->index(['RFC'], 'idx_empleados_rfc');
        });

        // Índices para tabla puestos
        Schema::table('puestos', function (Blueprint $table) {
            $table->index(['Eliminado_en'], 'idx_puestos_eliminado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            $table->dropIndex('idx_candidatos_eliminado');
            $table->dropIndex('idx_candidatos_puesto');
            $table->dropIndex('idx_candidatos_fecha');
            $table->dropIndex('idx_candidatos_nombre');
        });

        Schema::table('empleados', function (Blueprint $table) {
            $table->dropIndex('idx_empleados_eliminado');
            $table->dropIndex('idx_empleados_puesto');
            $table->dropIndex('idx_empleados_ingreso');
            $table->dropIndex('idx_empleados_nombre');
            $table->dropIndex('idx_empleados_nss');
            $table->dropIndex('idx_empleados_rfc');
        });

        Schema::table('puestos', function (Blueprint $table) {
            $table->dropIndex('idx_puestos_eliminado');
        });
    }
};
