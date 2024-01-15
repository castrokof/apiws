<?php


use App\Models\DiagnosticosCie10;
use Carbon\Carbon;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class cie10 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('diagnosticos')->delete();
        $json = File::get('database/data/cie10.json');
        $data = json_decode($json);
        foreach ($data as $obj) {
            DiagnosticosCie10::create(array(
                'table' => "CIE10",
                'codigo' => $obj->codigo,
                'descripcion' => $obj->nombre,
                'estado' => "ACTIVO"

            ));
        }
    }
}
