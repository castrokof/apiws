<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenCompraMedcol2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_compra_medcol2', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('documentoOrden',20);
            $table->string('numeroOrden',100);
            $table->string('codigo',50);
            $table->string('nombre',300);
            $table->string('cums',30);
            $table->string('marca',30);
            $table->string('cantidad',50);
            $table->decimal('precio', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('iva', 10, 2);
            $table->unsignedBigInteger('proveedor_id');
            $table->foreign('proveedor_id', 'fk_proveedor2')->references('id')->on('terceros_api_medcol2')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id', 'fk_usuario2')->references('id')->on('users')->onDelete('restrict')->onUpdate('restrict');
            $table->string('contrato',50);
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
        Schema::dropIfExists('orden_compra_medcol2');
    }
}
