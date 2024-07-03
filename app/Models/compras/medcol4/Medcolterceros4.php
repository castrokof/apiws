<?php

namespace App\Models\compras\medcol4;

use Illuminate\Database\Eloquent\Model;

use DateTimeInterface;

class Medcolterceros4 extends Model
{
      
    protected $table = 'terceros_api_medcol4';
     
     protected $fillable = [
         
                    'codigo_tercero',
                    'nombre_sucursal',
                    'direccion',
                    'telefono',
                    'e_mail'
                    
                           ];
            
            
      protected function serializeDate(DateTimeInterface $date)
    {
        
        return $date->format('Y-m-d H:i:s');
    
    }        
}
