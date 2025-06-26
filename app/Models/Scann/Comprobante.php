<?php
namespace App\Models\Scann;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model {
    

    protected $table = 'comprobantes'; // Nombre de la tabla en la BD
    protected $fillable = ['codigo', 'comprobante', 'orden', 'pdf', 'usuario'];

    protected $casts = [
        'comprobante' => 'array',
        'orden' => 'array',
        'pdf' => 'array'
    ];
    
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}