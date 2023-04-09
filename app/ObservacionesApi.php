<?php

namespace App;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class ObservacionesApi extends Model
{
    //
    protected $table = 'observacionesapi';
    protected $fillable = [
        'observacion',
        'usuario',
        'estado',
        'pendiente_id',
        'entregado_id'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
