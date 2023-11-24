<?php

namespace App\Models\Medcold;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class ObservacionesApiMedcold extends Model
{
    //
    protected $table = 'observaciones_api_medcold';
    protected $fillable = [
        'observacion',
        'usuario',
        'estado',
        'pendiente_id'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
