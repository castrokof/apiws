<?php

namespace App\Models\Medcol6;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Model;

class DispensadoApiMedcol6 extends Model
{
    

    protected $table = 'dispensado_medcol6';

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
        'codigoSOS',
        'codigo',
        'tipo_medicamento',
        'nombre_comercial',
        'nombre_generico',
        'atc',
        'forma',
        'concentracion',
        'unidad_medicamento',
        'cantidad_ordenada',
        'numero_unidades',
        'regimen',
        'paciente',
        'primer_apellido',
        'segundo_apellido',
        'primer_nombre',
        'segundo_nombre',
        'cuota_moderadora',
        'copago',
        'num_total_entregas',
        'numero_entrega',
        'numero_orden',
        'formula_completa',
        'fecha_ordenamiento',
        'fecha_suministro',
        'dx',
        'nitips',
        'ips',
        'tipoidmedico',
        'numeroIdentificacion',
        'id_medico',
        'medico',
        'especialidadmedico',
        'mipres',
        'precio_unitario',
        'valor_total',
        'reporte_entrega_nopbs',
        'estado',
        'centroprod',
        'drogueria',
        'cajero',
        'documento_origen',
        'factura_origen',
        'frecuencia',
        'dosis',
        'duracion_tratamiento',
        'cobertura',
        'tipocontrato',
        'tipoentrega',
        'cod_dispen_transacc',
        'plan',
        'via',
        'ciudad',
        'ID_REGISTRO'
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
