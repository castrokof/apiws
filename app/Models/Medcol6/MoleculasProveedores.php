<?php

namespace App\Models\Medcol6;

use Illuminate\Database\Eloquent\Model;

class MoleculasProveedores extends Model
{
     protected $table = 'moleculas_proveedores';

    protected $fillable = [
        'codigo_medicamento',
        'tercero_id',
        'codigo_molecula_proveedor',
        'molecula_proveedor',
        'presentacion',
        'costo',
        'estado',
    ];

    protected $casts = [
        'costo'  => 'decimal:2',
        'estado' => 'integer',
    ];

    // ── Relación con terceros ────────────────────────────────────────────────
    public function tercero()
    {
        return $this->belongsTo(
            \App\Models\compras\medcol3\Medcolmedicamentos3::class,
            'tercero_id',
            'id'
        );
    }

    // ── Scope activos ────────────────────────────────────────────────────────
    public function scopeActivos($query)
    {
        return $query->where('estado', 1);
    }
}