<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tipdocum', 10)->nullable();
            $table->string('historia', 50)->nullable();
            $table->string('paciente', 200)->nullable();
            $table->string('direccion', 300)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('regimen', 50)->nullable();
            $table->string('nivel', 20)->nullable();
            $table->string('edad', 10)->nullable();
            $table->string('sexo', 10)->nullable();
            $table->enum('pqrs', ['SI', 'NO'])->default('NO');
            $table->enum('estado', ['VIVO', 'FALLECIDO'])->default('VIVO');
            $table->string('programa', 50)->nullable();
            $table->enum('alto_costo', ['SI', 'NO'])->default('NO');
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
        Schema::dropIfExists('pacientes');
    }
}
