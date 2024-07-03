<?php

namespace App\Models\compras\medcol2;

use Illuminate\Database\Eloquent\Model;

class Medcolcompras2 extends Model
{
    //
    
    protected $table = 'orden_compra_medcol2';

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
    ];
    
    /**
     * Get the proveedor associated with the OrdenCompraMedcol2
     */
    public function proveedor()
    {
        return $this->belongsTo(Medcolterceros2::class, 'proveedor_id', 'id');
    }
}
