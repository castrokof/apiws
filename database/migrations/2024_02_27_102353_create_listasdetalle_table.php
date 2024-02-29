<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListasdetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listasdetalle', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug',10);
            $table->string('nombre');
            $table->string('descripcion',200);
            $table->string('activo',2);
            $table->unsignedBigInteger('listas_id');
            $table->foreign('listas_id', 'fk_listas_listasdetalle')->references('id')->on('listas')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id', 'fk_usuario_listasdetalle')->references('id')->on('users')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('listasdetalle');
    }
}
