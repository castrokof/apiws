<?php

namespace App\Http\Controllers\Medcol6;

use App\Http\Controllers\Controller;
use App\Models\Medcol6\PendienteApiMedcol6;
use App\Models\Medcol6\EntregadosApiMedcol6;
use App\Models\Medcol6\ObservacionesApiMedcol6;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;

class PendienteApiMedcol6Controller extends Controller
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
    public function index(Request $request)
    {

             return view('menu.Medcol6.indexAnalista');
    }
    
      public function index1(Request $request)
        {
            // Definir las fechas por defecto
            $fechaAi = now()->startOfDay();
            $fechaAf = now()->endOfDay();
        
            // Obtener la droguería del usuario autenticado
            $drogueria = '';
            switch (Auth::user()->drogueria) {
                case "1":
                    $drogueria = ''; // Todos
                    break;
                case "2":
                    $drogueria = 'SM01';
                    break;
                case "3":
                    $drogueria = 'DLR1';
                    break;
                case "4":
                    $drogueria = 'PAC';
                    break;
                case "5":
                    $drogueria = 'EHU1';
                    break;
                case "6":
                    $drogueria = 'BIO1';
                    break;
                case "8":
                    $drogueria = 'EM01';
                    break;
                case "9":
                    $drogueria = 'BPDT';
                    break;
                case "10":
                    $drogueria = 'DPA1';
                    break;
                case "11":
                    $drogueria = 'EVSM';
                    break;
                case "12":
                    $drogueria = 'EVEN';
                    break;
                case "13":
                    $drogueria = 'FRJA';
                    break;
            }
        
            if ($request->ajax()) {
                // Iniciar la consulta
                $query = PendienteApiMedcol6::query();
        
                // Filtrar por estado
                $query->where(function($q) {
                    $q->where('estado', 'PENDIENTE')
                      ->orWhere('estado', NULL);
                });
        
                // Filtrar por centro de producción si no es '1'
                if (Auth::user()->drogueria !== '1') {
                    
                   if( Auth::user()->drogueria == '3')
                    $query->where('centroproduccion', [$drogueria, 'DPA1']);
                    else
                    $query->where('centroproduccion',$drogueria);
                    
                }
        
                // Filtrar por fechas si están presentes
                if (!empty($request->fechaini) && !empty($request->fechafin)) {
                    $fechaini = Carbon::parse($request->fechaini)->startOfDay();
                    $fechafin = Carbon::parse($request->fechafin)->endOfDay();
                    $query->whereBetween('fecha_factura', [$fechaini, $fechafin]);
                } else {
                    // Si no se proporcionan fechas, usar las fechas por defecto
                    $query->whereBetween('fecha_factura', [$fechaAi, $fechaAf]);
                }
        
                // Filtrar por contrato si está presente
                if (!empty($request->contrato)) {
                    $query->where('centroproduccion', $request->contrato);
                }
        
                // Obtener los resultados
                $pendiente_api_medcol6 = $query->orderBy('id')->get();
        
                // Retornar los resultados para DataTables
                return DataTables()->of($pendiente_api_medcol6)
                    ->addColumn('action', function ($pendiente) {
                        $button = '<button type="button" name="show_detail" id="' . $pendiente->id . '" class="show_detail btn btn-app bg-secondary tooltipsC" title="Detalle">
                            <span class="badge bg-teal">Detalle</span><i class="fas fa-prescription-bottle-alt"></i></button>';
                        $button2 = '<button type="button" name="edit_pendiente" id="' . $pendiente->id . '" class="edit_pendiente btn btn-app bg-info tooltipsC" title="Editar">
                            <span class="badge bg-teal">Editar</span><i class="fas fa-pencil-alt"></i></button>';
        
                        return $button . ' ' . $button2;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        
            // Retornar la vista si no es una solicitud AJAX
            return view('menu.Medcol6.indexAnalista');
        }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createapendientespi(Request $request)
    {
        $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = 'colMed2023**';
        $usuario = Auth::user()->email;

        try {

            $response = Http::post("http://hed08pf9dxt.sn.mynetname.net:8004/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];
           

            $responsefacturas = Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/pendientesapi");

            $facturassapi = $responsefacturas->json()['data'];
            
            

           
            $contador = 0;
            $pendientes = [];

            foreach ($facturassapi as $factura) {
                $existe = PendienteApiMedcol6::where([['factura', $factura['factura']],['documento', $factura['documento']]])->count();
                    
                

                if ($existe == 0 || $existe == '') {
                        
                    $pendientes[] = [
                        'Tipodocum' => trim($factura['Tipodocum']),
                        'cantdpx' => trim($factura['cantdpx']),
                        'cantord' => trim($factura['cantord']),
                        'fecha_factura' => trim($factura['fecha_factura']),
                        'fecha' => trim($factura['fecha']),
                        'historia' => trim($factura['historia']),
                        'apellido1' => trim($factura['apellido1']),
                        'apellido2' => trim($factura['apellido2']),
                        'nombre1' => trim($factura['nombre1']),
                        'nombre2' => trim($factura['nombre2']),
                        'cantedad' => trim($factura['cantedad']),
                        'direcres' => trim($factura['direcres']),
                        'telefres' => trim($factura['telefres']),
                        'documento' => trim($factura['documento']),
                        'factura' => trim($factura['factura']),
                        'agrupador' => trim($factura['agrupador']),
                        'codigo' => trim($factura['codigo']),
                        'nombre' => trim($factura['nombre']),
                        'cums' => trim($factura['cums']),
                        'cantidad' => trim($factura['cantidad']),
                        'cajero' => trim($factura['cajero']),
                        'estado' => 'PENDIENTE',
                        'orden_externa' => trim($factura['ORDEN_EXTERNA']),
                        'centroproduccion' => trim($factura['CENTROPRODUCCION']),
                        'observaciones' => trim($factura['observaciones'])
                    ];
                    
                       
                    $contador++;
                    
                   
                    Log::info('Desde la web syncapi '.$contador . ' Linea creada y ' . trim($factura['documento']).trim($factura['factura']) . ' Medicamento - '. trim($factura['cums']).'=>'.trim($factura['nombre']).'Documento: '.trim($factura['historia']));
                }else{
                    
                    Log::info('Desde la web syncapi '.$contador . ' Linea ya estaba en BD ' . trim($factura['documento']).trim($factura['factura']) . ' Medicamento - '. trim($factura['cums']).'=>'.trim($factura['nombre']).'Documento: '.trim($factura['historia']));
                }
            }
           
            if (!empty($pendientes)) {
                
                
                PendienteApiMedcol6::insert($pendientes);
                
               
                
            }
            
            Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/closeallacceso");

            $var = $this->createentregadospi(null);
            
            Log::info('Desde la web syncapi '.$contador . ' Lineas creadas y ' . $var . ' Lineas entregadas'.' Usuario: '.$usuario);
            
            return response()->json([
                ['respuesta' => $contador . ' Lineas creadas y ' . $var . ' Lineas entregadas', 'titulo' => 'Mixed lineas', 'icon' => 'success', 'position' => 'bottom-left']
            ]);

        } catch (\Exception $e) {


            $response = Http::post("http://192.168.66.91:8004/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];

            $responsefacturas = Http::withToken($token)->get("http://192.168.66.91:8004/api/pendientesapi");

            $facturassapi = $responsefacturas->json()['data'];
            
            //dd($facturassapi);

            $contador = 0;
            $pendientes = [];

            foreach ($facturassapi as $factura) {
               $existe = PendienteApiMedcol6::where([['factura', $factura['factura']],['documento', $factura['documento']]])->count();

                if ($existe == 0 || $existe == '') {
                    $pendientes[] = [
                        'Tipodocum' => trim($factura['Tipodocum']),
                        'cantdpx' => trim($factura['cantdpx']),
                        'cantord' => trim($factura['cantord']),
                        'fecha_factura' => trim($factura['fecha_factura']),
                        'fecha' => trim($factura['fecha']),
                        'historia' => trim($factura['historia']),
                        'apellido1' => trim($factura['apellido1']),
                        'apellido2' => trim($factura['apellido2']),
                        'nombre1' => trim($factura['nombre1']),
                        'nombre2' => trim($factura['nombre2']),
                        'cantedad' => trim($factura['cantedad']),
                        'direcres' => trim($factura['direcres']),
                        'telefres' => trim($factura['telefres']),
                        'documento' => trim($factura['documento']),
                        'factura' => trim($factura['factura']),
                        'agrupador' => trim($factura['agrupador']),
                        'codigo' => trim($factura['codigo']),
                        'nombre' => trim($factura['nombre']),
                        'cums' => trim($factura['cums']),
                        'cantidad' => trim($factura['cantidad']),
                        'cajero' => trim($factura['cajero']),
                        'estado' => 'PENDIENTE',
                        'orden_externa' => trim($factura['ORDEN_EXTERNA']),
                        'centroproduccion' => trim($factura['CENTROPRODUCCION']),
                        'observaciones' => trim($factura['observaciones'])
                    ];

                    $contador++;
                }
            }

            if (!empty($pendientes)) {
                PendienteApiMedcol6::insert($pendientes);
            }

            Http::withToken($token)->get("http://192.168.66.91:8004/api/closeallacceso");

            $var = $this->createentregadospilocal(null);

            
            
            if($e->getMessage()){
                
            
             Log::error('Desde la web syncapi '.$e->getMessage().' Usuario: '.$usuario);    
            
                
            return response()->json([
                ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
            ]);
            
            }else{
                
             Log::info('Desde la web syncapi '.$contador . ' Lineas creadas y ' . $var . ' Lineas entregadas'.' Usuario: '.$usuario);
                
            return response()->json([
                 ['respuesta' => $contador . ' Lineas creadas y ' . $var . ' Lineas entregadas', 'titulo' => 'Usando Api Local', 'icon' => 'error', 'position' => 'bottom-left']
             ]);
             
            }
        }



    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function porentregar(Request $request)
    {
         $i = Auth::user()->drogueria;
        
        switch ($i) {
                    case "1":
                        $drogueria = '';
                        break;
                    case "2":
                        $drogueria = 'SALUD';
                        break;
                    case "3":
                       $drogueria = 'DOLOR';
                        break;
                    case "4":
                        $drogueria = 'PAC';
                        break;
                    case "5":
                        $drogueria = 'EHU1';
                        break;
                    case "6":
                         $drogueria = 'BIO1';
                        break;
                     case "8":
                        $drogueria = 'EM01';
                        break;    
                     case "9":
                        $drogueria = 'FSIO';
                        break;
                    case "10":
                        $drogueria = 'FSOS';
                        break;
                    case "11":
                        $drogueria = 'FSAU';
                        break;
                    case "12":
                        $drogueria = 'EVSO';
                        break; 
                    case "13":
                        $drogueria = 'FRJA';
                        break;         
                }
                
        if ($request->ajax()) {
            
             if(Auth::user()->drogueria == '1'){
                 $pendiente_api_medcol6 = PendienteApiMedcol6::where([['estado', 'TRAMITADO']])
                ->orderBy('id')
                ->get();
             }else{
                 $pendiente_api_medcol6 = PendienteApiMedcol6::where([['estado', 'TRAMITADO'],['centroproduccion',$drogueria]])
                ->orderBy('id')
                ->get();
             }
            
            
            
            
            

            return DataTables()->of($pendiente_api_medcol6)
                ->addColumn('action', function ($pendiente) {
                    $button = '<button type="button" name="show_detail" id="' . $pendiente->id . '
                    " class="show_detail btn btn-app bg-secondary tooltipsC" title="Detalle"  >
                    <span class="badge bg-teal">Detalle</span><i class="fas fa-prescription-bottle-alt"></i> </button>';
                    $button2 = '<button type="button" name="edit_pendiente" id="' . $pendiente->id . '
                    " class="edit_pendiente btn btn-app bg-info tooltipsC" title="Editar"  >
                    <span class="badge bg-teal">Editar</span><i class="fas fa-pencil-alt"></i> </button>';

                    return $button . ' ' . $button2;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('menu.Medcol6.indexAnalista');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Medcol6\PendienteApiMedcol6  $pendienteApiMedcol6
     * @return \Illuminate\Http\Response
     */
    public function entregados(Request $request)
    {
         // Definir las fechas por defecto
            $fechaAi = now()->startOfDay();
            $fechaAf = now()->endOfDay();
        
            // Obtener la droguería del usuario autenticado
            $drogueria = '';
            switch (Auth::user()->drogueria) {
                case "1":
                    $drogueria = ''; // Todos
                    break;
                case "2":
                    $drogueria = 'SALUD';
                    break;
                case "3":
                    $drogueria = 'DOLOR';
                    break;
                case "4":
                    $drogueria = 'PAC';
                    break;
                case "5":
                    $drogueria = 'EHU1';
                    break;
                case "6":
                    $drogueria = 'BIO1';
                    break;
                case "8":
                    $drogueria = 'EM01';
                    break;
                case "9":
                    $drogueria = 'FSIO';
                    break;
                case "10":
                    $drogueria = 'FSOS';
                    break;
                case "11":
                    $drogueria = 'FSAU';
                    break;
                case "12":
                    $drogueria = 'EVSO';
                    break;
                case "13":
                    $drogueria = 'FRJA';
                    break;
            }
        
            if ($request->ajax()) {
                // Iniciar la consulta
                $query = PendienteApiMedcol6::query();
        
                // Filtrar por estado
                $query->where(function($q) {
                    $q->where('estado', 'ENTREGADO')
                      ->orWhere('estado', NULL);
                });
        
                // Filtrar por centro de producción si no es '1'
                if (Auth::user()->drogueria !== '1') {
                    $query->where('centroproduccion', $drogueria);
                }
        
                // Filtrar por fechas si están presentes
                if (!empty($request->fechaini) && !empty($request->fechafin)) {
                    $fechaini = Carbon::parse($request->fechaini)->startOfDay();
                    $fechafin = Carbon::parse($request->fechafin)->endOfDay();
                    $query->whereBetween('fecha_factura', [$fechaini, $fechafin]);
                } else {
                    // Si no se proporcionan fechas, usar las fechas por defecto
                    $query->whereBetween('fecha_factura', [$fechaAi, $fechaAf]);
                }
        
                // Filtrar por contrato si está presente
                if (!empty($request->contrato)) {
                    $query->where('centroproduccion', $request->contrato);
                }
        
                // Obtener los resultados
                $pendiente_api_medcol6 = $query->orderBy('id')->get();
        
                // Retornar los resultados para DataTables
                return DataTables()->of($pendiente_api_medcol6)
                    ->addColumn('action', function ($pendiente) {
                        $button = '<button type="button" name="show_detail" id="' . $pendiente->id . '" class="show_detail btn btn-app bg-secondary tooltipsC" title="Detalle">
                            <span class="badge bg-teal">Detalle</span><i class="fas fa-prescription-bottle-alt"></i></button>';
                        $button2 = '<button type="button" name="edit_pendiente" id="' . $pendiente->id . '" class="edit_pendiente btn btn-app bg-info tooltipsC" title="Editar">
                            <span class="badge bg-teal">Editar</span><i class="fas fa-pencil-alt"></i></button>';
        
                        return $button . ' ' . $button2;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        
            // Retornar la vista si no es una solicitud AJAX
            return view('menu.Medcol6.indexAnalista');
        }
        
        

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Medcol6\PendienteApiMedcol6  $pendienteApiMedcol6
     * @return \Illuminate\Http\Response
     */
    public function getDesabastecidos(Request $request)
    {
         $i = Auth::user()->drogueria;
        
        switch ($i) {
                    case "1":
                        $drogueria = '';
                        break;
                    case "2":
                        $drogueria = 'SALUD';
                        break;
                    case "3":
                       $drogueria = 'DOLOR';
                        break;
                    case "4":
                        $drogueria = 'PAC';
                        break;
                    case "5":
                        $drogueria = 'EHU1';
                        break;
                    case "6":
                         $drogueria = 'BIO1';
                        break;
                     case "8":
                        $drogueria = 'EM01';
                        break;    
                     case "9":
                        $drogueria = 'FSIO';
                        break;
                    case "10":
                        $drogueria = 'FSOS';
                        break;
                    case "11":
                        $drogueria = 'FSAU';
                        break;
                    case "12":
                        $drogueria = 'EVSO';
                        break; 
                    case "13":
                        $drogueria = 'FRJA';
                        break;         
                }
        if ($request->ajax()) {
            
            if(Auth::user()->drogueria == '1'){
                
                $pendiente_api_medcol6 = PendienteApiMedcol6::where([['estado', 'DESABASTECIDO']])
                ->orderBy('id')
                ->get();
                
            }else{
                
                
                $pendiente_api_medcol6 = PendienteApiMedcol6::where([['estado', 'DESABASTECIDO'],['centroproduccion',$drogueria]])
                ->orderBy('id')
                ->get();
            }
            
            

            return DataTables()->of($pendiente_api_medcol6)
                ->addColumn('action', function ($pendiente) {
                    $button = '<button type="button" name="show_detail" id="' . $pendiente->id . '
                    " class="show_detail btn btn-app bg-secondary tooltipsC" title="Detalle"  >
                    <span class="badge bg-teal">Detalle</span><i class="fas fa-prescription-bottle-alt"></i> </button>';
                    $button2 = '<button type="button" name="edit_pendiente" id="' . $pendiente->id . '
                    " class="edit_pendiente btn btn-app bg-info tooltipsC" title="Editar"  >
                    <span class="badge bg-teal">Editar</span><i class="fas fa-pencil-alt"></i> </button>';

                    return $button . ' ' . $button2;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('menu.Medcol6.indexAnalista');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Medcol6\PendienteApiMedcol6  $pendienteApiMedcol6
     * @return \Illuminate\Http\Response
     */
    public function getAnulados(Request $request)
{
    // Definir las fechas por defecto
    $fechaAi = now()->startOfDay();
    $fechaAf = now()->endOfDay();
    
    $i = Auth::user()->drogueria;
    
    // Mapeo de droguerías
    $droguerias = [
        "1" => '',
        "2" => 'SALUD',
        "3" => 'DOLOR',
        "4" => 'PAC',
        "5" => 'EHU1',
        "6" => 'BIO1',
        "8" => 'EM01',
        "9" => 'FSIO',
        "10" => 'FSOS',
        "11" => 'FSAU',
        "12" => 'EVSO',
        "13" => 'FRJA'
    ];
    
    $drogueria = $droguerias[$i] ?? null;

    if ($request->ajax()) {
        // Construir la consulta base
        $query = PendienteApiMedcol6::where('estado', 'ANULADO');
        
        // Aplicar filtro de droguería si no es admin (droguería 1)
        if ($i != "1" && $drogueria) {
            $query->where('centroproduccion', $drogueria);
        }
        
        // Filtrar por fechas si están presentes
        if (!empty($request->fechaini) && !empty($request->fechafin)) {
            $fechaini = Carbon::parse($request->fechaini)->startOfDay();
            $fechafin = Carbon::parse($request->fechafin)->endOfDay();
            $query->whereBetween('fecha_factura', [$fechaini, $fechafin]);
        } else {
            // Si no se proporcionan fechas, usar las fechas por defecto
            $query->whereBetween('fecha_factura', [$fechaAi, $fechaAf]);
        }
    
        // Filtrar por contrato si está presente
        if (!empty($request->contrato)) {
            $query->where('centroproduccion', $request->contrato);
        }
    
        // Obtener los resultados
        $pendiente_api_medcol6 = $query->orderBy('id')->get();
        
        return DataTables()->of($pendiente_api_medcol6)
            ->addColumn('action', function ($pendiente) {
                $button = '<button type="button" name="show_detail" id="'.$pendiente->id.'" 
                          class="show_detail btn btn-app bg-secondary tooltipsC" title="Detalle">
                          <span class="badge bg-teal">Detalle</span><i class="fas fa-prescription-bottle-alt"></i></button>';
                          
                $button2 = '<button type="button" name="edit_pendiente" id="'.$pendiente->id.'" 
                           class="edit_pendiente btn btn-app bg-info tooltipsC" title="Editar">
                           <span class="badge bg-teal">Editar</span><i class="fas fa-pencil-alt"></i></button>';

                return $button.' '.$button2;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('menu.Medcol6.indexAnalista');
}
    
    public function getVencidos(Request $request)
    {
         // Definir las fechas por defecto
            $fechaAi = now()->startOfDay();
            $fechaAf = now()->endOfDay();
        
            // Obtener la droguería del usuario autenticado
            $drogueria = '';
            switch (Auth::user()->drogueria) {
                case "1":
                    $drogueria = ''; // Todos
                    break;
                case "2":
                    $drogueria = 'SALUD';
                    break;
                case "3":
                    $drogueria = 'DOLOR';
                    break;
                case "4":
                    $drogueria = 'PAC';
                    break;
                case "5":
                    $drogueria = 'EHU1';
                    break;
                case "6":
                    $drogueria = 'BIO1';
                    break;
                case "8":
                    $drogueria = 'EM01';
                    break;
                case "9":
                    $drogueria = 'FSIO';
                    break;
                case "10":
                    $drogueria = 'FSOS';
                    break;
                case "11":
                    $drogueria = 'FSAU';
                    break;
                case "12":
                    $drogueria = 'EVSO';
                    break;
                case "13":
                    $drogueria = 'FRJA';
                    break;
            }
        
            if ($request->ajax()) {
                // Iniciar la consulta
                $query = PendienteApiMedcol6::query();
        
                // Filtrar por estado
                $query->where(function($q) {
                    $q->where('estado', 'VENCIDO')
                      ->orWhere('estado', NULL);
                });
        
                // Filtrar por centro de producción si no es '1'
                if (Auth::user()->drogueria !== '1') {
                    $query->where('centroproduccion', $drogueria);
                }
        
                // Filtrar por fechas si están presentes
                if (!empty($request->fechaini) && !empty($request->fechafin)) {
                    $fechaini = Carbon::parse($request->fechaini)->startOfDay();
                    $fechafin = Carbon::parse($request->fechafin)->endOfDay();
                    $query->whereBetween('fecha_factura', [$fechaini, $fechafin]);
                } else {
                    // Si no se proporcionan fechas, usar las fechas por defecto
                    $query->whereBetween('fecha_factura', [$fechaAi, $fechaAf]);
                }
        
                // Filtrar por contrato si está presente
                if (!empty($request->contrato)) {
                    $query->where('centroproduccion', $request->contrato);
                }
        
                // Obtener los resultados
                $pendiente_api_medcol6 = $query->orderBy('id')->get();
        
                // Retornar los resultados para DataTables
                return DataTables()->of($pendiente_api_medcol6)
                    ->addColumn('action', function ($pendiente) {
                        $button = '<button type="button" name="show_detail" id="' . $pendiente->id . '" class="show_detail btn btn-app bg-secondary tooltipsC" title="Detalle">
                            <span class="badge bg-teal">Detalle</span><i class="fas fa-prescription-bottle-alt"></i></button>';
                        $button2 = '<button type="button" name="edit_pendiente" id="' . $pendiente->id . '" class="edit_pendiente btn btn-app bg-info tooltipsC" title="Editar">
                            <span class="badge bg-teal">Editar</span><i class="fas fa-pencil-alt"></i></button>';
        
                        return $button . ' ' . $button2;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        
            // Retornar la vista si no es una solicitud AJAX
            return view('menu.Medcol6.indexAnalista');
        }

    public function update(Request $request, $id)
    {
        $rules = array(
            'estado' => 'required'
        );
        
        if ($request->input('enviar_fecha_entrega') == 'true') {
            $rules['fecha_entrega'] = 'required';
            $rules['factura_entrega'] = 'required';
            $rules['cantord'] = 'required|numeric|min:1'; // Mayor a cero
            $rules['cantdpx'] = 'required|numeric|min:1'; // Mayor a cero
        }
        
        if ($request->input('enviar_fecha_impresion') == 'true') {
            $rules['fecha_impresion'] = 'required';
        }
        
        if ($request->input('enviar_fecha_anulado') == 'true') {
            $rules['fecha_anulado'] = 'required';
        }
        
        if ($request->input('enviar_fecha_factura_entrega') == 'true') {
            $rules['doc_entrega'] = 'required';
            $rules['factura_entrega'] = 'required';
        }
        
        $messages = [
            'cantord.min' => 'El campo Cantidad Ordenada debe ser mayor a cero',
            'cantdpx.min' => 'El campo Cantidad Entregada debe ser mayor a cero',
            'factura_entrega.required' => 'El campo Factura Entrega es requerido',
        ];
        
        $error = Validator::make($request->all(), $rules, $messages);
        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        
        if (request()->ajax()) {
            $pendiente_api_medcol6 = PendienteApiMedcol6::findOrFail($id);
            $pendiente_api_medcol6->fill($request->all());
            $pendiente_api_medcol6->doc_entrega = $request->doc_entrega;
            $pendiente_api_medcol6->factura_entrega = $request->factura_entrega;
            $pendiente_api_medcol6->usuario = $request->name;
            
            if ($request->input('enviar_fecha_entrega') == 'true') {
                // Validar que cantord y cantdpx sean mayores a cero
                if ($request->input('cantord') <= 0) {
                    return response()->json(['errors' => ['El campo cantord debe ser mayor a cero']]);
                }
                
                if ($request->input('cantdpx') <= 0) {
                    return response()->json(['errors' => ['El campo cantdpx debe ser mayor a cero']]);
                }
                
                // Validar rango de fecha
                if ($request->fecha_entrega < $pendiente_api_medcol6->fecha || $request->fecha_entrega > now()->format('Y-m-d')) {
                    return response()->json(['errors' => ['La fecha de ENTREGA debe estar entre la fecha de la factura y la fecha actual']]);
                }
                
                $pendiente_api_medcol6->fecha_entrega = $request->fecha_entrega;
            }
            
            if ($request->input('enviar_fecha_impresion') == 'true') {
                if ($request->fecha_impresion < $pendiente_api_medcol6->fecha || $request->fecha_impresion > now()->format('Y-m-d')) {
                    return response()->json(['errors' => ['La fecha de TRAMITE debe estar entre la fecha de la factura y la fecha actual']]);
                }
                $pendiente_api_medcol6->fecha_impresion = $request->fecha_impresion;
            }
            
            if ($request->input('enviar_fecha_anulado') == 'true') {
                if ($request->fecha_anulado < $pendiente_api_medcol6->fecha || $request->fecha_anulado > now()->format('Y-m-d')) {
                    return response()->json(['errors' => ['La fecha de ANULACIÓN debe estar entre la fecha de la factura y la fecha actual']]);
                }
                $pendiente_api_medcol6->fecha_anulado = $request->fecha_anulado;
            }
            
            $pendiente_api_medcol6->save();
            
            // Guardar observación en la tabla ObservacionesApiMedcol6
            ObservacionesApiMedcol6::create([
                'pendiente_id' => $pendiente_api_medcol6->id,
                'observacion' => $request->input('observacion'),
                'usuario' => $request->input('name'),
                'estado' => $request->input('estado')
            ]);
        }
        
        return response()->json(['success' => 'ok1']);
    }


    public function saveObs(Request $request)
    {
        /* ObservacionesApiMedcol6::create([
            'pendiente_id' => $id,
            'observacion' => $request->input('observacion'),
            'estado' => $request->input('estado')
        ]); */
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Medcol6\PendienteApiMedcol6  $pendienteApiMedcol6
     * @return \Illuminate\Http\Response
     */
    public function getObservaciones(Request $request)
    {
        $idlist = $request->id;

        if (request()->ajax()) {
            $data = DB::table('observaciones_api_medcol6')
                ->where('observaciones_api_medcol6.pendiente_id', '=', $idlist)
                ->get();

            return DataTables()->of($data)->make(true);
        }
        return view('menu.Medcol6.indexAnalista');
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
        if (request()->ajax()) {
            $pendiente = PendienteApiMedcol6::where([['id', '=', $id]])
                ->first();

            $saldo_pendiente = $pendiente->cantord - $pendiente->cantdpx;
            // Concatenar los campos doc_entrega y factura_entrega
            $fac_entrega = $pendiente->doc_entrega . ' ' . $pendiente->factura_entrega;

            return response()->json([
                'pendiente' => $pendiente,
                'saldo_pendiente' => $saldo_pendiente,
                'fac_entrega' => $fac_entrega
            ]);
        }
        return view('menu.Medcol6.indexAnalista');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (request()->ajax()) {
            $pendiente = PendienteApiMedcol6::where('id', '=', $id)
                ->first();

            $saldo_pendiente = $pendiente->cantord - $pendiente->cantdpx;

            return response()->json([
                'pendiente' => $pendiente,
                'saldo_pendiente' => $saldo_pendiente
            ]);
        }
        return view('menu.Medcol6.indexAnalista');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function informes(Request $request)
    {
         $i = Auth::user()->drogueria;
        
        switch ($i) {
                    case "1":
                        $drogueria = '';
                        break;
                    case "2":
                        $drogueria = 'SALUD';
                        break;
                    case "3":
                       $drogueria = 'DOLOR';
                        break;
                    case "4":
                        $drogueria = 'PAC';
                        break;
                    case "5":
                        $drogueria = 'EHU1';
                        break;
                    case "6":
                         $drogueria = 'BIO1';
                        break; 
                         case "8":
                        $drogueria = 'EM01';
                        break;    
                     case "9":
                        $drogueria = 'FSIO';
                        break;
                    case "10":
                        $drogueria = 'FSOS';
                        break;
                    case "11":
                        $drogueria = 'FSAU';
                        break;
                    case "12":
                        $drogueria = 'EVSO';
                        break; 
                    case "13":
                        $drogueria = 'FRJA';
                        break;     
                }
                
    if(Auth::user()->drogueria == '1'){            
        
        $pendientes =  PendienteApiMedcol6::where([['estado', 'PENDIENTE']])->count();
        $entregados =  PendienteApiMedcol6::where([['estado', 'ENTREGADO']])->count();
        $tramitados =  PendienteApiMedcol6::where([['estado', 'TRAMITADO']])->count();
        $agotados =  PendienteApiMedcol6::where([['estado', 'DESABASTECIDO']])->count();
        $anulados =  PendienteApiMedcol6::where([['estado', 'ANULADO']])->count();
        $vencidos =  PendienteApiMedcol6::where([['estado', 'VENCIDO']])->count();
    }else{
        
        $pendientes =  PendienteApiMedcol6::where([['estado', 'PENDIENTE'], ['centroproduccion',$drogueria]])->count();
        $entregados =  PendienteApiMedcol6::where([['estado', 'ENTREGADO'], ['centroproduccion',$drogueria]])->count();
        $tramitados =  PendienteApiMedcol6::where([['estado', 'TRAMITADO'], ['centroproduccion',$drogueria]])->count();
        $agotados =  PendienteApiMedcol6::where([['estado', 'DESABASTECIDO'], ['centroproduccion',$drogueria]])->count();
        $anulados =  PendienteApiMedcol6::where([['estado', 'ANULADO'], ['centroproduccion',$drogueria]])->count();
        $vencidos =  PendienteApiMedcol6::where([['estado', 'VENCIDO'], ['centroproduccion',$drogueria]])->count();
    }
        
    

        return response()->json(['pendientes' => $pendientes, 'entregados' => $entregados, 'tramitados' => $tramitados, 'agotados' => $agotados, 'anulados' => $anulados, 'vencidos' => $vencidos]);
    }

    public function createentregadospi($var1)
    {
        $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = 'colMed2023**';

        $response = Http::post(
            "http://hed08pf9dxt.sn.mynetname.net:8004/api/acceso",
            [
                'email' =>  $email,
                'password' => $password,
            ]
        );


        // $this->createapendientespi($request);

        $prueba = $response->json();
        $token = $prueba["token"];

        $responsefacturas = Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/entregadosapi");

        $facturassapi = $responsefacturas->json();

        
        //dd($facturassapi);
        
        $contadorei = 0;
        $contador1 = 0;

        foreach ($facturassapi['data'] as $factura) {

            $existe =  EntregadosApiMedcol6::where([
                ['factura', trim($factura['factura'])],['documento', trim($factura['documento'])]])->count();
               

            if ($existe == 0 || $existe == '') {
                
               EntregadosApiMedcol6::create([
                    'Tipodocum' => trim($factura['Tipodocum']),
                    'cantdpx' => trim($factura['cantdpx']),
                    'cantord' => trim($factura['cantord']),
                    'fecha_factura' => trim($factura['fecha_factura']),
                    'fecha' => trim($factura['fecha']),
                    'historia' => trim($factura['historia']),
                    'apellido1' => trim($factura['apellido1']),
                    'apellido2' => trim($factura['apellido2']),
                    'nombre1' => trim($factura['nombre1']),
                    'nombre2' => trim($factura['nombre2']),
                    'cantedad' => trim($factura['cantedad']),
                    'direcres' => trim($factura['direcres']),
                    'telefres' => trim($factura['telefres']),
                    'documento' => trim($factura['documento']),
                    'factura' => trim($factura['factura']),
                    'codigo' => trim($factura['codigo']),
                    'nombre' => trim($factura['nombre']),
                    'cums' => trim($factura['cums']),
                    'cantidad' => trim($factura['cantidad']),
                    'cajero' => trim($factura['cajero']),
                    'orden_externa' => trim($factura['orden_externa']),
                    'doc_entrega' => trim($factura['documento']),
                    'factura_entrega' => trim($factura['factura']),
                    'centroproduccion' => trim($factura['CENTROPRODUCCION']),
                    'observaciones' => trim($factura['observaciones'])
                ]);
             
                
               $contador1++;
               
               Log::info('Desde la web syncapi '.$contador1 . ' Linea creada entregados y ' . trim($factura['documento']).trim($factura['factura']) . ' Medicamento - '. trim($factura['cums']).'=>'.trim($factura['nombre']).'Documento: '.trim($factura['historia']));
            }else{
                
                Log::info('Desde la web syncapi '.$contador1 . ' ya creada en entregados y ' . trim($factura['documento']).trim($factura['factura']) . ' Medicamento - '. trim($factura['cums']).'=>'.trim($factura['nombre']).'Documento: '.trim($factura['historia']));
            }
        }

        Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/closeallacceso");

        $pendientes = DB::table('pendiente_api_medcol6')
            ->join('entregados_api_medcol6', function ($join) {
                $join->on('pendiente_api_medcol6.orden_externa', '=', 'entregados_api_medcol6.orden_externa')
                    ->on('pendiente_api_medcol6.codigo', '=', 'entregados_api_medcol6.codigo');
            })
            ->select(
                'pendiente_api_medcol6.id as idd',
                'entregados_api_medcol6.orden_externa',
                'entregados_api_medcol6.codigo',
                'entregados_api_medcol6.cantdpx',
                'entregados_api_medcol6.fecha_factura',
                'entregados_api_medcol6.documento',
                'entregados_api_medcol6.factura'
            )
            ->where([['pendiente_api_medcol6.estado', 'PENDIENTE']])
            ->get();
            
           

        foreach ($pendientes as $key => $value) {

            $entregados =
                DB::table('pendiente_api_medcol6')
                ->where([
                    ['pendiente_api_medcol6.estado', '=', 'ENTREGADO'],
                    ['pendiente_api_medcol6.orden_externa', '=', $value->orden_externa],
                    ['pendiente_api_medcol6.codigo', '=', $value->codigo],
                    ['pendiente_api_medcol6.usuario', 'RFAST']
                ])->count();
                
                

            if ($entregados == 0 || $entregados == null) {

                DB::table('pendiente_api_medcol6')
                    ->where([
                        ['pendiente_api_medcol6.estado', '=', 'PENDIENTE'],
                        ['pendiente_api_medcol6.orden_externa', '=', $value->orden_externa],
                        ['pendiente_api_medcol6.codigo', '=', $value->codigo]
                    ])
                    ->update([
                        'pendiente_api_medcol6.fecha_entrega' =>  $value->fecha_factura,
                        'pendiente_api_medcol6.estado' => 'ENTREGADO',
                        'pendiente_api_medcol6.cantdpx' => $value->cantdpx,
                        'pendiente_api_medcol6.doc_entrega' => $value->documento,
                        'pendiente_api_medcol6.factura_entrega' => $value->factura,
                        'pendiente_api_medcol6.usuario' => 'RFAST',
                        'pendiente_api_medcol6.updated_at' => now()
                    ]);

                $contadorei++;
            }


            // Guardar observación en la tabla ObservacionesApi

            $entregado = ObservacionesApiMedcol6::where([
                ['pendiente_id', $value->idd],
                ['estado', 'ENTREGADO']
            ])->count();

            if ($entregado == 0 || $entregado == null) {

                ObservacionesApiMedcol6::create([
                    'pendiente_id' => $value->idd,
                    'observacion' => 'Este resgistro se genero automaticamente al consumir la api',
                    'usuario' => 'RFAST',
                    'estado' => 'ENTREGADO'
                ]);
            }
        }

        return $this->var1 = $contadorei;
    }

    public function createentregadospilocal($var2)
    {
        $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = 'colMed2023**';

        $response = Http::post(
            "http://192.168.66.91:8004/api/acceso",
            [
                'email' =>  $email,
                'password' => $password,
            ]
        );


        // $this->createapendientespi($request);

        $prueba = $response->json();
        $token = $prueba["token"];

        $responsefacturas = Http::withToken($token)->get("http://192.168.66.91:8004/api/entregadosapi");

        $facturassapi = $responsefacturas->json();

        //dd($facturassapi);
        $contadorei = 0;
        $contador1 = 0;

        foreach ($facturassapi['data'] as $factura) {


            $existe =  EntregadosApiMedcol6::where('factura', $factura['factura'])->count();

            if ($existe == 0 || $existe == '') {
                EntregadosApiMedcol6::create([
                    'Tipodocum' => trim($factura['Tipodocum']),
                    'cantdpx' => trim($factura['cantdpx']),
                    'cantord' => trim($factura['cantord']),
                    'fecha_factura' => trim($factura['fecha_factura']),
                    'fecha' => trim($factura['fecha']),
                    'historia' => trim($factura['historia']),
                    'apellido1' => trim($factura['apellido1']),
                    'apellido2' => trim($factura['apellido2']),
                    'nombre1' => trim($factura['nombre1']),
                    'nombre2' => trim($factura['nombre2']),
                    'cantedad' => trim($factura['cantedad']),
                    'direcres' => trim($factura['direcres']),
                    'telefres' => trim($factura['telefres']),
                    'documento' => trim($factura['documento']),
                    'factura' => trim($factura['factura']),
                    'codigo' => trim($factura['codigo']),
                    'nombre' => trim($factura['nombre']),
                    'cums' => trim($factura['cums']),
                    'cantidad' => trim($factura['cantidad']),
                    'cajero' => trim($factura['cajero']),
                    'orden_externa' => trim($factura['orden_externa']),
                    'doc_entrega' => trim($factura['documento']),
                    'factura_entrega' => trim($factura['factura']),
                    'centroproduccion' => trim($factura['CENTROPRODUCCION']),
                    'observaciones' => trim($factura['observaciones'])
                ]);

                $contador1++;
            }else{
                
                
            }
        }

        Http::withToken($token)->get("http://192.168.66.91:8004/api/closeallacceso");

        $pendientes = DB::table('pendiente_api_medcol6')
            ->join('entregadosapi', function ($join) {
                $join->on('pendiente_api_medcol6.orden_externa', '=', 'entregadosapi.orden_externa')
                    ->on('pendiente_api_medcol6.codigo', '=', 'entregadosapi.codigo');
            })
            ->select(
                'pendiente_api_medcol6.id as idd',
                'entregadosapi.orden_externa',
                'entregadosapi.codigo',
                'entregadosapi.cantdpx',
                'entregadosapi.fecha_factura',
                'entregadosapi.documento',
                'entregadosapi.factura'
            )
            ->get();

        foreach ($pendientes as $key => $value) {

            $entregados =
                DB::table('pendiente_api_medcol6')
                ->where([
                    ['pendiente_api_medcol6.estado', '=', 'ENTREGADO'],
                    ['pendiente_api_medcol6.orden_externa', '=', $value->orden_externa],
                    ['pendiente_api_medcol6.codigo', '=', $value->codigo],
                    ['pendiente_api_medcol6.usuario', 'RFAST']
                ])->count();

            if ($entregados == 0 || $entregados == null) {

                DB::table('pendiente_api_medcol6')
                    ->where([
                        ['pendiente_api_medcol6.estado', '=', 'PENDIENTE'],
                        ['pendiente_api_medcol6.orden_externa', '=', $value->orden_externa],
                        ['pendiente_api_medcol6.codigo', '=', $value->codigo]
                    ])
                    ->update([
                        'pendiente_api_medcol6.fecha_entrega' =>  $value->fecha_factura,
                        'pendiente_api_medcol6.estado' => 'ENTREGADO',
                        'pendiente_api_medcol6.cantdpx' => $value->cantdpx,
                        'pendiente_api_medcol6.doc_entrega' => $value->documento,
                        'pendiente_api_medcol6.factura_entrega' => $value->factura,
                        'pendiente_api_medcol6.usuario' => 'RFAST',
                        'pendiente_api_medcol6.updated_at' => now()
                    ]);

                $contadorei++;
            }


            // Guardar observación en la tabla ObservacionesApi

            $entregado = ObservacionesApiMedcol6::where([
                ['pendiente_id', $value->idd],
                ['estado', 'ENTREGADO']
            ])->count();

            if ($entregado == 0 || $entregado == null) {

                ObservacionesApiMedcol6::create([
                    'pendiente_id' => $value->idd,
                    'observacion' => 'Este resgistro se genero automaticamente al consumir la api',
                    'usuario' => 'RFAST',
                    'estado' => 'ENTREGADO'
                ]);
            }
        }



        return $this->var2 = $contadorei;
    }
    
       
     public function informepedientes(Request $request)
    {
         $i = Auth::user()->drogueria;
        
        switch ($i) {
                    case "1":
                        $drogueria = '';
                        break;
                    case "2":
                        $drogueria = 'SALUD';
                        break;
                    case "3":
                       $drogueria = 'DOLOR';
                        break;
                    case "4":
                        $drogueria = 'PAC';
                        break;
                    case "5":
                        $drogueria = 'EHU1';
                        break;
                    case "6":
                         $drogueria = 'BIO1';
                        break;
                     case "8":
                        $drogueria = 'EM01';
                        break;    
                     case "9":
                        $drogueria = 'FSIO';
                        break;
                    case "10":
                        $drogueria = 'FSOS';
                        break;
                    case "11":
                        $drogueria = 'FSAU';
                        break;
                    case "12":
                        $drogueria = 'EVSO';
                        break; 
                    case "13":
                        $drogueria = 'FRJA';
                        break;         
                }
        

        if (request()->ajax()) {
            
             if(Auth::user()->drogueria == '1'){
            
            $data = DB::table('pendiente_api_medcol6')
                ->where('estado', '=', 'PENDIENTE')
                ->select('nombre')
                ->selectRaw('SUM(cantord) as cantord')
                ->groupBy('nombre')
                ->get();

           
            
             }else{
                 
                  $data = DB::table('pendiente_api_medcol6')
                ->where([['estado', '=', 'PENDIENTE'],['centroproduccion',$drogueria]])
                ->select('nombre')
                ->selectRaw('SUM(cantord) as cantord')
                ->groupBy('nombre')
                ->get();

           
             }
              return DataTables()->of($data)->make(true);
                 
             }
        
        //return view('menu.usuario.indexAnalista');
    }
    
    public function updateanuladosapi(Request $request)
    {
        $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = 'colMed2023**';
        $usuario = Auth::user()->email;
    
        try {
            $response = Http::post("http://hed08pf9dxt.sn.mynetname.net:8004/api/acceso", [
                'email' => $email,
                'password' => $password,
            ]);
    
            $token = $response->json()["token"] ?? null;
    
            if ($token) {
                try {
                    $responsefacturas = Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/pendientesanuladosapi");
                    $facturassapi = $responsefacturas->json()['data'] ?? [];
    
                    $contadorActualizados = 0;
                    
                    //dd($facturassapi);
    
                    foreach ($facturassapi as $factura) {
                        if (isset($factura['orden_externa'])) {
                            $actualizados = PendienteApiMedcol6::where('orden_externa', $factura['orden_externa'])
                                ->where('estado', ['PENDIENTE'])
                                ->update([
                                    'estado' => 'ANULADO',
                                    'fecha_anulado' => now(),
                                    'updated_at' => now()
                                ]);
                        } 
    
                        if ($actualizados) {
                            $contadorActualizados++;
                        }
                    }
                    
                    
    
                    Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/closeallacceso");
    
                    Log::info('Desde la web syncapi autopista anulados', [
                        'lineas_actualizadas' => $contadorActualizados,
                        'usuario' => $usuario
                    ]);
    
                    return response()->json([
                        [
                            'respuesta' => $contadorActualizados . " Pendientes anuladas",
                            'titulo' => 'Lineas Actualizadas',
                            'icon' => 'success',
                            'position' => 'bottom-left'
                        ]
                    ]);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
    
                    return response()->json([
                        'respuesta' => 'Error: ' . $e->getMessage(),
                        'titulo' => 'Error',
                        'icon' => 'error',
                        'position' => 'bottom-left'
                    ]);
                }
            } else {
                return response()->json([
                    'respuesta' => 'Error: No se pudo obtener el token',
                    'titulo' => 'Error',
                    'icon' => 'error',
                    'position' => 'bottom-left'
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
    
            return response()->json([
                'respuesta' => 'Error: ' . $e->getMessage(),
                'titulo' => 'Error',
                'icon' => 'error',
                'position' => 'bottom-left'
            ]);
        }
    }
    
    
    public function getreport(Request $request)
    {
        $i = Auth::user()->drogueria;

        // Mapeo de droguerías según el usuario
        $droguerias = [
            "1" => '',
            "2" => 'SALUD',
            "3" => 'DOLOR',
            "4" => 'PAC',
            "5" => 'EHU1',
            "6" => 'BIO1',
            "8" => 'EM01',
            "9" => 'FSIO',
            "10" => 'FSOS',
            "11" => 'FSAU',
            "12" => 'EVSO',
            "13" => 'FRJA',
        ];

        $drogueria = $droguerias[$i] ?? null;

        // Validar fechas
        $fechaInicio = $request->filled('fechaini')
            ? Carbon::parse($request->fechaini)->startOfDay()
            : now()->subMonth()->startOfDay();

        $fechaFin = $request->filled('fechafin')
            ? Carbon::parse($request->fechafin)->endOfDay()
            : now()->endOfDay();

        $contrato = $request->input('contrato', null);

        // Lista completa de códigos de droguerías
        $codigosDroguerias = [
            'SALUD',
            'DOLOR',
            'PAC',
            'EHU1',
            'BIO1',
            'EM01',
            'FSIO',
            'FSOS',
            'FSAU',
            'EVSO',
            'FRJA',
            'FRIO'
        ];

        // Construcción de la consulta base
        $queryBase = PendienteApiMedcol6::whereBetween('fecha_factura', [$fechaInicio, $fechaFin])
            ->whereNotIn('codigo', ['1010', '1011', '1012']);

        // Aplicar filtros según selección
        if ($contrato && $contrato !== 'Todos') {
            // Filtro por contrato específico
            $queryBase->where('centroproduccion', $contrato);
        } elseif ($contrato === 'Todos') {
            // Incluir todos los contratos
            $queryBase->whereIn('centroproduccion', $codigosDroguerias);
        }

        // Filtro por droguería del usuario si no es admin
        if ($i !== "1" && $drogueria) {
            $queryBase->where('centroproduccion', $drogueria);
        }

        // Consulta para conteo por centro de producción y estado
        $queryGrouped = (clone $queryBase)
            ->select('centroproduccion', 'estado', DB::raw('count(*) as total'))
            ->groupBy('centroproduccion', 'estado');

        // Total de pendientes + entregados
        $totalPendientesGenerados = (clone $queryBase)
            ->whereIn('estado', ['PENDIENTE', 'ENTREGADO'])
            ->count();

        // Total exclusivo de pendientes (usando count en lugar de sum)
        $totalPendientes = (clone $queryBase)
            ->where('estado', 'PENDIENTE')
            ->count();

        // Entregas en 48 horas (3 días o menos de diferencia)
        $entregasA48Horas = (clone $queryBase)
            ->where('estado', 'ENTREGADO')
            ->whereNotNull('factura_entrega')
            ->whereRaw('DATEDIFF(factura_entrega, fecha_factura) <= 3')
            ->select('centroproduccion', DB::raw('count(*) as total'))
            ->groupBy('centroproduccion')
            ->get();

        // Total de entregas dentro de las 48 horas
        $totalEntregasA48Horas = (clone $queryBase)
            ->where('estado', 'ENTREGADO')
            ->whereNotNull('factura_entrega')
            ->whereRaw('DATEDIFF(factura_entrega, fecha_factura) <= 3')
            ->count();

        // Obtener datos para los estados agrupados
        $results = $queryGrouped->get()->groupBy('estado');

        // Extraer los pendientes
        $pendientes = $results->get('PENDIENTE', []);

        // CÁLCULOS DE PORCENTAJES

        // 1. Porcentaje de entregas en 48h respecto al total de pendientes generados
        $porcentajeEntregas48h = $totalPendientesGenerados > 0
            ? round(($totalEntregasA48Horas / $totalPendientesGenerados) * 100, 2)
            : 0;

        // 2. Porcentaje de pendientes respecto al total de pendientes generados
        $porcentajePendientes = $totalPendientesGenerados > 0
            ? round(($totalPendientes / $totalPendientesGenerados) * 100, 2)
            : 0;

        // 3. Calcular porcentajes por centro de producción para entregas en 48h
        $entregasA48HorasConPorcentaje = $entregasA48Horas->map(function ($item) use ($totalPendientesGenerados) {
            $item->porcentaje = $totalPendientesGenerados > 0
                ? round(($item->total / $totalPendientesGenerados) * 100, 2)
                : 0;
            return $item;
        });

        // 4. Calcular porcentajes por centro de producción para pendientes
        $pendientesConPorcentaje = $pendientes->map(function ($item) use ($totalPendientesGenerados) {
            $item->porcentaje = $totalPendientesGenerados > 0
                ? round(($item->total / $totalPendientesGenerados) * 100, 2)
                : 0;
            return $item;
        });

        return response()->json([
            'pendiente' => $pendientesConPorcentaje,
            'entregado' => $results->get('ENTREGADO', []),
            'anulado' => $results->get('ANULADO', []),
            'entregas_48h' => $entregasA48HorasConPorcentaje,
            'total_entregas_48h' => $totalEntregasA48Horas,
            'porcentaje_entregas_48h' => $porcentajeEntregas48h,
            'total_pendientes' => $totalPendientes,
            'porcentaje_pendientes' => $porcentajePendientes,
            'total_pendientes_generados' => $totalPendientesGenerados,
            'meta' => [
                'fecha_inicio' => $fechaInicio->format('Y-m-d H:i:s'),
                'fecha_fin' => $fechaFin->format('Y-m-d H:i:s'),
                'drogueria_filtrada' => $drogueria ?? 'Todas',
                'filtro_contrato' => $contrato === 'Todos' ? 'Todos los contratos' : ($contrato ?? 'No aplicado')
            ]
        ]);
    }

}
