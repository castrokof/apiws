<?php

namespace App\Http\Controllers\Medcolcli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\MedcolCli\DispensadoCliMedcol;
use App\Models\Medcol6\DispensadoApiMedcol6;

use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;


class DispensadoMedcolCliController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index()
    {

        return view('menu.Medcolcli.indexInformed');
    }
    
       public function index1(Request $request)
        {
            $fechaAi = now()->toDateString() . " 00:00:01";
            $fechaAf = now()->toDateString() . " 23:59:59";
        
            if ($request->ajax()) {
                $dispensadoapi = DispensadoCliMedcol::query();
        
                if ($request->fechaini != '' && $request->fechafin != '') {
                    $fechaini = new Carbon($request->fechaini);
                    $fechaini = $fechaini->toDateString();
        
                    $fechafin = new Carbon($request->fechafin);
                    $fechafin = $fechafin->toDateString();
        
                    $dispensadoapi->whereBetween('fecha_suministro', [$fechaini . ' 00:00:00', $fechafin . ' 23:59:59']);
                    $dispensadoapi->whereIn('estado', ['DISPENSADO', 'REVISADO']);
                }
        
                if ($request->historia != '') {
                    $historia = preg_replace("/\s+/", "", trim($request->historia));
                    $historia = explode(',', $historia);
        
                    $pc = count($historia);
                    for ($i = 0; $i < $pc; $i++) {
                        $dispensadoapi->whereIn('historia', $historia);
                    }
                }
        
                if ($request->fechaini == '' && $request->fechafin == '' && $request->historia == '') {
                    $dispensadoapi->whereBetween('fecha_suministro', [$fechaAi, $fechaAf]);
                    $dispensadoapi->whereIn('estado', ['REVISADO', 'DISPENSADO']);
                    $dispensadoapi->where([
                        ['fecha_suministro', '>=', '2023-11-01' . ' 00:00:00']
                    ]);
                }
        
                // **Excluir los códigos 1010, 1011 y 1012**
                $dispensadoapi->whereNotIn('codigo', ['1010', '1011', '1012']);
        
                return DataTables()->of($dispensadoapi->get())->make(true);
            }
        }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
