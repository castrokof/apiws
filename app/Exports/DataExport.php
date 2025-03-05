<?php
namespace App\Exports;
use App\Models\Medcol6\DispensadoApiMedcol6;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DataExport implements FromQuery, WithHeadings, WithMapping
{
    private $filters;
    
    public function __construct($filters)
    {
        $this->filters = $filters;
    }
    
    public function query()
    {
        return DispensadoApiMedcol6::query()
            ->select([
                'idusuario',
                'tipo',
                'facturad',
                'factura',
                'tipodocument',
                'historia',
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
                'numero_orden',
                'numero_entrega',
                'num_total_entregas',
                'fecha_ordenamiento',
                'fecha_suministro',
                'dx',
                'nitips',
                'ips',
                'autorizacion',
                'mipres',
                'reporte_entrega_nopbs',
                'id_medico',
                'numeroIdentificacion',
                'medico',
                'especialidadmedico',
                'precio_unitario',
                'valor_total',
                'estado',
                'centroprod',
                'drogueria',
                'cajero',
                'user_id',
                'frecuencia',
                'dosis',
                'duracion_tratamiento',
                'cobertura',
                'tipocontrato',
                'tipoentrega',
                'plan',
                'via',
                'ciudad'
            ])
            ->when(isset($this->filters['fechaini']), function ($query) {
                $query->where('fecha_suministro', '>=', $this->filters['fechaini']);
            })
            ->when(isset($this->filters['fechafin']), function ($query) {
                $query->where('fecha_suministro', '<=', $this->filters['fechafin']);
            })
            ->when(isset($this->filters['contrato']), function ($query) {
                $query->where('centroprod', $this->filters['contrato']);
            });
    }

    public function map($row): array
    {
        return [
            $row->idusuario,
            $row->tipo,
            $row->facturad,
            $row->factura,
            $row->tipodocument,
            $row->historia,
            $row->cums,
            $row->expediente,
            $row->consecutivo,
            $row->cums_rips,
            $row->codigo,
            $row->tipo_medicamento,
            $row->nombre_generico,
            $row->atc,
            $row->forma,
            $row->concentracion,
            $row->unidad_medicamento,
            $row->numero_unidades,
            $row->regimen,
            $row->paciente,
            $row->primer_apellido,
            $row->segundo_apellido,
            $row->primer_nombre,
            $row->segundo_nombre,
            $row->cuota_moderadora,
            $row->copago,
            $row->numero_orden,
            $row->numero_entrega,
            $row->num_total_entregas,
            $row->fecha_ordenamiento,
            $row->fecha_suministro,
            $row->dx,
            $row->nitips,
            $row->ips,
            $row->autorizacion,
            $row->mipres,
            $row->reporte_entrega_nopbs,
            $row->id_medico,
            $row->numeroIdentificacion,
            $row->medico,
            $row->especialidadmedico,
            $row->precio_unitario,
            $row->valor_total,
            $row->estado,
            $row->centroprod,
            $row->drogueria,
            $row->cajero,
            $row->user_id,
            $row->frecuencia,
            $row->dosis,
            $row->duracion_tratamiento,
            $row->cobertura,
            $row->tipocontrato,
            $row->tipoentrega,
            $row->plan,
            $row->via,
            $row->ciudad
        ];
    }

    public function headings(): array
    {
        return [
            'ID Usuario',
            'Tipo',
            'Facturad',
            'Factura',
            'Tipo Documento',
            'Historia',
            'CUMS',
            'Expediente',
            'Consecutivo',
            'CUMS RIPS',
            'Código',
            'Tipo Medicamento',
            'Nombre Genérico',
            'ATC',
            'Forma',
            'Concentración',
            'Unidad Medicamento',
            'Número Unidades',
            'Régimen',
            'Paciente',
            'Primer Apellido',
            'Segundo Apellido',
            'Primer Nombre',
            'Segundo Nombre',
            'Cuota Moderadora',
            'Copago',
            'Número Orden',
            'Número Entrega',
            'Número Total Entregas',
            'Fecha Ordenamiento',
            'Fecha Suministro',
            'DX',
            'NIT IPS',
            'IPS Nombre',
            'Autorización',
            'MIPRES',
            'Reporte Entrega NOPBS',
            'ID Médico',
            'Número Identificación',
            'Médico',
            'Especialidad Médico',
            'Precio Unitario',
            'Valor Total',
            'Estado',
            'Centro Prod',
            'Droguería',
            'Cajero',
            'User ID',
            'Frecuencia',
            'Dosis',
            'Duración Tratamiento',
            'Cobertura',
            'Tipo Contrato',
            'Tipo Entrega',
            'Plan',
            'Vía',
            'Ciudad'
        ];
    }
}