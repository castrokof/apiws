<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoleculaProveedorCodigosTable extends Migration
{
    public function up()
    {
        Schema::create('molecula_proveedor_codigos', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('molecule_id');
            $t->foreign('molecule_id', 'fk_mpc_med_api_medcol3')->references('id')->on('medicamentos_api_medcol3')->cascadeOnDelete();
            $t->string('nombre_proveedor')->nullable();
            $t->string('codigo_proveedor', 60);
            $t->boolean('activo')->default(true);
            $t->timestamps();
            $t->softDeletes(); // opcional
            $t->index('codigo_proveedor');
        });
    }

    public function down()
    {
        Schema::dropIfExists('molecula_proveedor_codigos');
    }
}
