<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocEntregaFacturaEntregaToPendienteApiMedcol6Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pendiente_api_medcol6', function (Blueprint $table) {
            if (!Schema::hasColumn('pendiente_api_medcol6', 'doc_entrega')) {
                $table->string('doc_entrega')->nullable();
            }
            if (!Schema::hasColumn('pendiente_api_medcol6', 'factura_entrega')) {
                $table->string('factura_entrega')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pendiente_api_medcol6', function (Blueprint $table) {
            $table->dropColumn(['doc_entrega', 'factura_entrega']);
        });
    }
}
