<?php

namespace App\Models\Medcold;

use Illuminate\Database\Eloquent\Model;

use DateTimeInterface;

class DispensadoApiMedcold extends Model
{
    
    
     protected $table = 'dispensado_medcold';
     
     protected $fillable = [
        'idusuario',
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
        'cajero'
    ];

   /* protected $casts = [
        'fecha_ordenamiento' => 'datetime',
        'fecha_suministro' => 'datetime',
    ];*/
    
     protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
