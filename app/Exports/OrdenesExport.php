<?php

// app/Exports/OrdenesExport.php
namespace App\Exports;

use App\Models\Medcol6\OrdenCompraMedcol6; // ajusta namespace
use Illuminate\Http\Request;
use App\User;
use App\Models\compras\medcol3\Medcolcompras3;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrdenesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $r;

    public function __construct(Request $r) {
        $this->r = $r;
    }

    public function query()
    {
        $q = OrdenCompraMedcol6::query();

        // === Leer ambos estilos de nombres ===
        $codFarmacia = $this->r->input('cod_farmacia');
        $estado      = $this->r->input('estado');
        $userCreate  = $this->r->input('userCreate', $this->r->input('user_create'));
        $orden       = $this->r->input('orden_de_compra');
        $proveedor   = $this->r->input('proveedor');
        $desde       = $this->r->input('fecha_desde');
        $hasta       = $this->r->input('fecha_hasta');
        $clasi       = $this->r->input('clasi_orden', $this->r->input('ClasiOrden')); // tu columna es "ClasiOrden"

        if ($codFarmacia) $q->where('cod_farmacia', 'like', "%{$codFarmacia}%");
        if ($estado)      $q->where('estado', 'like', "%{$estado}%");
        if ($userCreate)  $q->where('user_create', 'like', "%{$userCreate}%");
        if ($orden)       $q->where('orden_de_compra', 'like', "%{$orden}%");
        if ($proveedor)   $q->where('proveedor', 'like', "%{$proveedor}%");
        if ($clasi)       $q->where('ClasiOrden', 'like', "%{$clasi}%");

        if ($desde && $hasta) {
            $q->whereBetween('fecha', [$desde, $hasta]);
        } elseif ($desde) {
            $q->whereDate('fecha', '>=', $desde);
        } elseif ($hasta) {
            $q->whereDate('fecha', '<=', $hasta);
        }

        return $q->orderBy('fecha', 'desc');
    }

    public function headings(): array
    {
        return ['No. Orden de compra', 'Fecha solicitud', 'Programa', 'Generada por', 'Proveedor', 'Valor Orden de Compra', 'Observaciones'];
    }

    public function map($o): array
    {
        $Usuario =  User::where('id',$o->user_create)->first();
        $Molecula =  Medcolcompras3::where('numeroOrden',$o->num_orden_compra)->first();
        return [
            $o->orden_de_compra,
            $o->fecha,
            $Molecula->documentoOrden ?? '',
            $Usuario->name ?? '',
            $o->proveedor,
            number_format((float)$o->total, 2, ',', '.'),
            $o->observaciones,
        ];
    }
}
