<?php

namespace App\Models\compras\medcol2;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MedcolCotiGeneral2 extends Model
{
  protected $table = 'base_coti_general_medcol2';
     
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
