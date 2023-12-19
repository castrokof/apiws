<?php

namespace App\Models\MedcolCli;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class PendienteCliMedcol extends Model
{
    //
    protected $table = 'unificacion_pendientes';


    protected $fillable = [
        'id', 'origen', 'Tipodocum', 'cantdpx', 'cantord', 'fecha_factura', 'fecha', 'historia', 'apellido1', 'apellido2', 'nombre1', 'nombre2', 'cantedad', 'direcres', 'telefres', 'documento', 'factura', 'orden_externa', 'codigo', 'nombre', 'cums', 'observaciones','cantidad', 'cajero', 'usuario', 'estado', 'fecha_impresion', 'fecha_entrega', 'fecha_anulado', 'doc_entrega', 'factura_entrega', 'centroproduccion', 'created_at', 'updated_at'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
