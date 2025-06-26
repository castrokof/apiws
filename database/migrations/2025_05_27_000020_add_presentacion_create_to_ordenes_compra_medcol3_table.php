<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPresentacionCreateToOrdenesCompraMedcol3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_compra_medcol3', function (Blueprint $table) {
            $table->text('presentacion')->nullable(); // Campo observaciones, permite valores NULL
            
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
            $table->dropColumn(['observaciones']);
        });
    }
}