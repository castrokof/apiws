<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFaltantesEstadoCreateToOrdenesCompraMedcol3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_compra_medcol3', function (Blueprint $table) {
            $table->string('estado')->nullable(); // Campo estado, permite valores NULL
            $table->integer('cantidadEntregada')->unsigned()->nullable(); // Campo cantidadEntregada, entero no negativo, permite NULL
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orden_compra_medcol3', function (Blueprint $table) {
            $table->dropColumn(['estado', 'cantidadEntregada']);
        });
    }
}