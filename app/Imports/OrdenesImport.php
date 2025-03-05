<?php

namespace App\Imports;

use App\Models\compras\medcol3\Medcolcompras3;
use App\Models\compras\medcol3\MedcolCotizaciones3;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class OrdenesImport implements ToCollection, WithHeadingRow
{
   
    protected $proveedor;
    protected $data;

    public function __construct($proveedor)
    {
        $this->proveedor = $proveedor;
        
        
        $this->data = collect();
    }

   public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                $precioRecord = MedcolCotizaciones3::where([
                    ['codigo', '=', $row['codigo']],
                    ['codigo_tercero', '=', $this->proveedor],
                    ['estado', '=', 'activo']
                ])->select('precio')->first();
                
            
    
                $precio = $precioRecord ? $precioRecord->precio : 0;
                $subtotal = $precio * $row['cantidad'];
    
                $this->data->push([
                    'codigo' => $row['codigo'],
                    'nombre' => $row['nombre'],
                    'cums' => $row['cums'],
                    'marca' => $row['marca'],
                    'cantidad' => $row['cantidad'],
                    'precio' => $precio,
                    'sutotal' => $subtotal
                ]);
            } catch (\Exception $e) {
                Log::error('Error processing row: ' . $e->getMessage());
            }
        }
    }

    public function getData()
    {
        
       
        return $this->data;
    }

}