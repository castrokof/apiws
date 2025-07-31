<?php

namespace App\Helpers;

use Carbon\Carbon;

class DeliveryMetricsHelper
{
    /**
     * Calcula los días transcurridos desde la fecha de factura hasta hoy
     *
     * @param string $fechaFactura
     * @return int
     */
    public static function calcularDiasTranscurridos($fechaFactura)
    {
        if (!$fechaFactura) {
            return 0;
        }

        $fechaFacturaCarbon = Carbon::parse($fechaFactura);
        $hoy = Carbon::now();
        
        return $fechaFacturaCarbon->diffInDays($hoy);
    }

    /**
     * Calcula la fecha estimada de entrega (48 horas después de la fecha de factura)
     *
     * @param string $fechaFactura
     * @return string|null
     */
    public static function calcularFechaEstimadaEntrega($fechaFactura)
    {
        if (!$fechaFactura) {
            return null;
        }

        $fechaFacturaCarbon = Carbon::parse($fechaFactura);
        return $fechaFacturaCarbon->addHours(48)->format('Y-m-d H:i');
    }

    /**
     * Calcula el estado de prioridad basado en el tiempo transcurrido
     *
     * @param string $fechaFactura
     * @return array
     */
    public static function calcularEstadoPrioridad($fechaFactura)
    {
        if (!$fechaFactura) {
            return [
                'estado' => 'SIN_FECHA',
                'clase_css' => 'text-muted',
                'icono' => 'fas fa-question-circle'
            ];
        }

        $fechaFacturaCarbon = Carbon::parse($fechaFactura);
        $hoy = Carbon::now();
        $horasTranscurridas = $fechaFacturaCarbon->diffInHours($hoy);

        if ($horasTranscurridas <= 24) {
            return [
                'estado' => 'EN_TIEMPO',
                'clase_css' => 'text-success',
                'icono' => 'fas fa-check-circle',
                'descripcion' => 'Entrega en tiempo óptimo'
            ];
        } elseif ($horasTranscurridas <= 48) {
            return [
                'estado' => 'PRIORIDAD',
                'clase_css' => 'text-warning',
                'icono' => 'fas fa-clock',
                'descripcion' => 'Requiere atención prioritaria'
            ];
        } elseif ($horasTranscurridas <= 72) {
            return [
                'estado' => 'CRITICO',
                'clase_css' => 'text-danger',
                'icono' => 'fas fa-exclamation-triangle',
                'descripcion' => 'Estado crítico - Límite superado'
            ];
        } else {
            return [
                'estado' => 'URGENTE',
                'clase_css' => 'text-danger font-weight-bold',
                'icono' => 'fas fa-exclamation-circle',
                'descripcion' => 'Requiere acción inmediata'
            ];
        }
    }

    /**
     * Obtiene todas las métricas de entrega en un solo array
     *
     * @param string $fechaFactura
     * @return array
     */
    public static function obtenerTodasLasMetricas($fechaFactura)
    {
        $diasTranscurridos = self::calcularDiasTranscurridos($fechaFactura);
        $fechaEstimada = self::calcularFechaEstimadaEntrega($fechaFactura);
        $estadoPrioridad = self::calcularEstadoPrioridad($fechaFactura);

        return [
            'dias_transcurridos' => $diasTranscurridos,
            'fecha_estimada_entrega' => $fechaEstimada,
            'estado_prioridad' => $estadoPrioridad
        ];
    }
}