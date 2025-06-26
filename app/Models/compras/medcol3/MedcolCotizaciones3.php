<?php

namespace App\Models\compras\medcol3;

use Illuminate\Database\Eloquent\Model;


use DateTimeInterface;

class MedcolCotizaciones3 extends Model
{
    
     protected $table = 'base_cotizaciones_medcol3';
     
     protected $fillable = [
                
            'codigo',
            'nombre',
            'cums',
            'cums_corto',
            'marca',
            'precio',
            'codigo_tercero',
            'nombre_sucursal',
            'fecha_inicio',
            'fecha_fin',
            'estado',
            'consumoMes',
            'consumoYear',
            'user_id',
            'listas_id'
    ];
    
    
         protected function serializeDate(DateTimeInterface $date)
    {
        
        return $date->format('Y-m-d H:i:s');
    
    }
    
     public function useridc3()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    public function ListasIdc3()
    {
                return $this->belongsTo(MedcolCotiGeneral3::class, 'listas_id');
    }
}
