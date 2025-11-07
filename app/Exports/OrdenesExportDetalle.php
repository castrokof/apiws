<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Medcol6\OrdenCompraMedcol6;
use App\Models\compras\medcol3\Medcolcompras3;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class OrdenesExportDetalle implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected Request $r;

    // para colorear filas por estado
    protected array $filaEstado = []; // [rowNumber => 'PENDIENTE'|'COMPLETA'|'ANULADA']
    protected int $rowPtr = 2;        // data arranca en la fila 2 (fila 1 = encabezados)

    public function __construct(Request $r)
    {
        $this->r = $r;
    }

    public function headings(): array
    {
        return [
            'N. Orden',
            'Codigo Molecula',
            'Descripcion',
            'Presentacion',
            'Cantidad',
            'Faltantes',
            'V Unitario',
            'V Total Pactado',
            'Estado',
        ];
    }

    public function collection(): Collection
    {
        // === Filtros igual que en tu index ===
        $q = OrdenCompraMedcol6::query();

        $codFarmacia = $this->r->input('cod_farmacia');
        $estado      = $this->r->input('estado');
        $userCreate  = $this->r->input('userCreate', $this->r->input('user_create'));
        $orden       = $this->r->input('orden_de_compra');
        $proveedor   = $this->r->input('proveedor');
        $desde       = $this->r->input('fecha_desde');
        $hasta       = $this->r->input('fecha_hasta');
        $clasi       = $this->r->input('clasi_orden', $this->r->input('ClasiOrden'));

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

        $ordenes = $q->orderBy('fecha','desc')->get();

        if ($ordenes->isEmpty()) {
            return collect(); // nada que exportar
        }

        // Traer todos los detalles de una sola: evita N+1
        $nums = $ordenes->pluck('num_orden_compra')->unique()->values();
        $detalles = Medcolcompras3::whereIn('numeroOrden', $nums)->get()->groupBy('numeroOrden');

        $rows = [];

        foreach ($ordenes as $o) {
            $mols = $detalles->get($o->num_orden_compra, collect());

            // Si una orden no tiene detalles, puedes omitirla o poner una fila vacía.
            if ($mols->isEmpty()) {
                continue;
            }

            foreach ($mols as $m) {
                $faltantes = (float)($m->cantidad ?? 0) - (float)($m->cantidadEntregada ?? 0);
                $estadoDet = strtoupper($m->estado ?? '');

                // Registrar estado para color de la fila actual
                $this->filaEstado[$this->rowPtr] = $estadoDet;
                $this->rowPtr++;

                $rows[] = [
                    $o->orden_de_compra,
                    $m->codigo,
                    $m->nombre,
                    $m->presentacion,
                    $m->cantidad,
                    $faltantes,
                    $m->precio,
                    $m->subtotal,
                    $estadoDet,
                ];
            }
        }

        return collect($rows);
    }

    public function styles(Worksheet $sheet)
    {
        // Encabezado (fila 1)
        $sheet->getStyle('A1:I1')->getFill()->setFillType(Fill::FILL_SOLID)
              ->getStartColor()->setRGB('BDD7EE'); // azul claro

        // Colores por estado
        foreach ($this->filaEstado as $row => $estado) {
            $rgb = null;
            switch ($estado) {
                case 'PENDIENTE': $rgb = 'FFF2CC'; break; // amarillo suave
                case 'COMPLETA':  $rgb = 'C6EFCE'; break; // verde suave
                case 'ANULADA':   $rgb = 'F8CBAD'; break; // rojo suave
            }
            if ($rgb) {
                $sheet->getStyle("A{$row}:I{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB($rgb);
            }
        }

        return [];
    }
}
