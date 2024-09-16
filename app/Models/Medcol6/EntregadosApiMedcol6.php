<?php

namespace App\Models\Medcol6;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class EntregadosApiMedcol6 extends Model
{
    //
    protected $table = 'entregados_api_medcol6';


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
        'centroproduccion',
        'observaciones',
        'numero_orden',
        'codigoSOS',
        'municipio',
        't_entrega_dias',
        't_entrega_horas',
        'cod_agrupador',
        'nombre_comercial',
        'causa_pendiente',
        'lugar_entrega',
        'secuencia_pendiente'

    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
