<?php

namespace App\Imports;

use App\Models\compras\Molecula;
use App\Models\compras\MoleculaProveedorCodigo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProveedoresImport implements ToCollection, WithHeadingRow
{
    public function headingRow(): int
    {
        // Encabezados en la fila 1: CodigoRfast | NombreProveedor | CodigoProveedor
        return 1;
    }

    public function collection(Collection $rows)
    {
        $errors = [];
        $line   = 2; // primera fila de datos (después del header)

        foreach ($rows as $row) {
            // Keys que entrega WithHeadingRow: las pasa a snake_case y minúsculas
            $codigo     = $this->clean($row['codigorfast']     ?? '');
            $provNombre = $this->clean($row['nombreproveedor'] ?? '');
            $provCodigo = $this->clean($row['codigoproveedor'] ?? '');

            // 1) Fila totalmente vacía -> omitir silenciosamente
            if ($codigo === '' && $provNombre === '' && $provCodigo === '') {
                $line++;
                continue;
            }

            // 2) Fila con datos incompletos -> acumular error
            if ($codigo === '' || $provNombre === '' || $provCodigo === '') {
                $errors[] = "Fila {$line}: datos incompletos (revise columnas requeridas).";
                $line++;
                continue;
            }

            // 3) Buscar molécula por código (ajusta si tu modelo apunta a otra tabla/columna)
            $mol = Molecula::where('codigo', $codigo)->first();
            if (!$mol) {
                $errors[] = "Fila {$line}: la molécula «{$codigo}» no existe.";
                $line++;
                continue;
            }

            // 4) Evitar duplicados exactos
            $exists = MoleculaProveedorCodigo::where('molecule_id', $mol->id)
                ->where('nombre_proveedor', $provNombre)
                ->where('codigo_proveedor', $provCodigo)
                ->exists();

            if ($exists) {
                // Ya existe: lo omitimos sin marcar error
                $line++;
                continue;
            }

            // 5) Crear relación
            MoleculaProveedorCodigo::create([
                'molecule_id'      => $mol->id,
                'nombre_proveedor' => $provNombre,
                'codigo_proveedor' => $provCodigo,
                'activo'           => true,
            ]);

            $line++;
        }

        // Si hubo errores, abortar import con resumen
        if (!empty($errors)) {
            throw new \Exception(implode(' | ', $errors));
        }
    }

    private function clean($v): string
    {
        $s = is_string($v) ? $v : (string)$v;
        $s = preg_replace('/\s+/u', ' ', $s ?? ''); // colapsa espacios
        return trim($s ?? '');
    }
}
