<?php

namespace App\Imports;

use App\Models\compras\medcol3\MedcolCotizaciones3;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class CotizacionesImport implements ToCollection, WithHeadingRow
{
   
    protected $numRows = 0;
    protected $fechaInicio;
    protected $fechaFin;
    protected $user_id;
    protected $archivo_id;
    

    public function __construct( $fechaInicio, $fechaFin, $user_id, $archivo_id)
    {
       
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->user_id = $user_id;
        $this->archivo_id = $archivo_id;
    }

    public function collection(Collection $rows)
    {
         foreach ($rows as $row) {
           
           
           $consumomes = $row['consumomes'];
           if($consumomes == null || $consumomes == '' )
                $consumomes = 0;
           $consumoyear = $row['consumoyear'];
           
           if($consumoyear == null || $consumoyear == '' )
                $consumoyear = 0;
           
           // Transformar el campo 'cums' si tiene un 0 después del '-'
            $cums = $row['cums'];
            if (strpos($cums, '-') !== false) {
                $parts = explode('-', $cums);
                if (isset($parts[1]) && strlen($parts[1]) === 2 && $parts[1][1] === '0') {
                    $cums = $parts[0] . '-' . $parts[1][0]; // Deja solo la parte antes del '-' y el primer dígito después del '-' si el segundo es '0'
                }
            }

            // Verificar si el registro ya existe
            $existe = MedcolCotizaciones3::where([
                'codigo' => $row['codigo'],
                'fecha_inicio' => $this->fechaInicio,
                'fecha_fin' => $this->fechaFin,
                'codigo_tercero' => $row['proveedor'],
                'estado' => 'activo'
            ])->exists();

            if (!$existe) {
                // Si no existe, insertar el nuevo registro
                DB::table('base_cotizaciones_medcol3')->insert([
                    'codigo' => $row['codigo'],
                    'nombre' => $row['nombre'],
                    'cums' => $row['cums'],
                    'cums_corto' => $cums, // Usa la variable $cums modificada
                    'marca' => $row['marca'],
                    'precio' => $row['precio'],
                    'codigo_tercero' => $row['codigo_tercero'],
                    'nombre_sucursal' => $row['proveedor'],
                    'fecha_inicio' => $this->fechaInicio,
                    'fecha_fin' => $this->fechaFin,
                    'estado' => 'activo',
                    'consumoMes' =>  $consumomes, 
                    'consumoYear' => $consumoyear,
                    'listas_id' =>  $this->archivo_id,
                    'user_id' => $this->user_id,
                    'created_at' => now()
                    
                ]);

                $this->numRows++;
            }
        }
    }

    public function getRowCount(): int
    {
        return $this->numRows;
    }

}