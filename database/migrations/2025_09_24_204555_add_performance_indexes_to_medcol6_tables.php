<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPerformanceIndexesToMedcol6Tables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Índices para la tabla pendiente_api_medcol6
        Schema::table('pendiente_api_medcol6', function (Blueprint $table) {
            // Índice compuesto para las consultas principales por fecha y estado
            $table->index(['fecha', 'estado'], 'idx_pendientes_fecha_estado');

            // Índice para el código (usado en JOINs)
            $table->index('codigo', 'idx_pendientes_codigo');

            // Índice para estado (usado frecuentemente en WHERE)
            $table->index('estado', 'idx_pendientes_estado');

            // Índice para fecha (usado en rangos de fechas)
            $table->index('fecha', 'idx_pendientes_fecha');
        });

        // Índices para la tabla dispensado_medcol6
        Schema::table('dispensado_medcol6', function (Blueprint $table) {
            // Índice para el código (usado en JOINs con pendientes)
            $table->index('codigo', 'idx_dispensado_codigo');

            // Índice compuesto para fecha y estado
            $table->index(['fecha_suministro', 'estado'], 'idx_dispensado_fecha_estado');

            // Índice para centroprod (usado en filtros de contrato)
            $table->index('centroprod', 'idx_dispensado_centroprod');

            // Índice para historia (usado para contar pacientes únicos)
            $table->index('historia', 'idx_dispensado_historia');

            // Índice compuesto para consultas de agregación frecuentes
            $table->index(['fecha_suministro', 'centroprod', 'estado'], 'idx_dispensado_fecha_contrato_estado');

            // Índice para nombre_generico (usado en top medicamentos)
            $table->index('nombre_generico', 'idx_dispensado_nombre_generico');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar índices de pendiente_api_medcol6
        Schema::table('pendiente_api_medcol6', function (Blueprint $table) {
            $table->dropIndex('idx_pendientes_fecha_estado');
            $table->dropIndex('idx_pendientes_codigo');
            $table->dropIndex('idx_pendientes_estado');
            $table->dropIndex('idx_pendientes_fecha');
        });

        // Eliminar índices de dispensado_medcol6
        Schema::table('dispensado_medcol6', function (Blueprint $table) {
            $table->dropIndex('idx_dispensado_codigo');
            $table->dropIndex('idx_dispensado_fecha_estado');
            $table->dropIndex('idx_dispensado_centroprod');
            $table->dropIndex('idx_dispensado_historia');
            $table->dropIndex('idx_dispensado_fecha_contrato_estado');
            $table->dropIndex('idx_dispensado_nombre_generico');
        });
    }
}
