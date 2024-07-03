<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTercerosApiMedcol4Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terceros_api_medcol4', function (Blueprint $table) {
             $table->bigIncrements('id');
            $table->string('codigo_tercero',20);
            $table->string('nombre_sucursal',100);
            $table->string('direccion',50);
            $table->string('telefono',30);
            $table->string('e_mail',50);
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
        Schema::dropIfExists('terceros_api_medcol4');
    }
}
