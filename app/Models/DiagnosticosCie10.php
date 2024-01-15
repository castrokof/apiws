<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosticosCie10 extends Model
{
    protected $table = 'diagnosticos';
   
    protected $fillable = [
        
        'table',
        'codigo',
        'descripcion',
        'estado'
       
        
    ];
    
}
