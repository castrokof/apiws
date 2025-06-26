<?php

namespace App\Models\compras\medcol3;

use Illuminate\Database\Eloquent\Model;

class Medcolcompras3 extends Model
{
    //

    protected $table = 'orden_compra_medcol3';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'documentoOrden',
        'numeroOrden',
        'codigo',
        'nombre',
        'cums',
        'presentacion',
        'marca',
        'cantidad',
        'precio',
        'subtotal',
        'iva',
        'proveedor_id',
        'contrato',
        'observaciones',
        'usuario_id',
        'estado',
        'cantidadEntregada',
        'created_at',
        'totalParcial'
    ];
    
    /**
     * Get the proveedor associated with the OrdenCompraMedcol2
     */
    public function proveedor()
    {
        return $this->belongsTo(Medcolterceros3::class, 'proveedor_id', 'id');
    }
}
