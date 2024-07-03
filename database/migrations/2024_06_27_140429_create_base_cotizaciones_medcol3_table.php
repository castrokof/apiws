<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseCotizacionesMedcol3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_cotizaciones_medcol3', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo');
            $table->string('nombre');
            $table->string('cums');
            $table->string('cums_corto');
            $table->string('marca');
            $table->decimal('precio', 10, 2);
            $table->string('codigo_tercero',20);
            $table->string('nombre_sucursal',100);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('estado');
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
        Schema::dropIfExists('base_cotizaciones_medcol3');
    }
}
