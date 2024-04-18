<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispensadoMedcol4Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispensado_medcol4', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('idusuario');
            $table->string('tipo');
            $table->string('facturad')->nullable();
            $table->string('factura');
            $table->string('tipodocument');
            $table->string('historia');
            $table->string('autorizacion')->nullable();
            $table->string('cums');
            $table->string('expediente');
            $table->string('consecutivo');
            $table->string('cums_rips');
            $table->string('codigo');
            $table->string('tipo_medicamento');
            $table->string('nombre_generico');
            $table->string('atc');
            $table->string('forma');
            $table->string('concentracion');
            $table->string('unidad_medicamento');
            $table->string('numero_unidades');
            $table->string('regimen');
            $table->string('paciente');
            $table->string('primer_apellido');
            $table->string('segundo_apellido');
            $table->string('primer_nombre');
            $table->string('segundo_nombre');
            $table->string('cuota_moderadora');
            $table->string('copago');
            $table->string('numero_entrega');
            $table->dateTime('fecha_ordenamiento')->nullable();
            $table->dateTime('fecha_suministro');
            $table->string('dx');
            $table->string('ips')->nullable();
            $table->string('id_medico');
            $table->string('medico');
            $table->string('mipres')->nullable();
            $table->string('precio_unitario');
            $table->string('valor_total');
            $table->string('reporte_entrega_nopbs')->nullable();
            $table->string('estado')->nullable();
            $table->string('centroprod');
            $table->string('drogueria');
            $table->string('cajero');
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
        Schema::dropIfExists('dispensado_medcol4');
    }
}
