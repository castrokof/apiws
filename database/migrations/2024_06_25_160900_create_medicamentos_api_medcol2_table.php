<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicamentosApiMedcol2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicamentos_api_medcol2', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->string('tipo_MI',30);
            $table->string('codigo',20);
            $table->string('nombre',200);
            $table->string('marca',30);
            $table->string('atc',20);
            $table->string('forma',100);
            $table->string('concentracion',150);
            $table->string('cums',30);
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
        Schema::dropIfExists('medicamentos_api_medcol2');
    }
}
