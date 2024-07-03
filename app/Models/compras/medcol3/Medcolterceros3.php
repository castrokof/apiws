<?php

namespace App\Models\compras\medcol3;

use Illuminate\Database\Eloquent\Model;

use DateTimeInterface;

class Medcolterceros3 extends Model
{
   
   
    protected $table = 'terceros_api_medcol3';
    protected $primaryKey = 'id';
    public $timestamps = true;
     
     protected $fillable = [
         
                    'codigo_tercero',
                    'nombre_sucursal',
                    'direccion',
                    'telefono',
                    'e_mail',
                    'estado'
                    
                           ];
            
            
      protected function serializeDate(DateTimeInterface $date)
    {
        
        return $date->format('Y-m-d H:i:s');
    
    }        
   
   
}

