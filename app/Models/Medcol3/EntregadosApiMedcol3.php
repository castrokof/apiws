<?php

namespace App\Models\Medcol3;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class EntregadosApiMedcol3 extends Model
{
    //
    protected $table = 'entregados_api_medcol3';


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
        'fecha_anulado'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
