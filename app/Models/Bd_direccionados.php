<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon; // Asegúrate de importar Carbon o DateTimeInterface

class Bd_direccionados extends Model
{
   
    protected $table = 'bd_direccionados';


    protected $fillable = [
            'Idt',
            'IDDireccionamiento',
            'NoPrescripcion',
            'TipoIDPaciente',
            'NoIDPaciente',
            'CantTotAEntregar',
            'NoEntrega',
            'TipoIDProv',
            'NoIDProv',
            'CodSerTecAEntregar',
            'FecMaxEnt',
            'FecDireccionamiento',
            'NoIDEPS',
            'CodEPS',
            'CodSedeProv',
            'IdProgramacion',
            'fechapro',
            'fechaanuladopro',
            'IdEntregado',
            'fechaentregado',
            'fechaanuladoentregado',
            'IdReporteEntrega',
            'fechareporteentregado',
            'fechaanuladoreporteentregado',
            'IdFacturado',
            'fechafacturado',
            'fechaanuladofacturado',
            'estado'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
