<?php

namespace App\Models\Medcold;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class EntregadosApiMedcold extends Model
{
    //
    protected $table = 'entregados_api_medcold';


    protected $fillable = [
        'Tipodocum',
        'cantdpx',
        'cantord',
        'fecha_factura',
        'fecha',
        'historia',
        'apellido1',
        'apellido2',
        'nombre1',
        'nombre2',
        'cantedad',
        'direcres',
        'telefres',
        'documento',
        'factura',
        'orden_externa',
        'codigo',
        'nombre',
        'cums',
        'cantidad',
        'cajero',
        'usuario',
        'estado',
        'fecha_impresion',
        'fecha_entrega',
        'fecha_anulado',
        'doc_entrega',
        'factura_entrega',
        'centroproduccion'

    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
