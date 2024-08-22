<?php

namespace App\Models\Medcol5;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class ObservacionesApiMedcol5 extends Model
{
    //
    protected $table = 'observaciones_api_medcol5';
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
