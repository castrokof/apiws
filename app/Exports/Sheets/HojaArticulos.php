<?php
namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
// Permiten proteger la hoja de edicion
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithMapping;

class HojaArticulos implements FromArray, WithTitle, WithHeadings, WithEvents, WithColumnWidths, WithColumnFormatting, WithMapping
{
    protected $articulos;

    public function __construct($articulos) {
        $this->articulos = $articulos;
    }

    public function array(): array {
        return $this->articulos;
    }

    public function title(): string {
        return 'Articulos'; // Nombre exacto para la fórmula
    }

    public function headings(): array {
        return ['Alterno', 'Código', 'Artículo', 'Marca', 'Presentacion', 'IVA'];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // Alterno
            'B' => 20, // Codigo
            'C' => 90, // Articulo
            'D' => 20, // Marca
            'E' => 20, // Presentacion
            'F' => 20, // Iva
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // Columna Alterno
            'B' => NumberFormat::FORMAT_TEXT, // Columna Código
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

    public function map($item): array
    {
        return [
            // IMPORTANTE: No concatenamos nada, solo aseguramos que sea String
            (string)$item['Alterno'], 
            (string)$item['Codigo'],
            $item['Articulo'],
            $item['Marca'],
            $item['Presentacion'],
            $item['Iva'],
        ];
    }
}
