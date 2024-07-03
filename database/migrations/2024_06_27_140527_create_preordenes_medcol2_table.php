<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreordenesMedcol2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preordenes_medcol2', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('documentoPreOrden',20);
            $table->string('numeroPreOrden',100);
            $table->string('codigo',100);
            $table->string('cantidad',50);
            $table->string('codigo_tercero',20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preordenes_medcol2');
    }
}
