<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormulaCompletaToDispensadoMedcol6Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dispensado_medcol6', function (Blueprint $table) {
            $table->string('formula_completa')->nullable()->after('numero_orden');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dispensado_medcol6', function (Blueprint $table) {
            $table->dropColumn('formula_completa');
        });
    }
}
