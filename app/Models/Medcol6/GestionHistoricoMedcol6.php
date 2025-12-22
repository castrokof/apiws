<?php

namespace App\Models\Medcol6;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use App\User;

class GestionHistoricoMedcol6 extends Model
{
    protected $table = 'gestion_historico_medcol6';

    protected $fillable = [
        'pendiente_id',
        'historia',
        'usuario_id',
        'tipo_evento',
        'titulo',
        'descripcion',
        'estado_anterior',
        'estado_nuevo',
        'metadata',
        'resultado_contacto',
        'requiere_seguimiento',
        'fecha_seguimiento'
    ];

    protected $casts = [
        'metadata' => 'array',
        'requiere_seguimiento' => 'boolean',
        'fecha_seguimiento' => 'datetime'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Relación con pendiente
     */
    public function pendiente()
    {
        return $this->belongsTo(PendienteApiMedcol6::class, 'pendiente_id');
    }

    /**
     * Relación con usuario que registró el evento
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Scope: Filtrar por paciente (historia)
     */
    public function scopePorPaciente($query, $historia)
    {
        return $query->where('historia', $historia);
    }

    /**
     * Scope: Filtrar por tipo de evento
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_evento', $tipo);
    }

    /**
     * Scope: Filtrar por rango de fechas
     */
    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
    }

    /**
     * Scope: Solo eventos manuales (contactos y observaciones)
     */
    public function scopeEventosManuales($query)
    {
        return $query->whereIn('tipo_evento', [
            'CONTACTO_LLAMADA',
            'CONTACTO_MENSAJE',
            'CONTACTO_VISITA',
            'OBSERVACION_GESTION',
            'REPROGRAMACION'
        ]);
    }

    /**
     * Scope: Solo eventos automáticos
     */
    public function scopeEventosAutomaticos($query)
    {
        return $query->whereIn('tipo_evento', [
            'CAMBIO_ESTADO',
            'CAMBIO_SALDO',
            'CREACION_PENDIENTE',
            'ANULACION',
            'ENTREGA_EXITOSA'
        ]);
    }

    /**
     * Scope: Eventos que requieren seguimiento
     */
    public function scopeRequierenSeguimiento($query)
    {
        return $query->where('requiere_seguimiento', true)
                     ->where(function($q) {
                         $q->whereNull('fecha_seguimiento')
                           ->orWhere('fecha_seguimiento', '>', now());
                     });
    }

    /**
     * Accessor: Icono según tipo de evento
     */
    public function getIconoEventoAttribute()
    {
        $iconos = [
            'CAMBIO_ESTADO' => 'fas fa-exchange-alt',
            'CONTACTO_LLAMADA' => 'fas fa-phone',
            'CONTACTO_MENSAJE' => 'fas fa-sms',
            'CONTACTO_VISITA' => 'fas fa-walking',
            'OBSERVACION_GESTION' => 'fas fa-sticky-note',
            'CAMBIO_SALDO' => 'fas fa-boxes',
            'CREACION_PENDIENTE' => 'fas fa-plus-circle',
            'ANULACION' => 'fas fa-ban',
            'ENTREGA_EXITOSA' => 'fas fa-check-circle',
            'REPROGRAMACION' => 'fas fa-calendar-alt'
        ];

        return $iconos[$this->tipo_evento] ?? 'fas fa-circle';
    }

    /**
     * Accessor: Color badge según tipo de evento
     */
    public function getColorBadgeAttribute()
    {
        $colores = [
            'CAMBIO_ESTADO' => 'info',
            'CONTACTO_LLAMADA' => 'primary',
            'CONTACTO_MENSAJE' => 'primary',
            'CONTACTO_VISITA' => 'warning',
            'OBSERVACION_GESTION' => 'secondary',
            'CAMBIO_SALDO' => 'success',
            'CREACION_PENDIENTE' => 'info',
            'ANULACION' => 'danger',
            'ENTREGA_EXITOSA' => 'success',
            'REPROGRAMACION' => 'warning'
        ];

        return $colores[$this->tipo_evento] ?? 'secondary';
    }
}
