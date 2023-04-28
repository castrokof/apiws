<?php

namespace App\Models\Medcol3;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class ObservacionesApiMedcol3 extends Model
{
    //
    protected $table = 'observaciones_api_medcol3';
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
