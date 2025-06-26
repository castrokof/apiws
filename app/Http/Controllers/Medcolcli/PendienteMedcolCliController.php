<?php

namespace App\Http\Controllers\Medcolcli;


use App\Http\Controllers\Controller;

use App\Models\MedcolCli\PendienteCliMedcol;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;

class PendienteMedcolCliController extends Controller
{


    public $var1 = null;
    public $var2 = null;
    public $ip = null;
    public $res = false;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('menu.Medcolcli.indexInforme');
    }
    
       public function index1(Request $request)
        {
            
                $fechaAi=now()->toDateString()." 00:00:01";
                $fechaAf=now()->toDateString()." 23:59:59";
                
               
    
            if ($request->ajax()) {
                
              if($request->fechaini != '' && $request->fechafin != '' ){  
                
                $fechaini = new Carbon($request->fechaini);
                $fechaini = $fechaini->toDateString();
    
                $fechafin = new Carbon($request->fechafin);
                $fechafin = $fechafin->toDateString();
                
                $pendientesapi = PendienteCliMedcol::whereIn('estado', ['PENDIENTE','ENTREGADO', 'VENCIDO'])
                   ->whereNotIn('centroproduccion', ['EHU1'])
                   ->whereBetween('fecha_factura', [$fechaini.' 00:00:00',$fechafin.' 23:59:59']);
                   
            
                $pendientesapi->where('fecha_factura', '>', '2023-08-31 00:00:00')->get();  
                
                          
    
               return DataTables()->of($pendientesapi)
                ->make(true);
              }else{
                  
                  $pendientesapitoday = PendienteCliMedcol::whereIn('estado', ['PENDIENTE','ENTREGADO', 'VENCIDO'])
                    ->whereBetween('fecha_factura', [$fechaAi,$fechaAf])
                    ->whereNotIn('centroproduccion', ['EHU1'])
                    ->orWhere('estado', NULL);
                    
                  $pendientesapitoday->where([
                    ['fecha_factura', '>=', '2023-08-01'.' 00:00:00']
                     ])->get();
               
                // Calcula la diferencia en días
                //$pendientesapitoday = $pendientesapitoday->addSelect(['diferencia_dias' => DB::raw('DATEDIFF(fecha_entrega, fecha_factura)')]);  
    
               return DataTables()->of($pendientesapitoday)
                ->make(true);
              }
                  
              }
            }
    
        
    


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function informes()
    {

        $pendientes =  PendientesApi::where('estado', 'PENDIENTE')->count();
        $entregados =  PendientesApi::where('estado', 'ENTREGADO')->count();
        $tramitados =  PendientesApi::where('estado', 'TRAMITADO')->count();
        $agotados =  PendientesApi::where('estado', 'DESABASTECIDO')->count();
        $anulados =  PendientesApi::where('estado', 'ANULADO')->count();

        return response()->json(['pendientes' => $pendientes, 'entregados' => $entregados, 'tramitados' => $tramitados, 'agotados' => $agotados, 'anulados' => $anulados]);
    }


   
    
     public function informepedientes()
    {

        if (request()->ajax()) {
            $data = DB::table('pendientesapi')
                ->where([['estado', '=', 'PENDIENTE']])
                ->select('nombre')
                ->selectRaw('SUM(cantord) as cantord')
                ->groupBy('nombre')
                ->get();

            return DataTables()->of($data)->make(true);
        }
        //return view('menu.usuario.indexAnalista');
    }
}
