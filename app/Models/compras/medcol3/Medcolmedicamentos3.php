<?php

namespace App\Models\compras\medcol3;

use Illuminate\Database\Eloquent\Model;

use DateTimeInterface;

class Medcolmedicamentos3 extends Model
{
    
    protected $table = 'medicamentos_api_medcol3';
    protected $primaryKey = 'id';
    public $timestamps = true;
     
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


