<?php

namespace App\Models\compras\medcol4;

use Illuminate\Database\Eloquent\Model;

class Medcolcompras4 extends Model
{
    //

    protected $table = 'orden_compra_medcol4';

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
        'marca',
        'cantidad',
        'precio',
        'subtotal',
        'iva',
        'proveedor_id',
        'contrato',
        'observaciones',
        'usuario_id'
    ];
    
    /**
     * Get the proveedor associated with the OrdenCompraMedcol2
     */
    public function proveedor()
    {
        return $this->belongsTo(Medcolterceros4::class, 'proveedor_id', 'id');
    }
    
}
