<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValorParcialFacturasCreateToOrdenesCompraMedcol6Table extends Migration
{
    public function up()
    {
        Schema::table('ordenes_compra_medcol6', function (Blueprint $table) {
            $table->decimal('totalParcial', 15, 2)->nullable();
            $table->text('facturas')->nullable(); // Campo Facturas, permite valores NULL

        });
    }

    public function down()
    {
        Schema::table('ordenes_compra_medcol6', function (Blueprint $table) {
            $table->dropColumn(['totalParcial','facturas']);
        });
    }
}
