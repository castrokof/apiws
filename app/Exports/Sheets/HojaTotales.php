<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
// Permiten proteger la hoja de edicion
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class HojaTotales implements FromArray, WithTitle, WithMapping, WithHeadings, WithEvents, WithColumnWidths
{
    protected $movTotales;
    protected $rowNumber = 1;

    public function __construct($movTotales) {
        $this->movTotales = $movTotales;
    }

    public function array(): array {
        return $this->movTotales;
    }

    public function title(): string {
        return 'Totales';
    }

    public function headings(): array {
        return [
            'Subtotal', 
            'Valor IVA', 
            'Total', 
            '', 
            'Filial Orden', 
            'Año Orden', 
            'Documento Orden', 
            'Número Orden'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // Subtotal
            'B' => 20, // ValorIVA
            'C' => 20, // Total
            'D' => 20, // IVA
            'F' => 10, // 
            'G' => 25, // Filial Orden
            'H' => 20, // Año Orden
            'J' => 25, // Documento Orden
            'K' => 25, // Número Orden
        ];
    }

    public function map($item): array {
        $this->rowNumber++;
        $f = $this->rowNumber; // Normalmente será la fila 2

        return [
            "=SUMIF('Entradas'!H2:H10001, \">0\")", // Col 1: Subtotal (Suma Base en Entradas)
            "=SUMIF('Entradas'!I2:I10001, \">0\")", // Col 2: ValorIVA (Suma IVA en Entradas)
            "=A$f+B$f",                       // Col 3: Total (Suma de celdas locales)
            '',                               // Col 4: Espacio
            $item['filial'] ?? '',            // Col 5: Filial Orden
            $item['anio'] ?? '',              // Col 6: Año Orden
            $item['documento'] ?? '',         // Col 7: Documento Orden
            $item['numero'] ?? '',            // Col 8: Número Orden
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
            },
        ];
    }
}