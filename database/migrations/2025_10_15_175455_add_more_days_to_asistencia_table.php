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
            // Agregar los días faltantes para completar hasta el 31
            $table->boolean('dia_17')->nullable()->after('dia_16');
            $table->boolean('dia_18')->nullable()->after('dia_17');
            $table->boolean('dia_19')->nullable()->after('dia_18');
            $table->boolean('dia_20')->nullable()->after('dia_19');
            $table->boolean('dia_21')->nullable()->after('dia_20');
            $table->boolean('dia_22')->nullable()->after('dia_21');
            $table->boolean('dia_23')->nullable()->after('dia_22');
            $table->boolean('dia_24')->nullable()->after('dia_23');
            $table->boolean('dia_25')->nullable()->after('dia_24');
            $table->boolean('dia_26')->nullable()->after('dia_25');
            $table->boolean('dia_27')->nullable()->after('dia_26');
            $table->boolean('dia_28')->nullable()->after('dia_27');
            $table->boolean('dia_29')->nullable()->after('dia_28');
            $table->boolean('dia_30')->nullable()->after('dia_29');
            $table->boolean('dia_31')->nullable()->after('dia_30');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asistencia', function (Blueprint $table) {
            $table->dropColumn([
                'dia_17', 'dia_18', 'dia_19', 'dia_20', 'dia_21', 'dia_22', 'dia_23', 'dia_24',
                'dia_25', 'dia_26', 'dia_27', 'dia_28', 'dia_29', 'dia_30', 'dia_31'
            ]);
        });
    }
};
