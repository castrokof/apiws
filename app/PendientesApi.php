<?php

namespace App;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class PendientesApi extends Model
{
    protected $table = 'usuarioapi';


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
        'codigo',
        'nombre',
        'cums',
        'cantidad',
        'cajero'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
