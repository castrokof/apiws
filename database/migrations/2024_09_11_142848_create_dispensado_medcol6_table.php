<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispensadoMedcol6Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispensado_medcol6', function (Blueprint $table) {
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
            $table->string('codigoSOS')->nullable();
            $table->string('codigo');
            $table->string('tipo_medicamento');
            $table->string('nombre_comercial')->nullable();
            $table->string('nombre_generico');
            $table->string('atc');
            $table->string('forma');
            $table->string('concentracion');
            $table->string('unidad_medicamento');
            $table->string('cantidad_ordenada')->nullable();
            $table->string('numero_unidades');
            $table->string('regimen');
            $table->string('paciente');
            $table->string('primer_apellido');
            $table->string('segundo_apellido');
            $table->string('primer_nombre');
            $table->string('segundo_nombre');
            $table->string('cuota_moderadora');
            $table->string('copago');
            $table->string('num_total_entregas')->nullable();
            $table->string('numero_entrega');
            $table->string('numero_orden')->nullable();
            $table->date('fecha_ordenamiento')->nullable();
            $table->date('fecha_suministro');
            $table->string('dx');
            $table->string('nitips')->nullable();
            $table->string('ips')->nullable();
            $table->string('cod_dispensario_sos')->nullable();
            $table->string('ambito')->nullable();
            $table->string('tipoidmedico')->nullable();
            $table->string('numeroIdentificacion')->nullable();
            $table->string('id_medico');
            $table->string('medico');
            $table->string('especialidadmedico')->nullable();
            $table->string('mipres')->nullable();
            $table->string('precio_unitario');
            $table->string('valor_total');
            $table->string('reporte_entrega_nopbs')->nullable();
            $table->string('estado')->nullable();
            $table->string('centroprod');
            $table->string('drogueria');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('cajero')->nullable();
            $table->string('documento_origen')->nullable();
            $table->string('factura_origen')->nullable();
            $table->string('frecuencia')->nullable();
            $table->string('dosis')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('duracion_tratamiento')->nullable();
            $table->string('cobertura')->nullable();
            $table->string('tipocontrato')->nullable();
            $table->string('tipoentrega')->nullable();
            $table->string('cod_dispen_transacc')->nullable();
            $table->string('plan')->nullable();
            $table->string('via')->nullable();
            $table->unsignedBigInteger('ID_REGISTRO')->unique();
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
        Schema::dropIfExists('dispensado_medcol6');
    }
}
