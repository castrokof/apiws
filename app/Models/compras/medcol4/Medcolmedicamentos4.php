<?php

namespace App\Models\compras\medcol4;

use Illuminate\Database\Eloquent\Model;

use DateTimeInterface;

class Medcolmedicamentos4 extends Model
{
   protected $table = 'medicamentos_api_medcol4';
     
     protected $fillable = [
                    
                    'tipo_MI',
                    'codigo',
                    'nombre',
                    'marca',
                    'atc',
                    'forma',
                    'concentracion',
                    'cums',
                    'estado'
                           ];
    
    
         protected function serializeDate(DateTimeInterface $date)
    {
        
        return $date->format('Y-m-d H:i:s');
    
    }        
   
}




