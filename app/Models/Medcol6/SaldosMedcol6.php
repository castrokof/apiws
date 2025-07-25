<?php

namespace App\Models\Medcol6;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SaldosMedcol6 extends Model
{
    
    protected $table = 'saldos_medcol6';
    
    protected $fillable = [
        'ips',
        'deposito',
        'agrupador',
        'codigo',
        'cums',
        'nombre',
        'marca',
        'costo_unitario',
        'saldo',
        'total',
        'fecha_vencimiento',
        'invima',
        'fecha_saldo',
        'grupo',
        'subgrupo',
        'linea',
        'nombre_ips',
        'nombre_deposito',
        'nombre_grupo',
        'nombre_subgrupo'
    ];

    protected $dates = [
        'fecha_vencimiento',
        'fecha_saldo'
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'fecha_saldo' => 'date',
        'costo_unitario' => 'decimal:2',
        'saldo' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    /**
     * Scope para filtrar por depósito
     */
    public function scopeByDeposito($query, $deposito)
    {
        return $query->where('deposito', $deposito);
    }

    /**
     * Scope para filtrar por grupo
     */
    public function scopeByGrupo($query, $grupo)
    {
        return $query->where('grupo', $grupo);
    }

    /**
     * Scope para filtrar por fecha de saldo
     */
    public function scopeByFechaSaldo($query, $fecha)
    {
        return $query->whereDate('fecha_saldo', $fecha);
    }

    /**
     * Scope para productos con saldo mayor a cero
     */
    public function scopeConSaldo($query)
    {
        return $query->where('saldo', '>', 0);
    }

    /**
     * Scope para productos próximos a vencer (30 días)
     */
    public function scopeProximosVencer($query, $dias = 30)
    {
        $fechaLimite = Carbon::now()->addDays($dias);
        return $query->where('fecha_vencimiento', '<=', $fechaLimite)
                    ->where('fecha_vencimiento', '>=', Carbon::now());
    }

    /**
     * Accessor para obtener el estado del producto basado en fecha de vencimiento
     */
    public function getEstadoVencimientoAttribute()
    {
        if (!$this->fecha_vencimiento) {
            return 'Sin fecha';
        }

        $diasParaVencer = Carbon::now()->diffInDays($this->fecha_vencimiento, false);
        
        if ($diasParaVencer < 0) {
            return 'Vencido';
        } elseif ($diasParaVencer <= 30) {
            return 'Próximo a vencer';
        } elseif ($diasParaVencer <= 90) {
            return 'Vigente (corto plazo)';
        } else {
            return 'Vigente';
        }
    }

    /**
     * Accessor para formatear el nombre completo del producto
     */
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ($this->marca ? ' - ' . $this->marca : '');
    }

    /**
     * Mutator para normalizar el código
     */
    public function setCodigoAttribute($value)
    {
        $this->attributes['codigo'] = strtoupper(trim($value));
    }

    /**
     * Mutator para normalizar el nombre
     */
    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = strtoupper(trim($value));
    }
}