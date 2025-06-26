<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalParcialCreateToOrdenesCompraMedcol3Table extends Migration
{
    public function up()
    {
        Schema::table('orden_compra_medcol3', function (Blueprint $table) {
            $table->decimal('totalParcial', 15, 2)->nullable();

        });
    }

    public function down()
    {
        Schema::table('orden_compra_medcol3', function (Blueprint $table) {
            $table->dropColumn(['totalParcial']);
        });
    }
}
