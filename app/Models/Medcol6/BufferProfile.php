<?php

namespace App\Models\Medcol6;

use Illuminate\Database\Eloquent\Model;

class BufferProfile extends Model
{
    protected $table    = 'buffer_profiles';
    protected $fillable = [
        'nombre', 'descripcion',
        'lead_time', 'lead_time_factor',
        'variability_factor',
        'order_cycle', 'moq',
        'is_active',
    ];

    protected $casts = [
        'lead_time_factor'   => 'float',
        'variability_factor' => 'float',
        'is_active'          => 'boolean',
    ];

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ── Helpers de cálculo DDMRP ─────────────────────────────────────────────

    /**
     * Calcula las zonas DDMRP para un DDP (demanda diaria promedio) dado.
     *
     * @param  float $ddp  Demanda Diaria Promedio en unidades
     * @return array{zona_roja, zona_roja_base, zona_roja_seg, zona_amarilla, zona_verde, tor, toy, tog}
     */
    public function calcularZonas(float $ddp): array
    {
        $lt  = (float) $this->lead_time;
        $ltf = (float) $this->lead_time_factor;
        $vf  = (float) $this->variability_factor;
        $oc  = (float) $this->order_cycle;
        $moq = (float) $this->moq;

        // Zona Roja
        $zonaRojaBase = $ddp * $lt * $ltf;
        $zonaRojaSeg  = $zonaRojaBase * $vf;
        $zonaRoja     = $zonaRojaBase + $zonaRojaSeg;

        // Zona Amarilla
        $zonaAmarilla = $ddp * $lt;

        // Zona Verde = MAX(DDP×OC, DDP×LT×LTF, MOQ)
        $zonaVerde = max($ddp * $oc, $ddp * $lt * $ltf, $moq);

        $tor = $zonaRoja;
        $toy = $zonaRoja + $zonaAmarilla;
        $tog = $zonaRoja + $zonaAmarilla + $zonaVerde;

        return compact(
            'zonaRoja', 'zonaRojaBase', 'zonaRojaSeg',
            'zonaAmarilla', 'zonaVerde',
            'tor', 'toy', 'tog'
        );
    }
}
