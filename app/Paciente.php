<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Paciente extends Model
{
    protected $table = 'pacientes';

    protected $fillable = [
        'tipdocum',
        'historia',
        'paciente',
        'direccion',
        'telefono',
        'regimen',
        'nivel',
        'edad',
        'sexo',
        'pqrs',
        'estado',
        'programa',
        'alto_costo',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
