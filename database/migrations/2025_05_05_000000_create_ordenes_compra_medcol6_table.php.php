<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenesCompraMedcol6Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes_compra_medcol6', function (Blueprint $table) {
            $table->id(); // Id
            $table->string('orden_de_compra')->nullable(); // ORDEN DE COMPRA
            $table->string('nit')->nullable(); // NIT
            $table->string('proveedor')->nullable(); // PROVEEDOR
            $table->string('telefono')->nullable(); // TELEFONO
            $table->date('fecha')->nullable(); 
            $table->string('cod_farmacia')->nullable();// FECHA
            $table->string('num_orden_compra')->nullable(); // NumORDENCOMPRA
            $table->string('programa')->nullable(); // PROGRAMA
            $table->string('contacto')->nullable(); // CONTACTO
            $table->string('direccion')->nullable(); // DIRECCION
            $table->string('email')->nullable(); // EMAIL
            $table->string('codigo_proveedor')->nullable(); // CODIGOPROVEEDOR
            $table->string('user_create')->nullable();// FECHA
            $table->string('estado')->nullable(); // ESTADO
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordenes_compra_medcol6');
    }
}
