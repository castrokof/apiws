<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResolucion1604FieldsToPendienteApiMedcol6 extends Migration
{
    public function up()
    {
        Schema::table('pendiente_api_medcol6', function (Blueprint $table) {
            $table->string('numero_formula', 100)->nullable()->after('secuencia_pendiente');
            $table->date('fecha_ordenamiento')->nullable()->after('numero_formula');
            $table->string('frecuencia_administracion', 150)->nullable()->after('fecha_ordenamiento');
            $table->string('duracion_tratamiento', 100)->nullable()->after('frecuencia_administracion');
        });
    }

    public function down()
    {
        Schema::table('pendiente_api_medcol6', function (Blueprint $table) {
            $table->dropColumn([
                'numero_formula',
                'fecha_ordenamiento',
                'frecuencia_administracion',
                'duracion_tratamiento',
            ]);
        });
    }
}
