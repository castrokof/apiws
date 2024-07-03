<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvimaApiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invima_api', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tipo_MI',20);
            $table->string('codigo',20);
            $table->string('nombre',200);
            $table->string('marca',20);
            $table->string('atc',20);
            $table->string('cums',20);
            $table->string('forma');
            $table->string('presentacion');
            $table->string('unidad');
            $table->string('invima');
            $table->string('estado_invima');
            $table->string('concentracion');
            $table->string('unidad_concentracion');
            $table->string('unidad_medida');
            $table->string('laboratorio');
            $table->string('estado',1);
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
        Schema::dropIfExists('invima_api');
    }
}
