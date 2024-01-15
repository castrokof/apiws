<?php

namespace App\Models\MedcolCli;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class DispensadoCliMedcol extends Model
{
    
     protected $table = 'unificacion_dispensado';

    protected $fillable = [
        
        'idusuario',
        'Origen',
        'tipo',
        'facturad',
        'factura',
        'tipodocument',
        'historia',
        'autorizacion',
        'cums',
        'expediente',
        'consecutivo',
        'cums_rips',
        'codigo',
        'tipo_medicamento',
        'nombre_generico',
        'atc',
        'forma',
        'concentracion',
        'unidad_medicamento',
        'numero_unidades',
        'regimen',
        'paciente',
        'primer_apellido',
        'segundo_apellido',
        'primer_nombre',
        'segundo_nombre',
        'cuota_moderadora',
        'copago',
        'numero_entrega',
        'fecha_ordenamiento',
        'fecha_suministro',
        'dx',
        'id_medico',
        'medico',
        'mipres',
        'precio_unitario',
        'valor_total',
        'reporte_entrega_nopbs',
        'estado',
        'centroprod',
        'drogueria',
        'cajero',
        'created_at',
        'updated_at'
    ];
    
    
    
    
    
    
   protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
