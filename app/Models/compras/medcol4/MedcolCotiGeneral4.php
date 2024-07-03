<?php

namespace App\Models\compras\medcol4;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MedcolCotiGeneral4 extends Model
{
   protected $table = 'base_coti_general_medcol4';
     
     protected $fillable = [
                
            'archivo',
            'registros',
            'fecha_inicio',
            'fecha_fin',
            'estado'
                           ];
    
    
         protected function serializeDate(DateTimeInterface $date)
    {
        
        return $date->format('Y-m-d H:i:s');
    
    }        
}
