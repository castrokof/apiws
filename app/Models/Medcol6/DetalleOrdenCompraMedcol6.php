<?php

namespace App\Models\Medcol6;

use Illuminate\Database\Eloquent\Model;

class DetalleOrdenCompraMedcol6 extends Model
{
    protected $table = 'detalles_orden_compra_medcol6';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'id_orden_compra',
        'codigo_molecula_proveedor',
        'descripcion',
        'presentacion',
        'cantidad',
        'v_unitario',
        'v_total',
        'iva',
        'vr_iva',
        'sub_total',
        'faltantes',
        'entregadas',
        'estado',
        'total',
        'observaciones',
        'totalParcial'
    ];

    // Relación inversa con la orden de compra
    public function orden()
    {
        return $this->belongsTo(OrdenCompraMedcol6::class, 'id_orden_compra', 'id');
    }
}
