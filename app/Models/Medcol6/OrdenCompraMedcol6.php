<?php

namespace App\Models\Medcol6;

use Illuminate\Database\Eloquent\Model;
use App\Models\compras\medcol3\Medcolcompras3;

class OrdenCompraMedcol6 extends Model
{
    protected $table = 'ordenes_compra_medcol6';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'orden_de_compra',
        'nit',
        'proveedor',
        'telefono',
        'fecha',
        'cod_farmacia',
        'num_orden_compra',
        'programa',
        'contacto',
        'direccion',
        'email',
        'codigo_proveedor',
        'user_create',
        'estado',
        'total',
        'sub-_total',
        'iva',
        'observaciones',
        'totalParcial',
        'facturas'

    ];

    // Relación con detalles de la orden de compra
    public function detalles()
    {
        return $this->hasMany(DetalleOrdenCompraMedcol6::class, 'id_orden_compra', 'id');
    }

    public function medcolCompra3()
    {
        return $this->belongsTo(Medcolcompras3::class, 'num_orden_compra', 'numeroOrden');
    }
}
