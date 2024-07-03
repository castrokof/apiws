<?php

namespace App\Models\compras\medcol3;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MedcolCotiGeneral3 extends Model
{
   
     protected $table = 'base_coti_general_medcol3';
     
     protected $fillable = [
                
            'archivo',
            'registros',
            'fecha_inicio',
            'fecha_fin',
            'estado',
            'user_id'
                           ];
    
    
         protected function serializeDate(DateTimeInterface $date)
    {
        
        return $date->format('Y-m-d H:i:s');
    
    }      
    
     public function useridc()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    public function CotizacionAdd(){
        return $this->hasMany(MedcolCotizaciones3::class, 'listas_id');
    }

    
    
     
}
