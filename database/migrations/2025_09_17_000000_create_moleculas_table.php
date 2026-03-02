<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoleculasTable extends Migration
{
    public function up()
    {
        Schema::create('moleculas', function (Blueprint $t) {
            $t->id();
            $t->string('codigo_rfast', 30)->unique();
            $t->text('descripcion');
            $t->string('marca', 80)->nullable();
            $t->string('presentacion', 80)->nullable();
            $t->boolean('activo')->default(true);
            $t->timestamps();
            $t->softDeletes(); // opcional
        });
    }
}
