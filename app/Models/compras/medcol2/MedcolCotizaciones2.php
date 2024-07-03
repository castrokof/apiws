<?php

namespace App\Models\compras\medcol2;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MedcolCotizaciones2 extends Model
{
     
     protected $table = 'base_cotizaciones_medcol2';
     
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
            'estado'
                           ];
    
    
         protected function serializeDate(DateTimeInterface $date)
    {
        
        return $date->format('Y-m-d H:i:s');
    
    }        
}
