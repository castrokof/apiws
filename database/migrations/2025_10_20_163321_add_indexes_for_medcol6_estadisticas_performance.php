<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddIndexesForMedcol6EstadisticasPerformance extends Migration
{
    /**
     * Run the migrations.
     *
     * Agregar índices para optimizar el rendimiento de getEstadisticasMedcol6
     * Estos índices mejoran significativamente las consultas con JOINs,
     * GROUP BY y WHERE en la función de estadísticas.
     *
     * @return void
     */
    public function up()
    {
        // Índices para pendiente_api_medcol6
        $this->createIndexIfNotExists('pendiente_api_medcol6', 'idx_pend_fecha', 'fecha');
        $this->createIndexIfNotExists('pendiente_api_medcol6', 'idx_pend_estado', 'estado');
        $this->createIndexIfNotExists('pendiente_api_medcol6', 'idx_pend_fecha_estado', 'fecha, estado');
        $this->createIndexIfNotExists('pendiente_api_medcol6', 'idx_pend_codigo', 'codigo');

        // Índices para saldos_medcol6
        $this->createIndexIfNotExists('saldos_medcol6', 'idx_saldos_codigo', 'codigo');
        $this->createIndexIfNotExists('saldos_medcol6', 'idx_saldos_updated', 'updated_at');
        $this->createIndexIfNotExists('saldos_medcol6', 'idx_saldos_codigo_updated', 'codigo, updated_at');

        // Índice para dispensado_medcol6 (si no existe del migration anterior)
        $this->createIndexIfNotExists('dispensado_medcol6', 'idx_disp_codigo_alt', 'codigo');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP INDEX IF EXISTS idx_pend_fecha ON pendiente_api_medcol6');
        DB::statement('DROP INDEX IF EXISTS idx_pend_estado ON pendiente_api_medcol6');
        DB::statement('DROP INDEX IF EXISTS idx_pend_fecha_estado ON pendiente_api_medcol6');
        DB::statement('DROP INDEX IF EXISTS idx_pend_codigo ON pendiente_api_medcol6');

        DB::statement('DROP INDEX IF EXISTS idx_saldos_codigo ON saldos_medcol6');
        DB::statement('DROP INDEX IF EXISTS idx_saldos_updated ON saldos_medcol6');
        DB::statement('DROP INDEX IF EXISTS idx_saldos_codigo_updated ON saldos_medcol6');

        DB::statement('DROP INDEX IF EXISTS idx_disp_codigo_alt ON dispensado_medcol6');
    }

    /**
     * Helper method to check if an index exists and create it
     *
     * @param string $table
     * @param string $indexName
     * @param string $columns
     * @return void
     */
    private function createIndexIfNotExists($table, $indexName, $columns)
    {
        $exists = DB::select("
            SELECT COUNT(*) as count
            FROM INFORMATION_SCHEMA.STATISTICS
            WHERE table_schema = DATABASE()
            AND table_name = ?
            AND index_name = ?
        ", [$table, $indexName]);

        if ($exists[0]->count == 0) {
            DB::statement("CREATE INDEX {$indexName} ON {$table} ({$columns})");
        }
    }
}
