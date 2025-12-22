<?php

namespace App\Observers;

use App\Models\Medcol6\PendienteApiMedcol6;
use App\Models\Medcol6\GestionHistoricoMedcol6;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PendienteApiMedcol6Observer
{
    /**
     * Handle the PendienteApiMedcol6 "created" event.
     * Registra cuando se crea un nuevo pendiente
     *
     * @param  \App\Models\Medcol6\PendienteApiMedcol6  $pendiente
     * @return void
     */
    public function created(PendienteApiMedcol6 $pendiente)
    {
        try {
            GestionHistoricoMedcol6::create([
                'pendiente_id' => $pendiente->id,
                'historia' => $pendiente->historia,
                'usuario_id' => Auth::id(),
                'tipo_evento' => 'CREACION_PENDIENTE',
                'titulo' => 'Pendiente creado',
                'descripcion' => sprintf(
                    'Se creó un nuevo pendiente para el paciente %s %s %s',
                    $pendiente->nombre1 ?? '',
                    $pendiente->nombre2 ?? '',
                    $pendiente->apellido1 ?? ''
                ),
                'estado_anterior' => null,
                'estado_nuevo' => $pendiente->estado,
                'metadata' => [
                    'factura' => $pendiente->factura,
                    'orden_externa' => $pendiente->orden_externa,
                    'numero_orden' => $pendiente->numero_orden,
                    'medicamento' => $pendiente->nombre,
                    'codigo' => $pendiente->codigo,
                    'cantidad' => $pendiente->cantidad,
                    'centroproduccion' => $pendiente->centroproduccion,
                    'lugar_entrega' => $pendiente->lugar_entrega,
                    't_entrega_dias' => $pendiente->t_entrega_dias,
                ]
            ]);

            Log::info('Evento CREACION_PENDIENTE registrado', [
                'pendiente_id' => $pendiente->id,
                'historia' => $pendiente->historia,
                'usuario_id' => Auth::id()
            ]);
        } catch (\Exception $e) {
            Log::error('Error al registrar creación de pendiente en histórico', [
                'pendiente_id' => $pendiente->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle the PendienteApiMedcol6 "updating" event.
     * Detecta cambios de estado y otras modificaciones importantes
     *
     * @param  \App\Models\Medcol6\PendienteApiMedcol6  $pendiente
     * @return void
     */
    public function updating(PendienteApiMedcol6 $pendiente)
    {
        try {
            // Detectar cambio de estado
            if ($pendiente->isDirty('estado')) {
                $estadoAnterior = $pendiente->getOriginal('estado');
                $estadoNuevo = $pendiente->estado;

                // Determinar tipo de evento según el cambio de estado
                $tipoEvento = 'CAMBIO_ESTADO';
                $titulo = 'Cambio de estado';
                $descripcion = sprintf(
                    'Estado cambió de %s a %s',
                    $estadoAnterior,
                    $estadoNuevo
                );

                // Eventos especiales según estado nuevo
                if ($estadoNuevo === 'ENTREGADO') {
                    $tipoEvento = 'ENTREGA_EXITOSA';
                    $titulo = 'Entrega exitosa';
                    $descripcion = sprintf(
                        'El pendiente fue entregado exitosamente. Estado anterior: %s',
                        $estadoAnterior
                    );
                } elseif ($estadoNuevo === 'ANULADO') {
                    $tipoEvento = 'ANULACION';
                    $titulo = 'Pendiente anulado';
                    $descripcion = sprintf(
                        'El pendiente fue anulado. Estado anterior: %s',
                        $estadoAnterior
                    );
                }

                GestionHistoricoMedcol6::create([
                    'pendiente_id' => $pendiente->id,
                    'historia' => $pendiente->historia,
                    'usuario_id' => Auth::id(),
                    'tipo_evento' => $tipoEvento,
                    'titulo' => $titulo,
                    'descripcion' => $descripcion,
                    'estado_anterior' => $estadoAnterior,
                    'estado_nuevo' => $estadoNuevo,
                    'metadata' => [
                        'factura' => $pendiente->factura,
                        'medicamento' => $pendiente->nombre,
                        'codigo' => $pendiente->codigo,
                        'cantidad' => $pendiente->cantidad,
                        'fecha_entrega' => $pendiente->fecha_entrega,
                        'doc_entrega' => $pendiente->doc_entrega,
                        'factura_entrega' => $pendiente->factura_entrega,
                    ]
                ]);

                Log::info('Evento de cambio de estado registrado', [
                    'tipo_evento' => $tipoEvento,
                    'pendiente_id' => $pendiente->id,
                    'estado_anterior' => $estadoAnterior,
                    'estado_nuevo' => $estadoNuevo
                ]);
            }

            // Detectar cambio en cantidad (posible cambio de saldo/disponibilidad)
            if ($pendiente->isDirty('cantidad')) {
                $cantidadAnterior = $pendiente->getOriginal('cantidad');
                $cantidadNueva = $pendiente->cantidad;

                GestionHistoricoMedcol6::create([
                    'pendiente_id' => $pendiente->id,
                    'historia' => $pendiente->historia,
                    'usuario_id' => Auth::id(),
                    'tipo_evento' => 'CAMBIO_SALDO',
                    'titulo' => 'Cambio en cantidad',
                    'descripcion' => sprintf(
                        'La cantidad cambió de %s a %s unidades',
                        $cantidadAnterior,
                        $cantidadNueva
                    ),
                    'estado_anterior' => (string) $cantidadAnterior,
                    'estado_nuevo' => (string) $cantidadNueva,
                    'metadata' => [
                        'medicamento' => $pendiente->nombre,
                        'codigo' => $pendiente->codigo,
                        'diferencia' => $cantidadNueva - $cantidadAnterior
                    ]
                ]);

                Log::info('Evento CAMBIO_SALDO registrado', [
                    'pendiente_id' => $pendiente->id,
                    'cantidad_anterior' => $cantidadAnterior,
                    'cantidad_nueva' => $cantidadNueva
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error al registrar actualización de pendiente en histórico', [
                'pendiente_id' => $pendiente->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle the PendienteApiMedcol6 "deleted" event.
     * Registra cuando se elimina un pendiente
     *
     * @param  \App\Models\Medcol6\PendienteApiMedcol6  $pendiente
     * @return void
     */
    public function deleted(PendienteApiMedcol6 $pendiente)
    {
        try {
            // Nota: Este evento se registrará antes de que el cascade delete
            // elimine los registros de gestion_historico_medcol6
            // Si queremos preservar el histórico, deberíamos considerar soft deletes

            GestionHistoricoMedcol6::create([
                'pendiente_id' => null, // Ya que será eliminado
                'historia' => $pendiente->historia,
                'usuario_id' => Auth::id(),
                'tipo_evento' => 'ANULACION',
                'titulo' => 'Pendiente eliminado',
                'descripcion' => sprintf(
                    'El pendiente ID %s fue eliminado del sistema. Estado final: %s',
                    $pendiente->id,
                    $pendiente->estado
                ),
                'estado_anterior' => $pendiente->estado,
                'estado_nuevo' => 'ELIMINADO',
                'metadata' => [
                    'pendiente_id_original' => $pendiente->id,
                    'factura' => $pendiente->factura,
                    'medicamento' => $pendiente->nombre,
                    'codigo' => $pendiente->codigo,
                    'cantidad' => $pendiente->cantidad,
                    'fecha_eliminacion' => now()->toDateTimeString()
                ]
            ]);

            Log::info('Evento de eliminación registrado', [
                'pendiente_id' => $pendiente->id,
                'historia' => $pendiente->historia,
                'usuario_id' => Auth::id()
            ]);
        } catch (\Exception $e) {
            Log::error('Error al registrar eliminación de pendiente en histórico', [
                'pendiente_id' => $pendiente->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
