<?php

namespace App\Models\compras\medcol2;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Medcolterceros2 extends Model
{
    
    
    protected $table = 'terceros_api_medcol2';
    
     
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
