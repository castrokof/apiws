<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdate1CreateToOrdenesCompraMedcol6Table extends Migration
{
    public function up()
    {
        Schema::table('ordenes_compra_medcol6', function (Blueprint $table) {
            $table->decimal('total', 15, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->decimal('sub_total', 15, 2)->nullable();
            $table->decimal('iva', 5, 2)->nullable();

        });
    }

    public function down()
    {
        Schema::table('ordenes_compra_medcol6', function (Blueprint $table) {
            $table->dropColumn(['total', 'user_create', 'sub_total', 'iva', 'observaciones']);
        });
    }
}
