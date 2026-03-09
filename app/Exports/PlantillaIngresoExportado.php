<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\HojaArticulos;
use App\Exports\Sheets\HojaEntradas;
use App\Exports\Sheets\HojaTotales;

use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;


class PlantillaIngresoExportado extends DefaultValueBinder implements WithMultipleSheets, WithCustomValueBinder
{
    protected $articulos;
    protected $cantidadArticulos;
    protected $movimientos;
    protected $movTotales;

    // Recibimos todos los datos necesarios en el constructor
    public function __construct(array $articulos, $cantidadArticulos, array $movimientos, array $movTotales)
    {
        $this->articulos = $articulos;
        $this->cantidadArticulos = $cantidadArticulos;
        $this->movimientos = $movimientos;
        $this->movTotales = $movTotales;
    }

    public function bindValue(Cell $cell, $value)
    {
        // Si el valor tiene más de 11 dígitos y es numérico, forzamos TIPO STRING
        if (is_numeric($value) && strlen((string)$value) > 11) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }

        // Para todo lo demás, usar el comportamiento normal
        return parent::bindValue($cell, $value);
    }

    /**
     * Definimos el orden y las instancias de las hojas.
     * Es vital el orden para que las fórmulas no fallen al abrir el archivo.
     */
    public function sheets(): array
    {
        return [
            'Entradas'  => new HojaEntradas($this->movimientos, $this->cantidadArticulos), // Hoja 1
            'Totales'   => new HojaTotales($this->movTotales),  // Hoja 2
            'Articulos' => new HojaArticulos($this->articulos), // Hoja 3
            
        ];
    }
}