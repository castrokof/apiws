<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGestionHistoricoMedcol6Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gestion_historico_medcol6', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Relaciones
            $table->unsignedBigInteger('pendiente_id')->nullable()->comment('ID del pendiente asociado');
            $table->string('historia')->index()->comment('Historia del paciente para búsquedas rápidas');
            $table->unsignedBigInteger('usuario_id')->nullable()->comment('Usuario que registró el evento');

            // Tipo de evento
            $table->enum('tipo_evento', [
                'CAMBIO_ESTADO',           // Automático: Estado cambió
                'CONTACTO_LLAMADA',        // Manual: Llamada al paciente
                'CONTACTO_MENSAJE',        // Manual: Mensaje enviado
                'CONTACTO_VISITA',         // Manual: Visita domiciliaria
                'OBSERVACION_GESTION',     // Manual: Nota de gestión
                'CAMBIO_SALDO',            // Automático: Disponibilidad de medicamento cambió
                'CREACION_PENDIENTE',      // Automático: Nuevo pendiente creado
                'ANULACION',               // Automático: Pendiente anulado
                'ENTREGA_EXITOSA',         // Automático: Entrega completada
                'REPROGRAMACION'           // Manual: Reprogramación de entrega
            ])->index();

            // Detalles del evento
            $table->string('titulo', 255)->comment('Título breve del evento');
            $table->text('descripcion')->nullable()->comment('Descripción detallada');

            // Cambios de estado (solo para eventos automáticos)
            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo')->nullable();

            // Metadata adicional en JSON
            $table->json('metadata')->nullable()->comment('Datos adicionales: medicamentos, resultado_contacto, etc.');

            // Datos de contacto (para eventos manuales)
            $table->enum('resultado_contacto', [
                'EXITOSO',
                'NO_CONTESTA',
                'TELEFONO_INVALIDO',
                'REAGENDAR',
                'RECHAZADO',
                'OTRO'
            ])->nullable();

            // Campos de seguimiento
            $table->boolean('requiere_seguimiento')->default(false);
            $table->dateTime('fecha_seguimiento')->nullable();

            // Timestamps
            $table->timestamps();

            // Foreign keys
            $table->foreign('pendiente_id', 'fk_gestion_hist_pendiente')
                  ->references('id')
                  ->on('pendiente_api_medcol6')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('usuario_id', 'fk_gestion_hist_usuario')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });

        // Índices compuestos para optimización de consultas
        Schema::table('gestion_historico_medcol6', function (Blueprint $table) {
            $table->index(['historia', 'created_at'], 'idx_historia_fecha');
            $table->index(['tipo_evento', 'created_at'], 'idx_tipo_fecha');
            $table->index(['pendiente_id', 'tipo_evento'], 'idx_pendiente_tipo');
            $table->index(['usuario_id', 'created_at'], 'idx_usuario_fecha');
            $table->index('requiere_seguimiento', 'idx_requiere_seguimiento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gestion_historico_medcol6');
    }
}
