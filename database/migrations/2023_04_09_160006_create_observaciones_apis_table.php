<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObservacionesApisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observacionesapi', function (Blueprint $table) {
            $table->bigIncrements('id_obs');
            $table->longText('observacion')->nullable();
            $table->string('usuario')->nullable();
            $table->string('estado')->nullable();
            $table->unsignedBigInteger('pendiente_id');
            $table->foreign('pendiente_id', 'fk_pendienteapi_obs')->references('id')->on('pendientesapi')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('entregado_id');
            $table->foreign('entregado_id', 'fk_entregadoapi_obs')->references('id')->on('entregadosapi')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('observacionesapi');
    }
}
