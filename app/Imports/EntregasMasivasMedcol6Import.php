<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class EntregasMasivasMedcol6Import implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // La lógica de procesamiento se maneja en el controlador
        return $rows;
    }
}
