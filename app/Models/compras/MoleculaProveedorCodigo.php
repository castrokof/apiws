<?php

namespace App\Models\compras;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes; // <- actívalo si agregaste softDeletes

class MoleculaProveedorCodigo extends Model
{
    // use SoftDeletes;

    protected $table = 'molecula_proveedor_codigos';

    protected $fillable = [
        'molecule_id',
        'proveedor_id',     // si decides normalizar proveedores (puede ser nullable)
        'nombre_proveedor', // si aún no tienes tabla proveedores
        'codigo_proveedor',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /** Relaciones */
    public function molecula()
    {
        return $this->belongsTo(Molecula::class, 'molecule_id');
    }

    /** Scopes */
    public function scopeActivos($q)               { return $q->where('activo', true); }
    public function scopePorProveedor($q, $pid)    { return $q->where('proveedor_id', $pid); }
    public function scopePorCodigoProveedor($q, $c){ return $q->where('codigo_proveedor', $c); }
}
