<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBufferProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('buffer_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();

            // ── Parámetros de tiempo ──────────────────────────────────────
            $table->unsignedSmallInteger('lead_time')
                  ->default(7)
                  ->comment('Lead Time real de reposición en días (LT)');

            $table->decimal('lead_time_factor', 4, 2)
                  ->default(1.00)
                  ->comment('Factor multiplicador LT para zona roja y verde (LTF)');

            // ── Parámetros de variabilidad ────────────────────────────────
            $table->decimal('variability_factor', 4, 2)
                  ->default(0.50)
                  ->comment('Factor de variabilidad para seguridad de zona roja (VF) 0.0–1.0');

            // ── Parámetros de pedido ──────────────────────────────────────
            $table->unsignedSmallInteger('order_cycle')
                  ->default(14)
                  ->comment('Ciclo de pedido en días (OC) – define zona verde mínima');

            $table->unsignedInteger('moq')
                  ->default(1)
                  ->comment('Cantidad mínima de pedido (MOQ)');

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('buffer_profiles');
    }
}
