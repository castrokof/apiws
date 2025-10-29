<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateMissingIndexesMedcol6 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Crear índices directamente con SQL para mayor control y rapidez

        // Índices para dispensado_medcol6
        $this->createIndexIfNotExists('dispensado_medcol6', 'idx_disp_codigo', 'codigo');
        $this->createIndexIfNotExists('dispensado_medcol6', 'idx_disp_precio', 'precio_unitario');

        // Índice compuesto para mejorar los JOINs con pendientes
        $this->createIndexIfNotExists('dispensado_medcol6', 'idx_disp_codigo_precio', 'codigo, precio_unitario');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP INDEX IF EXISTS idx_disp_codigo ON dispensado_medcol6');
        DB::statement('DROP INDEX IF EXISTS idx_disp_precio ON dispensado_medcol6');
        DB::statement('DROP INDEX IF EXISTS idx_disp_codigo_precio ON dispensado_medcol6');
    }

    /**
     * Crear índice solo si no existe
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
