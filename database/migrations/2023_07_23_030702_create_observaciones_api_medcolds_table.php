<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObservacionesApiMedcoldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observaciones_api_medcold', function (Blueprint $table) {
            $table->bigIncrements('id_obs');
            $table->longText('observacion')->nullable();
            $table->string('usuario')->nullable();
            $table->string('estado')->nullable();
            $table->unsignedBigInteger('pendiente_id')->nullable();
            $table->foreign('pendiente_id', 'fk_pendienteapi_medcold_obs')->references('id')->on('pendiente_api_medcold')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('entregado_id')->nullable();
            $table->foreign('entregado_id', 'fk_entregadoapi_medcold_obs')->references('id')->on('entregados_api_medcold')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('observaciones_api_medcold');
    }
}
