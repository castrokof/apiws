<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendienteApiMedcoldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendiente_api_medcold', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Tipodocum')->nullable();;
            $table->string('cantdpx');
            $table->string('cantord');
            $table->dateTime('fecha_factura');
            $table->dateTime('fecha');
            $table->string('historia');
            $table->string('apellido1');
            $table->string('apellido2')->nullable();
            $table->string('nombre1');
            $table->string('nombre2')->nullable();
            $table->string('cantedad');
            $table->string('direcres')->nullable();
            $table->string('telefres')->nullable();
            $table->string('documento');
            $table->string('factura');
            $table->string('orden_externa')->nullable();
            $table->string('codigo');
            $table->string('nombre');
            $table->string('cums');
            $table->string('cantidad');
            $table->string('cajero');
            $table->string('usuario')->nullable();
            $table->string('estado')->nullable();
            $table->dateTime('fecha_impresion')->nullable();
            $table->dateTime('fecha_entrega')->nullable();
            $table->dateTime('fecha_anulado')->nullable();
            $table->string('doc_entrega')->nullable();
            $table->string('factura_entrega')->nullable();
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
        Schema::dropIfExists('pendiente_api_medcold');
    }
}
