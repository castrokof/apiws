<?php

namespace App\Exports;

use App\Paciente;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PacientesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $search;

    public function __construct(?string $search = null)
    {
        $this->search = $search;
    }

    public function query()
    {
        $q = Paciente::select([
            'tipdocum', 'historia', 'paciente', 'direccion',
            'telefono', 'regimen', 'nivel', 'edad', 'sexo',
            'pqrs', 'estado', 'programa', 'alto_costo',
        ]);

        if ($this->search) {
            $s = $this->search;
            $q->where(function ($query) use ($s) {
                $query->where('historia',  'like', "%{$s}%")
                      ->orWhere('paciente', 'like', "%{$s}%")
                      ->orWhere('tipdocum', 'like', "%{$s}%")
                      ->orWhere('regimen',  'like', "%{$s}%")
                      ->orWhere('programa', 'like', "%{$s}%");
            });
        }

        return $q->orderBy('paciente');
    }

    public function headings(): array
    {
        return [
            'Tip. Doc.', 'Historia', 'Paciente', 'Dirección',
            'Teléfono', 'Régimen', 'Nivel', 'Edad', 'Sexo',
            'PQRS', 'Estado', 'Programa', 'Alto Costo',
        ];
    }

    public function map($row): array
    {
        return [
            $row->tipdocum,
            $row->historia,
            $row->paciente,
            $row->direccion,
            $row->telefono,
            $row->regimen,
            $row->nivel,
            $row->edad,
            $row->sexo,
            $row->pqrs,
            $row->estado,
            $row->programa ?? '',
            $row->alto_costo,
        ];
    }
}
