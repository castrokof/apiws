<?php

namespace App\Http\Controllers\Medcol6;

use App\Http\Controllers\Controller;
use App\Models\Medcol6\PendienteApiMedcol6;
use App\Models\Medcol6\EntregadosApiMedcol6;
use App\Models\Medcol6\ObservacionesApiMedcol6;
use App\Models\Medcol6\SaldosMedcol6;
use App\Services\PendienteService;
use App\Http\Requests\UpdatePendienteRequest;
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
    private $pendienteService;

    public function __construct(PendienteService $pendienteService)
    {
        $this->pendienteService = $pendienteService;
    }

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
            $query->where(function ($q) {
                $q->where('estado', 'PENDIENTE')
                    ->orWhere('estado', NULL);
            });

            // Filtrar por centro de producción si no es '1'
            if (Auth::user()->drogueria !== '1') {

                if (Auth::user()->drogueria == '3')
                    $query->whereIn('centroproduccion', [$drogueria, 'DPA1']);
                else
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createapendientespi(Request $request)
    {
        // Obtener la fecha límite de los últimos 7 días
        $fechaLimite = Carbon::now()->startOfWeek()->subDays(7)->startOfDay();

        $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = 'colMed2023**';
        $usuario = Auth::user()->email;

        set_time_limit(0);
        //ini_set('memory_limit', '512M');

        try {

            $response = Http::post(
                "http://hed08pf9dxt.sn.mynetname.net:8004/api/acceso",
                [
                    'email' =>  $email,
                    'password' => $password,
                ]
            );

            $token = $response->json()["token"];


            $responsefacturas = Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/pendientesapi");

            $facturassapi = $responsefacturas->json()['data'];

            ini_set('memory_limit', count($facturassapi) > 10000 ? '1024M' : '512M');

            $contadorei = 0;
            $contador = 0;
            //$pendientes = [];

            // Obtener las facturas existentes en un solo query

            // Crear las claves únicas de cada entrada en $facturassapi
            $clavesFacturasApi = array_map(function ($f) {
                return trim($f['documento']) . '-' . trim($f['factura']) . '-' . trim($f['codigo']);
            }, $facturassapi);

            $facturasExistentes = collect();


            // Consultar la base de datos en chunks para evitar demasiados placeholders
            foreach (array_chunk($facturassapi, 500) as $chunk) {
                $ids = array_map(fn($f) => trim($f['documento']), $chunk);
                $facturas = array_map(fn($f) => trim($f['factura']), $chunk);
                $codigos = array_map(fn($f) => trim($f['codigo']), $chunk);

                $resultados = PendienteApiMedcol6::select('documento', 'factura', 'codigo')
                    ->whereIn('documento', $ids)
                    ->whereIn('factura', $facturas)
                    ->whereIn('codigo', $codigos)
                    ->where('fecha_factura', '>=', $fechaLimite)
                    ->get();

                $facturasExistentes = $facturasExistentes->merge($resultados);
            }

            // Crear claves únicas de los existentes en base de datos
            $facturasExistentesFlip = array_flip(
                $facturasExistentes->map(function ($item) {
                    return trim($item->documento) . '-' . trim($item->factura) . '-' . trim($item->codigo);
                })->toArray()

            );
            unset($facturasExistentesFlip);

            $pendientes = [];

            //dd($pendientes);

            foreach ($facturassapi as $factura) {

                // Verificar si la factura ya existe en el array obtenido antes
                $clave = trim($factura['documento']) . '-' . trim($factura['factura']) . '-' . trim($factura['codigo']);


                if (isset($facturasExistentesFlip[$clave])) {
                    // Registrar en el log como "NO" (porque ya existe)
                    Log::info("{$clave} => NO (ya existe)");
                    continue;
                }

                // Registrar en el log como "SI" (porque se va a insertar)
                Log::info("{$clave} => SI (se inserta)");

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

            if (!empty($pendientes)) {
                $chunks = array_chunk($pendientes, 500); // Divide en lotes de 500 registros

                foreach ($chunks as $chunk) {
                    PendienteApiMedcol6::insertOrIgnore($chunk);
                }
            }

            Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/closeallacceso");

            $var = $this->createentregadospi(null);

            Log::info('Desde la web syncapi ' . $contador . ' Lineas creadas y ' . $var . ' Lineas entregadas' . ' Usuario: ' . $usuario);

            return response()->json([
                ['respuesta' => $contador . ' Lineas creadas y ' . $var . ' Lineas entregadas', 'titulo' => 'Mixed lineas', 'icon' => 'success', 'position' => 'bottom-left']
            ]);
        } catch (\Exception $e) {


            $response = Http::post("http://192.168.66.95:8004/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];

            $responsefacturas = Http::withToken($token)->get("http://192.168.66.95:8004/api/pendientesapi");

            $facturassapi = $responsefacturas->json()['data'];

            //dd($facturassapi);

            $contador = 0;
            $pendientes = [];

            foreach ($facturassapi as $factura) {
                $existe = PendienteApiMedcol6::where([['factura', $factura['factura']], ['documento', $factura['documento']]])->count();

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

            Http::withToken($token)->get("http://192.168.66.95:8004/api/closeallacceso");

            $var = $this->createentregadospilocal(null);


            if ($e->getMessage()) {


                Log::error('Desde la web syncapi ' . $e->getMessage() . ' Usuario: ' . $usuario);


                return response()->json([
                    ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
                ]);
            } else {

                Log::info('Desde la web syncapi ' . $contador . ' Lineas creadas y ' . $var . ' Lineas entregadas' . ' Usuario: ' . $usuario);

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

            if (Auth::user()->drogueria == '1') {
                $pendiente_api_medcol6 = PendienteApiMedcol6::where([['estado', 'TRAMITADO']])
                    ->orderBy('id')
                    ->get();
            } else {
                $pendiente_api_medcol6 = PendienteApiMedcol6::where([['estado', 'TRAMITADO'], ['centroproduccion', $drogueria]])
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
            $query->where(function ($q) {
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

            if (Auth::user()->drogueria == '1') {

                $pendiente_api_medcol6 = PendienteApiMedcol6::where([['estado', 'DESABASTECIDO']])
                    ->orderBy('id')
                    ->get();
            } else {


                $pendiente_api_medcol6 = PendienteApiMedcol6::where([['estado', 'DESABASTECIDO'], ['centroproduccion', $drogueria]])
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
                    $button = '<button type="button" name="show_detail" id="' . $pendiente->id . '" 
                          class="show_detail btn btn-app bg-secondary tooltipsC" title="Detalle">
                          <span class="badge bg-teal">Detalle</span><i class="fas fa-prescription-bottle-alt"></i></button>';

                    $button2 = '<button type="button" name="edit_pendiente" id="' . $pendiente->id . '" 
                           class="edit_pendiente btn btn-app bg-info tooltipsC" title="Editar">
                           <span class="badge bg-teal">Editar</span><i class="fas fa-pencil-alt"></i></button>';

                    return $button . ' ' . $button2;
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
            $query->where(function ($q) {
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePendienteRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePendienteRequest $request, $id)
    {
        try {
            // Los datos ya están validados por el FormRequest
            $validatedData = $request->validated();

            Log::info('Attempting to update pendiente', [
                'pendiente_id' => $id,
                'user' => Auth::user()->email ?? 'unknown',
                'validated_data' => $validatedData
            ]);

            $result = $this->pendienteService->updatePendiente($id, $validatedData);

            Log::info('Pendiente updated successfully', [
                'pendiente_id' => $id,
                'user' => Auth::user()->email ?? 'unknown'
            ]);

            return response()->json($result);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error updating pendiente: ' . $e->getMessage(), [
                'pendiente_id' => $id,
                'user' => Auth::user()->email ?? 'unknown',
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'error_code' => $e->getCode()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error en la base de datos: ' . $e->getMessage()
            ], 500);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error updating pendiente: ' . $e->getMessage(), [
                'pendiente_id' => $id,
                'user' => Auth::user()->email ?? 'unknown',
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('General error updating pendiente: ' . $e->getMessage(), [
                'pendiente_id' => $id,
                'user' => Auth::user()->email ?? 'unknown',
                'data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ], 500);
        }
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
        if (request()->ajax()) {
            // Consulta corregida usando el modelo Eloquent con join
            $pendiente = PendienteApiMedcol6::from('pendiente_api_medcol6 as p')
                ->leftJoin(DB::raw('(SELECT pendiente_id, observacion, created_at,
                                ROW_NUMBER() OVER (PARTITION BY pendiente_id ORDER BY created_at DESC) as rn
                                FROM observaciones_api_medcol6) as o'), 
                    function($join) {
                        $join->on('p.id', '=', 'o.pendiente_id')
                             ->where('o.rn', '=', 1);
                    })
                ->select('p.*', 'o.observacion as ultima_observacion', 'o.created_at as fecha_ultima_observacion')
                ->where('p.id', $id)
                ->first();
            
            // Verificar si se encontró el registro
            if (!$pendiente) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }
            
            $saldo_pendiente = $pendiente->cantord - $pendiente->cantdpx;
            // Concatenar los campos doc_entrega y factura_entrega
            $fac_entrega = $pendiente->doc_entrega . ' ' . $pendiente->factura_entrega;
    
            return response()->json([
                'pendiente' => $pendiente,
                'saldo_pendiente' => $saldo_pendiente,
                'observaciones' => $pendiente->ultima_observacion,
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

        if (Auth::user()->drogueria == '1') {

            $pendientes =  PendienteApiMedcol6::where([['estado', 'PENDIENTE']])->count();
            $entregados =  PendienteApiMedcol6::where([['estado', 'ENTREGADO']])->count();
            $tramitados =  PendienteApiMedcol6::where([['estado', 'TRAMITADO']])->count();
            $agotados =  PendienteApiMedcol6::where([['estado', 'DESABASTECIDO']])->count();
            $anulados =  PendienteApiMedcol6::where([['estado', 'ANULADO']])->count();
            $vencidos =  PendienteApiMedcol6::where([['estado', 'VENCIDO']])->count();
        } else {

            $pendientes =  PendienteApiMedcol6::where([['estado', 'PENDIENTE'], ['centroproduccion', $drogueria]])->count();
            $entregados =  PendienteApiMedcol6::where([['estado', 'ENTREGADO'], ['centroproduccion', $drogueria]])->count();
            $tramitados =  PendienteApiMedcol6::where([['estado', 'TRAMITADO'], ['centroproduccion', $drogueria]])->count();
            $agotados =  PendienteApiMedcol6::where([['estado', 'DESABASTECIDO'], ['centroproduccion', $drogueria]])->count();
            $anulados =  PendienteApiMedcol6::where([['estado', 'ANULADO'], ['centroproduccion', $drogueria]])->count();
            $vencidos =  PendienteApiMedcol6::where([['estado', 'VENCIDO'], ['centroproduccion', $drogueria]])->count();
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
                ['factura', trim($factura['factura'])],
                ['documento', trim($factura['documento'])]
            ])->count();



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

                Log::info('Desde la web syncapi ' . $contador1 . ' Linea creada entregados y ' . trim($factura['documento']) . trim($factura['factura']) . ' Medicamento - ' . trim($factura['cums']) . '=>' . trim($factura['nombre']) . 'Documento: ' . trim($factura['historia']));
            } else {

                Log::info('Desde la web syncapi ' . $contador1 . ' ya creada en entregados y ' . trim($factura['documento']) . trim($factura['factura']) . ' Medicamento - ' . trim($factura['cums']) . '=>' . trim($factura['nombre']) . 'Documento: ' . trim($factura['historia']));
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
            "http://192.168.66.95:8004/api/acceso",
            [
                'email' =>  $email,
                'password' => $password,
            ]
        );


        // $this->createapendientespi($request);

        $prueba = $response->json();
        $token = $prueba["token"];

        $responsefacturas = Http::withToken($token)->get("http://192.168.66.95:8004/api/entregadosapi");

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
            } else {
            }
        }

        Http::withToken($token)->get("http://192.168.66.95:8004/api/closeallacceso");

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

            if (Auth::user()->drogueria == '1') {

                $data = DB::table('pendiente_api_medcol6')
                    ->where('estado', '=', 'PENDIENTE')
                    ->select('nombre')
                    ->selectRaw('SUM(cantord) as cantord')
                    ->groupBy('nombre')
                    ->get();
            } else {

                $data = DB::table('pendiente_api_medcol6')
                    ->where([['estado', '=', 'PENDIENTE'], ['centroproduccion', $drogueria]])
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
            'FRIO',
            'FRIP'
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

    //Funcion del controlador para obtener los medicamentos por farmacia
    public function getMedicamentosPorFarmacia(Request $request)
    {
        // Validar las fechas de entrada
        $request->validate([
            'fechaini' => 'nullable|date',
            'fechafin' => 'nullable|date|after_or_equal:fechaini',
            'contrato' => 'nullable|string'
        ]);

        $i = Auth::user()->drogueria;

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

        $farmacias = [
            "BIO1",
            "DLR1",
            "DPA1",
            "EM01",
            "EHU1",
            "FRJA",
            "FRIO",
            "INY",
            "PAC",
            "SM01",
            "BPDT",
            "EVEN",
            "EVSM",
            "FRIP"
        ];

        $drogueria = $droguerias[$i] ?? null;

        $fechaInicio = $request->filled('fechaini')
            ? Carbon::parse($request->fechaini)->startOfDay()
            : now()->subMonth()->startOfDay();

        $fechaFin = $request->filled('fechafin')
            ? Carbon::parse($request->fechafin)->endOfDay()
            : now()->endOfDay();

        $contrato = $request->input('contrato', null);

        $queryBase = PendienteApiMedcol6::whereBetween('fecha_factura', [$fechaInicio, $fechaFin])
            ->whereNotIn('codigo', ['1010', '1011', '1012']);

        if ($contrato && $contrato !== 'Todos') {
            $queryBase->where('centroproduccion', $contrato);
        } elseif ($contrato === 'Todos') {
            $queryBase->whereIn('centroproduccion', $farmacias);
        }

        if ($i !== "1" && $drogueria) {
            $queryBase->where('centroproduccion', $drogueria);
        }

        // Obtener datos por nombre y farmacia
        $datos = (clone $queryBase)
            ->where('estado', 'PENDIENTE')
            ->select('nombre', 'centroproduccion', DB::raw('SUM(cantord) as cantidad'))
            ->groupBy('nombre', 'centroproduccion')
            ->orderBy('nombre')
            ->get();

        // Organizar en matriz: nombre => [farmacia => cantidad]
        $matriz = [];
        $totalesPorFarmacia = array_fill_keys($farmacias, 0);

        foreach ($datos as $item) {
            $nombre = $item->nombre;
            $centro = $item->centroproduccion;
            $cantidad = (int) $item->cantidad;

            if (!isset($matriz[$nombre])) {
                $matriz[$nombre] = array_fill_keys($farmacias, 0);
            }

            $matriz[$nombre][$centro] = $cantidad;
            $totalesPorFarmacia[$centro] += $cantidad;
        }

        // Preparar datos para la respuesta
        $medicamentos = [];
        foreach ($matriz as $nombre => $cantidades) {
            $totalMedicamento = array_sum($cantidades);
            $medicamentos[] = [
                'nombre' => $nombre,
                'cantidades' => $cantidades,
                'total' => $totalMedicamento
            ];
        }

        return response()->json([
            'success' => true,
            'medicamentos' => $medicamentos,
            'totales_por_farmacia' => $totalesPorFarmacia,
            'farmacias' => $farmacias,
            'total_registros' => count($matriz),
            'fecha_inicio' => $fechaInicio->format('Y-m-d'),
            'fecha_fin' => $fechaFin->format('Y-m-d'),
            'drogueria' => $drogueria ?? 'Todas',
            'contrato' => $contrato ?? 'No aplicado'
        ]);
    }

    /**
     * Obtener informe detallado de medicamentos pendientes vs saldos
     */
    public function informePendientesVsSaldos(Request $request)
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

        // Obtener parámetros de filtros
        $fechaInicio = $request->get('fechaini');
        $fechaFin = $request->get('fechafin');
        $contrato = $request->get('contrato');

        if (!$fechaInicio || !$fechaFin) {
            return response()->json([
                'success' => false,
                'message' => 'Debe proporcionar fechas de inicio y fin'
            ]);
        }

        try {
            $fechaInicio = Carbon::parse($fechaInicio);
            $fechaFin = Carbon::parse($fechaFin);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Formato de fecha inválido'
            ]);
        }

        // Query base para pendientes
        $queryPendientes = PendienteApiMedcol6::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->where('estado', 'PENDIENTE');

        // Aplicar filtros de droguería si corresponde
        if ($drogueria) {
            $queryPendientes->where('sucursal', $drogueria);
        }

        // Aplicar filtro de contrato/farmacia si se proporciona
        if ($contrato && $contrato !== 'Todos') {
            $queryPendientes->where('centroproduccion', $contrato);
        }

        // Obtener medicamentos pendientes con sus datos
        $pendientes = $queryPendientes
            ->select(
                'codigo',
                'nombre',
                'cums',
                'centroproduccion as farmacia',
                DB::raw('SUM(cantord) as cantidad_pendiente')
            )
            ->groupBy('codigo', 'nombre', 'cums', 'centroproduccion')
            ->get();

        // Mapeo de farmacias a depósitos para consultar saldos
        $farmaciaToDeposito = [
            'BIO1' => 'BIO1',
            'DLR1' => 'DLR1',
            'DPA1' => 'DPA1',
            'EM01' => 'EM01',
            'EHU1' => 'EHU1',
            'FRJA' => 'FRJA',
            'FRIO' => 'FRIO',
            'INY' => 'INY',
            'PAC' => 'PAC',
            'SM01' => 'SM01',
            'BPDT' => 'BPDT',
            'EVEN' => 'EVEN',
            'EVSM' => 'EVSM',
            'FRIP' => 'FRIP'
        ];

        $resultado = [];

        foreach ($pendientes as $pendiente) {
            // Buscar saldo correspondiente
            $deposito = $farmaciaToDeposito[$pendiente->farmacia] ?? $pendiente->farmacia;

            $saldo = \App\Models\Medcol6\SaldosMedcol6::where('codigo', $pendiente->codigo)
                ->where('deposito', $deposito)
                ->orderBy('fecha_saldo', 'desc')
                ->first();

            $saldoDisponible = $saldo ? $saldo->saldo : 0;
            $fechaSaldo = $saldo ? $saldo->fecha_saldo->format('Y-m-d') : null;
            $marca = $saldo ? $saldo->marca : null;

            // Determinar estado del saldo
            $estadoSaldo = $saldoDisponible > 0 ? 'CON SALDO' : 'SIN SALDO';

            // Comparar pendiente vs saldo
            $pendienteVsSaldo = '';
            if ($saldoDisponible >= $pendiente->cantidad_pendiente) {
                $pendienteVsSaldo = 'SALDO SUFICIENTE';
            } elseif ($saldoDisponible > 0) {
                $pendienteVsSaldo = 'SALDO PARCIAL';
            } else {
                $pendienteVsSaldo = 'SIN SALDO';
            }

            $resultado[] = [
                'codigo' => $pendiente->codigo,
                'nombre' => $pendiente->nombre,
                'cums' => $pendiente->cums,
                'marca' => $marca,
                'cantidad_pendiente' => (int) $pendiente->cantidad_pendiente,
                'saldo' => (float) $saldoDisponible,
                'pendiente_vs_saldo' => $pendienteVsSaldo,
                'fecha_saldo' => $fechaSaldo,
                'farmacia' => $pendiente->farmacia,
                'estado' => $estadoSaldo
            ];
        }

        // Ordenar por farmacia y luego por nombre
        usort($resultado, function ($a, $b) {
            if ($a['farmacia'] === $b['farmacia']) {
                return strcmp($a['nombre'], $b['nombre']);
            }
            return strcmp($a['farmacia'], $b['farmacia']);
        });

        return response()->json([
            'success' => true,
            'data' => $resultado,
            'total_registros' => count($resultado),
            'fecha_inicio' => $fechaInicio->format('Y-m-d'),
            'fecha_fin' => $fechaFin->format('Y-m-d'),
            'contrato' => $contrato ?? 'Todas las farmacias'
        ]);
    }

    /**
     * Obtener saldo de medicamento específico por código y depósito
     * Esta función busca el saldo disponible del medicamento específico
     * para el depósito donde se generó el pendiente
     */
    public function getSaldoMedicamento(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string',
            'deposito' => 'required|string'
        ]);

        try {
            // Mapeo de centros de producción a depósitos exactos
            $centroToDeposito = [
                'SM01' => 'SM01',
                'DLR1' => 'DLR1',
                'PAC' => 'PAC',
                'EHU1' => 'EHU1',
                'BIO1' => 'BIO1',
                'EM01' => 'EM01',
                'BPDT' => 'BPDT',
                'DPA1' => 'DPA1',
                'EVSM' => 'EVSM',
                'EVEN' => 'EVEN',
                'FRJA' => 'FRJA',
                'FRIO' => 'FRIO',
                'INY' => 'INY',
                // Agregar más mapeos según sea necesario
                'SALUD' => 'SM01',
                'DOLOR' => 'DLR1',
                'FRIP' => 'FRIP'
            ];

            $codigo = trim($request->input('codigo'));
            $centroproduccion = trim($request->input('deposito'));

            // Obtener el depósito correspondiente
            $deposito = $centroToDeposito[$centroproduccion] ?? $centroproduccion;

            Log::info('Consultando saldo para medicamento', [
                'codigo' => $codigo,
                'centroproduccion' => $centroproduccion,
                'deposito_mapeado' => $deposito,
                'user' => Auth::user()->email ?? 'unknown'
            ]);

            // Buscar el saldo más reciente para el código específico y depósito
            $saldo = SaldosMedcol6::where('codigo', $codigo)
                ->where('deposito', $deposito)
                ->orderBy('fecha_saldo', 'desc')
                ->first();

            if ($saldo) {
                $saldoDisponible = (float) $saldo->saldo;
                $estadoSaldo = $saldoDisponible > 0 ? 'CON SALDO' : 'SIN SALDO';

                Log::info('Saldo encontrado', [
                    'codigo' => $codigo,
                    'deposito' => $deposito,
                    'saldo' => $saldoDisponible,
                    'fecha_saldo' => $saldo->fecha_saldo ? $saldo->fecha_saldo->format('Y-m-d') : null
                ]);

                return response()->json([
                    'success' => true,
                    'saldo' => $saldoDisponible,
                    'fecha_saldo' => $saldo->fecha_saldo ? $saldo->fecha_saldo->format('Y-m-d') : null,
                    'nombre_medicamento' => $saldo->nombre,
                    'deposito' => $saldo->deposito,
                    'estado' => $estadoSaldo,
                    'codigo_consultado' => $codigo,
                    'centroproduccion' => $centroproduccion
                ]);
            } else {
                Log::warning('No se encontró saldo para el medicamento', [
                    'codigo' => $codigo,
                    'deposito' => $deposito,
                    'centroproduccion' => $centroproduccion
                ]);

                return response()->json([
                    'success' => true,
                    'saldo' => 0,
                    'fecha_saldo' => null,
                    'nombre_medicamento' => null,
                    'deposito' => $deposito,
                    'estado' => 'SIN REGISTRO',
                    'codigo_consultado' => $codigo,
                    'centroproduccion' => $centroproduccion
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error obteniendo saldo de medicamento específico: ' . $e->getMessage(), [
                'codigo' => $request->input('codigo'),
                'deposito' => $request->input('deposito'),
                'user' => Auth::user()->email ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el saldo del medicamento: ' . $e->getMessage(),
                'saldo' => 0
            ], 500);
        }
    }

    /**
     * Obtener pendientes agrupados por documento de paciente
     */
    public function getPendientesPorPaciente(Request $request)
    {
        $i = Auth::user()->drogueria;

        // Mapeo de droguerías
        $droguerias = [
            "1" => '',
            "2" => 'SM01',
            "3" => 'DLR1',
            "4" => 'PAC',
            "5" => 'EHU1',
            "6" => 'BIO1',
            "8" => 'EM01',
            "9" => 'BPDT',
            "10" => 'DPA1',
            "11" => 'EVSM',
            "12" => 'EVEN',
            "13" => 'FRJA'
        ];

        $drogueria = $droguerias[$i] ?? '';

        if ($request->ajax()) {
            // Query base para obtener pacientes con pendientes
            $query = PendienteApiMedcol6::query()
                ->select(
                    'historia',
                    'documento',
                    'nombre1',
                    'nombre2',
                    'apellido1',
                    'apellido2',
                    'telefres',
                    'direcres',
                    DB::raw('COUNT(*) as total_pendientes'),
                    DB::raw('SUM(CASE WHEN estado = "PENDIENTE" THEN 1 ELSE 0 END) as pendientes_activos'),
                    DB::raw('MAX(fecha_factura) as ultima_factura')
                )
                ->where(function ($q) {
                    $q->where('estado', 'PENDIENTE')
                        ->orWhere('estado', 'TRAMITADO')
                        ->orWhere('estado', 'DESABASTECIDO');
                });

            // Filtrar por droguería si no es admin
            if ($i !== "1" && $drogueria) {
                if ($i == "3") {
                    $query->whereIn('centroproduccion', ['DLR1', 'DPA1']);
                } else {
                    $query->where('centroproduccion', $drogueria);
                }
            }

            // Filtros de fecha si están presentes
            if (!empty($request->fechaini) && !empty($request->fechafin)) {
                $fechaini = Carbon::parse($request->fechaini)->startOfDay();
                $fechafin = Carbon::parse($request->fechafin)->endOfDay();
                $query->whereBetween('fecha_factura', [$fechaini, $fechafin]);
            }

            // Filtro por contrato
            if (!empty($request->contrato)) {
                $query->where('centroproduccion', $request->contrato);
            }

            // Filtro por historia
            if (!empty($request->historia)) {
                $query->where('historia', 'like', '%' . $request->historia . '%');
            }

            $pacientes = $query->groupBy('historia', 'documento', 'nombre1', 'nombre2', 'apellido1', 'apellido2', 'telefres', 'direcres')
                ->orderBy('ultima_factura', 'desc')
                ->get();

            return DataTables()->of($pacientes)
                ->addColumn('historia_documento', function ($paciente) {
                    return $paciente->historia;
                })
                ->addColumn('nombre_completo', function ($paciente) {
                    return trim($paciente->nombre1 . ' ' . $paciente->nombre2 . ' ' . $paciente->apellido1 . ' ' . $paciente->apellido2);
                })
                ->addColumn('ultima_factura_formatted', function ($paciente) {
                    return $paciente->ultima_factura ? Carbon::parse($paciente->ultima_factura)->format('d/m/Y') : '';
                })
                ->addColumn('action', function ($paciente) {
                    $button = '<button type="button" name="ver_pendientes_paciente" 
                                      data-historia="' . $paciente->historia . '" 
                                      class="ver_pendientes_paciente btn btn-sm btn-primary" 
                                      title="Ver pendientes del paciente">
                                    <i class="fas fa-eye"></i> Ver Pendientes
                               </button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return response()->json(['error' => 'Solicitud no válida'], 400);
    }

    /**
     * Obtener los pendientes específicos de un paciente por historia
     */
    public function getPendientesPacienteDetalle(Request $request)
    {
        $request->validate([
            'historia' => 'required|string'
        ]);

        $historia = $request->input('historia');
        $i = Auth::user()->drogueria;

        // Mapeo de droguerías
        $droguerias = [
            "1" => '',
            "2" => 'SM01',
            "3" => 'DLR1',
            "4" => 'PAC',
            "5" => 'EHU1',
            "6" => 'BIO1',
            "8" => 'EM01',
            "9" => 'BPDT',
            "10" => 'DPA1',
            "11" => 'EVSM',
            "12" => 'EVEN',
            "13" => 'FRJA'
        ];

        $drogueria = $droguerias[$i] ?? '';

        try {
            // Obtener datos del paciente usando historia
            $paciente = PendienteApiMedcol6::where('historia', $historia)
                ->select('documento', 'historia', 'nombre1', 'nombre2', 'apellido1', 'apellido2', 'telefres', 'direcres', 'cantedad')
                ->first();

            if (!$paciente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paciente no encontrado con esa historia clínica'
                ], 404);
            }

            Log::info('Buscando pendientes para paciente', [
                'historia' => $historia,
                'documento' => $paciente->documento,
                'nombre1' => $paciente->nombre1,
                'apellido1' => $paciente->apellido1
            ]);

            // Query para los pendientes del paciente usando historia
            $query = PendienteApiMedcol6::where('historia', $historia)
                ->where(function ($q) {
                    $q->where('estado', 'PENDIENTE')
                        ->orWhere('estado', 'TRAMITADO')
                        ->orWhere('estado', 'VENCIDO')
                        ->orWhere('estado', 'DESABASTECIDO');
                });

            // Filtrar por droguería si no es admin
            if ($i !== "1" && $drogueria) {
                if ($i == "3") {
                    $query->whereIn('centroproduccion', ['DLR1', 'DPA1']);
                } else {
                    $query->where('centroproduccion', $drogueria);
                }
            }

            $pendientes = $query->orderBy('fecha_factura', 'desc')->get();

            Log::info('Pendientes encontrados para el paciente', [
                'cantidad_pendientes' => $pendientes->count(),
                'historia' => $historia,
                'documento' => $paciente->documento
            ]);

            return response()->json([
                'success' => true,
                'paciente' => [
                    'historia' => $historia,
                    'documento' => $paciente->documento,
                    'nombre_completo' => trim($paciente->nombre1 . ' ' . $paciente->nombre2 . ' ' . $paciente->apellido1 . ' ' . $paciente->apellido2),
                    'telefono' => $paciente->telefres,
                    'direccion' => $paciente->direcres,
                    'edad' => $paciente->cantedad
                ],
                'pendientes' => $pendientes->map(function ($pendiente) {
                    return [
                        'id' => $pendiente->id,
                        'factura' => $pendiente->factura,
                        'fecha_factura' => $pendiente->fecha_factura ? Carbon::parse($pendiente->fecha_factura)->format('d/m/Y') : '',
                        'codigo' => $pendiente->codigo,
                        'nombre' => $pendiente->nombre,
                        'cums' => $pendiente->cums,
                        'cantord' => $pendiente->cantord,
                        'cantdpx' => $pendiente->cantdpx,
                        'saldo_pendiente' => $pendiente->cantord - $pendiente->cantdpx,
                        'estado' => $pendiente->estado,
                        'centroproduccion' => $pendiente->centroproduccion,
                        'observaciones' => $pendiente->observaciones,
                        'fecha_factura_raw' => $pendiente->fecha_factura
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Error obteniendo pendientes del paciente por historia: ' . $e->getMessage(), [
                'historia' => $historia,
                'user' => Auth::user()->email ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los pendientes del paciente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar múltiples pendientes por lotes
     */
    public function updateMultiplesPendientes(Request $request)
    {
        $request->validate([
            'pendientes' => 'required|array',
            'pendientes.*.id' => 'required|integer|exists:pendiente_api_medcol6,id',
            'pendientes.*.cantdpx' => 'required|numeric|min:0',
            'pendientes.*.estado' => 'required|in:PENDIENTE,ENTREGADO,TRAMITADO,DESABASTECIDO,ANULADO,VENCIDO,SIN CONTACTO',
            'pendientes.*.fecha_entrega' => 'nullable|date',
            'pendientes.*.fecha_impresion' => 'nullable|date',
            'pendientes.*.fecha_anulado' => 'nullable|date',
            'pendientes.*.fecha_sincontacto' => 'nullable|date',
            'pendientes.*.observaciones' => 'nullable|string|max:1000',
            'pendientes.*.doc_entrega' => 'nullable|string|max:50',
            'pendientes.*.factura_entrega' => 'nullable|string|max:50'
        ]);

        try {
            DB::beginTransaction();

            $actualizados = 0;
            $errores = [];

            foreach ($request->pendientes as $pendienteData) {
                try {
                    $pendiente = PendienteApiMedcol6::findOrFail($pendienteData['id']);

                    // Validar que la cantidad entregada no sea mayor a la ordenada
                    if ($pendienteData['cantdpx'] > $pendiente->cantord) {
                        $errores[] = "ID {$pendienteData['id']}: Cantidad entregada no puede ser mayor a cantidad ordenada";
                        continue;
                    }

                    // Validar que la fecha_entrega no sea menor a fecha_factura
                    if (!empty($pendienteData['fecha_entrega']) && !empty($pendiente->fecha_factura)) {
                        $fechaEntrega = Carbon::parse($pendienteData['fecha_entrega']);
                        $fechaFactura = Carbon::parse($pendiente->fecha_factura);

                        if ($fechaEntrega->lt($fechaFactura)) {
                            $errores[] = "ID {$pendienteData['id']}: Fecha de entrega no puede ser menor a fecha del pendiente ({$fechaFactura->format('d/m/Y')})";
                            continue;
                        }
                    }

                    $updateData = [
                        'cantdpx' => $pendienteData['cantdpx'],
                        'estado' => $pendienteData['estado'],
                        'usuario' => Auth::user()->email,
                        'updated_at' => now()
                    ];

                    // Agregar fecha según el estado
                    switch ($pendienteData['estado']) {
                        case 'ENTREGADO':
                            // Usar fecha personalizada si se proporciona, sino fecha actual
                            if (!empty($pendienteData['fecha_entrega'])) {
                                $updateData['fecha_entrega'] = Carbon::parse($pendienteData['fecha_entrega']);
                            } else {
                                $updateData['fecha_entrega'] = now();
                            }
                            
                            // Agregar campos de entrega
                            if (isset($pendienteData['doc_entrega'])) {
                                $updateData['doc_entrega'] = $pendienteData['doc_entrega'];
                            }
                            if (isset($pendienteData['factura_entrega'])) {
                                $updateData['factura_entrega'] = $pendienteData['factura_entrega'];
                            }
                            break;
                            
                        case 'TRAMITADO':
                        case 'DESABASTECIDO':
                            // Usar fecha personalizada si se proporciona, sino fecha actual
                            if (!empty($pendienteData['fecha_impresion'])) {
                                $updateData['fecha_impresion'] = Carbon::parse($pendienteData['fecha_impresion']);
                            } else {
                                $updateData['fecha_impresion'] = now();
                            }
                            break;
                            
                        case 'ANULADO':
                            // Usar fecha personalizada si se proporciona, sino fecha actual
                            if (!empty($pendienteData['fecha_anulado'])) {
                                $updateData['fecha_anulado'] = Carbon::parse($pendienteData['fecha_anulado']);
                            } else {
                                $updateData['fecha_anulado'] = now();
                            }
                            break;
                            
                        case 'SIN CONTACTO':
                            // Para SIN CONTACTO usamos updated_at
                            if (!empty($pendienteData['fecha_sincontacto'])) {
                                $updateData['updated_at'] = Carbon::parse($pendienteData['fecha_sincontacto']);
                            } else {
                                $updateData['updated_at'] = now();
                            }
                            break;
                    }

                    $pendiente->update($updateData);

                    // Crear observación con el texto ingresado por el usuario o generar automática
                    $observacionTexto = '';
                    
                    if (!empty($pendienteData['observaciones'])) {
                        // Si el usuario ingresó observaciones específicas, usarlas
                        $observacionTexto = $pendienteData['observaciones'];
                    } else {
                        // Si no hay observaciones específicas, generar observación automática
                        $observacionTexto = 'Actualización masiva: Estado cambiado a ' . $pendienteData['estado'] . ' con cantidad entregada: ' . $pendienteData['cantdpx'];
                        
                        if ($pendienteData['estado'] === 'ENTREGADO' && !empty($pendienteData['fecha_entrega'])) {
                            $observacionTexto .= ' - Fecha de entrega: ' . Carbon::parse($pendienteData['fecha_entrega'])->format('d/m/Y');
                        }
                    }

                    ObservacionesApiMedcol6::create([
                        'pendiente_id' => $pendiente->id,
                        'observacion' => $observacionTexto,
                        'usuario' => Auth::user()->email,
                        'estado' => $pendienteData['estado']
                    ]);

                    $actualizados++;
                } catch (\Exception $e) {
                    $errores[] = "ID {$pendienteData['id']}: " . $e->getMessage();
                }
            }

            DB::commit();

            Log::info('Actualización masiva de pendientes completada', [
                'user' => Auth::user()->email,
                'actualizados' => $actualizados,
                'errores' => count($errores)
            ]);

            return response()->json([
                'success' => true,
                'message' => "Se actualizaron {$actualizados} pendientes correctamente.",
                'actualizados' => $actualizados,
                'errores' => $errores
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error en actualización masiva de pendientes: ' . $e->getMessage(), [
                'user' => Auth::user()->email,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error en la actualización masiva: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sincontacto(Request $request)
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
            default:
                $drogueria = '';
                break;
        }
    
        if ($request->ajax()) {
            // Iniciar la consulta
            $query = DB::table('pendiente_api_medcol6 as p')
                ->leftJoin(DB::raw('(SELECT pendiente_id, observacion, created_at,
                                    ROW_NUMBER() OVER (PARTITION BY pendiente_id ORDER BY created_at DESC) as rn
                                    FROM observaciones_api_medcol6) as o'), 
                    function($join) {
                        $join->on('p.id', '=', 'o.pendiente_id')
                             ->where('o.rn', '=', 1);
                    })
                ->select('p.*', 'o.observacion as ultima_observacion', 'o.created_at as fecha_ultima_observacion')
                ->where(function($q) {
                    $q->where('p.estado', 'SIN CONTACTO')
                      ->orWhereNull('p.estado');
                });
    
            // Filtrar por centro de producción si no es '1'
            if (Auth::user()->drogueria !== '1') {
                $query->where('p.centroproduccion', $drogueria);
            }
    
            // Filtrar por fechas si están presentes
            if (!empty($request->fechaini) && !empty($request->fechafin)) {
                $fechaini = Carbon::parse($request->fechaini)->startOfDay();
                $fechafin = Carbon::parse($request->fechafin)->endOfDay();
                $query->whereBetween('p.fecha_factura', [$fechaini, $fechafin]);
            } else {
                // Si no se proporcionan fechas, usar las fechas por defecto
                $query->whereBetween('p.fecha_factura', [$fechaAi, $fechaAf]);
            }
    
            // Filtrar por contrato si está presente
            if (!empty($request->contrato)) {
                $query->where('p.centroproduccion', $request->contrato);
            }
            
            // Excluir centros de producción específicos
            $query->whereNotIn('p.centroproduccion', ['FSIO','FSOS', 'FSAU', 'ENMP']);
            
            // Ordenar y obtener los resultados
            $pendiente_api_medcol6 = $query->orderBy('p.id')->get();
    
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
}
