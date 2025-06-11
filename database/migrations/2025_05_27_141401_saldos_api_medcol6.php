<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SaldosApiMedcol6 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saldos_medcol6', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Campos básicos de identificación
            $table->string('ips');
            $table->string('deposito');
            $table->string('agrupador')->nullable();
            $table->string('codigo');
            $table->string('cums')->nullable();
            $table->string('nombre');

            // Campos de producto
            $table->string('marca')->nullable();
            $table->string('costo_unitario')->nullable();
            $table->string('saldo')->nullable();
            $table->string('total')->nullable();

            // Campos de fechas
            $table->date('fecha_vencimiento')->nullable();
            $table->string('invima')->nullable();
            $table->date('fecha_saldo');

            // Campos de categorización
            $table->string('grupo', 20);
            $table->string('subgrupo', 20);
            $table->string('linea', 50)->nullable();

            // Campos descriptivos
            $table->string('nombre_ips')->nullable();
            $table->string('nombre_deposito')->nullable();
            $table->string('nombre_grupo')->nullable();
            $table->string('nombre_subgrupo')->nullable();

            // Timestamps y claves
            $table->timestamps();

            // Índices para optimización
            /* $table->index(['ips', 'deposito']);
            $table->index('codigo');
            $table->index(['grupo', 'subgrupo']);
            $table->index('fecha_vencimiento');
            $table->index('fecha_saldo'); */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saldos_medcol6');
    }
}
