<?php

namespace App\Models\Medcol6;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class ObservacionesApiMedcol6 extends Model
{
    //
    protected $table = 'observaciones_api_medcol6';
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

    /**
     * Relación con pendiente
     */
    public function pendiente()
    {
        return $this->belongsTo(PendienteApiMedcol6::class, 'pendiente_id');
    }
}
