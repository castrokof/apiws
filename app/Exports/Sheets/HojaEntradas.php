<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
// Permiten proteger la hoja de edicion
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class HojaEntradas implements FromArray, WithTitle, WithMapping, WithHeadings, WithStrictNullComparison, WithEvents, WithColumnWidths
{
    protected $movimientos;
    protected $cantidadArticulos = 0;
    private $rowNumber = 1;

    public function __construct($movimientos, $cantidadArticulos) {
        $this->movimientos = $movimientos;
        $this->cantidadArticulos = $cantidadArticulos;
    }

    public function array(): array {
        //return $this->movimientos;
        $datosListos = $this->movimientos;

        $fila = [
            'codigo' => null,
            'cantidad' => null,
            'Valor Total Con Iva' => null,
            'FechaVencimiento' => null,
            'Lote' => null,
            'Invima' => null
        ];

        for($i=0; $i < 100; $i++){
            $datosListos[] = $fila;
        }
        

        return $datosListos;
    }

    public function title(): string {
        return 'Entradas';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // Alterno
            'B' => 90, // Articulo (más ancho para nombres largos)
            'C' => 15, // Presentación
            'D' => 10, // IVA
            'F' => 20, // Total valor con Iva
            'G' => 20, // Valor unitario con Iva
            'H' => 20, // Total Base
            'J' => 30, // Fecha Vencimiento
            'K' => 10, // Lote
            'L' => 20, // Invima
        ];
    }

    public function headings(): array {
        return ['Alterno', 'Articulo', 'Presentación', 'IVA', 'Cantidad', 'Valor Total Con Iva', 'Valor Unitario Con Iva', 'Total Base',
                'Total IVA', 'Fecha Vencimiento (DD/MM/YYYY)', 'Lote', 'Invima'];
    }

    public function map($item): array {
        $this->rowNumber++;
        $f = $this->rowNumber; // Número de fila actual
        $n = $this->cantidadArticulos; // cantidad Total de articulos cargados en hoja Articulos.
        return [
            $item['codigo'],                                     // Col 1: Codigo
            "=VLOOKUP(A$f, 'Articulos'!A2:F$n, 3, 0)",            // Col 2: Articulo
            "=VLOOKUP(A$f, 'Articulos'!A2:F$n, 5, 0)",            // Col 3: Presentación
            "=VLOOKUP(A$f, 'Articulos'!A2:F$n, 6, 0)",   // Col 4: IVA
            $item['cantidad'],                              // Col 5: Cantidad
            $item['Valor Total Con Iva'],                   // Col 6: Valor Total Con Iva
            "=F$f/E$f",                                          // Col 7: Valor Unitario Con Iva
            "=ROUND(F$f/(1+D$f/100),0)",                         // Col 8: Total BASE
            "=F$f-H$f",                                          // col.9: Total Iva
            $item['FechaVencimiento'],                           // col.10: FechaVencimiento
            $item['Lote'],                                       // col.10: Lote
            $item['Invima'],                                     // col.11: Invima
        ];
    }

    /**
     * Registro de eventos para proteger la hoja
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()
                    ->getParent()
                    ->getDefaultStyle()
                    ->getFont()
                    ->setName('Tahoma')
                    ->setSize(8);
                $sheet = $event->sheet->getDelegate();
                
                // Activar la protección de la hoja
                $sheet->getProtection()->setSheet(true);
                
                // Opcional: Establecer una contraseña
                $sheet->getProtection()->setPassword('Medcol2026');
                
                // Opcional: Permitir ciertas acciones (como seleccionar celdas bloqueadas)
                $sheet->getProtection()->setSelectLockedCells(true);
                
                // Habilitamos edicion en celda A,E y F
                $sheet->getStyle('A1:A500')->getProtection()->setLocked(
                    \PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED
                );
                $sheet->getStyle('E1:E500')->getProtection()->setLocked(
                    \PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED
                );
                $sheet->getStyle('F1:F500')->getProtection()->setLocked(
                    \PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED
                );
                $sheet->getStyle('J1:J500')->getProtection()->setLocked(
                    \PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED
                );
                $sheet->getStyle('K1:K500')->getProtection()->setLocked(
                    \PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED
                );
                $sheet->getStyle('L1:L500')->getProtection()->setLocked(
                    \PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED
                );

                //BLOEAMOS ESTAS CELDAS
                $sheet->getStyle('B1:D500')->getProtection()->setLocked(
                    \PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED
                );
                $sheet->getStyle('G1:I500')->getProtection()->setLocked(
                    \PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED
                );
            },
        ];
    }
}