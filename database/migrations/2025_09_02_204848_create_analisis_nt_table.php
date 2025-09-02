<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalisisNtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analisis_nt', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_cliente')->nullable();
            $table->string('codigo_medcol');
            $table->string('agrupador')->nullable();
            $table->string('nombre');
            $table->string('cums');
            $table->string('expediente')->nullable();
            $table->decimal('valor_unitario', 15, 2)->nullable();
            $table->string('frecuencia_uso')->nullable();
            $table->string('contrato');
            $table->timestamps();
            
            // Índice único compuesto para evitar duplicados
            $table->unique(['codigo_medcol', 'cums', 'contrato'], 'unique_codigo_cums_contrato');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analisis_nt');
    }
}
