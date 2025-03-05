<?php

namespace App\Models;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon; // Asegúrate de importar Carbon o DateTimeInterface

class Bd_reporteentrega extends Model
{
     protected $table = 'reporteentregado';


    protected $fillable = [
            'id',
            'IDr',
            'IDReporteEntrega',
            'NoPrescripcion',
            'TipoTec',
            'ConTec',
            'TipoIDPaciente',
            'NoIDPaciente',
            'NoEntrega',
            'EstadoEntrega',
            'CausaNoEntrega',
            'ValorEntregado',
            'CodTecEntregado',
            'CantTotEntregada',
            'NoLote',
            'FecEntrega',
            'FecRepEntrega',
            'EstRepEntrega',
            'FecAnulacion',
            'estado'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
