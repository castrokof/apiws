<?php

namespace App\Models\compras\medcol4;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MedcolCotizaciones4 extends Model
{
     
     protected $table = 'base_cotizaciones_medcol4';
     
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
