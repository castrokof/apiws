<?php

namespace App\Models\compras\medcol2;

use Illuminate\Database\Eloquent\Model;


use DateTimeInterface;

class Medcolmedicamentos2 extends Model
{
   
     protected $table = 'medicamentos_api_medcol2';
     
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

