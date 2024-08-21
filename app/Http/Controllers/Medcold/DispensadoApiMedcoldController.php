<?php

namespace App\Http\Controllers\Medcold;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Listas\ListasDetalle;
use App\Models\Medcold\DispensadoApiMedcold;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;

class DispensadoApiMedcoldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * 
     * 
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
        }

        if (Auth::user()->drogueria == '1') {

            $dispensado =  DispensadoApiMedcold::where([['estado', 'DISPENSADO'], ['fecha_suministro', '>=', '2024-03-01 00:00:00']])->count();
            $revisado =  DispensadoApiMedcold::where([['estado', 'REVISADO'],  ['fecha_suministro', '>=', '2024-03-01 00:00:00']])->count();
            $anulado =  DispensadoApiMedcold::where([['estado', 'ANULADA'],  ['fecha_suministro', '>=', '2024-03-01 00:00:00']])->count();
        } else {

            $dispensado =  DispensadoApiMedcold::where([['estado', 'DISPENSADO'], ['centroprod', $drogueria],  ['fecha_suministro', '>=', '2024-03-01 00:00:00']])->count();
            $revisado =  DispensadoApiMedcold::where([['estado', 'REVISADO'], ['centroprod', $drogueria],  ['fecha_suministro', '>=', '2024-03-01 00:00:00']])->count();
            $anulado =  DispensadoApiMedcold::where([['estado', 'ANULADA'], ['centroprod', $drogueria],  ['fecha_suministro', '>=', '2024-03-01 00:00:00']])->count();
        }



        return response()->json(['dispensado' => $dispensado, 'revisado' => $revisado, 'anulado' => $anulado]);
    }


    public function index(Request $request)
    {
        return view('menu.Medcold.indexDispensado');
    }
    public function index1(Request $request)
    {
        $fechaAi = now()->toDateString() . " 00:00:00";
        $fechaAf = now()->toDateString() . " 23:59:59";

        $droguerias = [
            '' => 'Todas',
            '2' => 'SALUD',
            '3' => 'DOLOR',
            '4' => 'PAC',
            '5' => 'EHU1',
            '6' => 'BIO1'
        ];

        $drogueria = $droguerias[Auth::user()->drogueria] ?? '';

        if ($request->ajax()) {

            $dispensado_api_medcold = DispensadoApiMedcold::query();


            if (!empty($drogueria)) {

                $dispensado_api_medcold->where('centroprod', $drogueria);
            }



            if (!empty($request->fechaini) && !empty($request->fechafin)) {
                $fechaini = new Carbon($request->fechaini);
                $fechaini = $fechaini->toDateString();

                $fechafin = new Carbon($request->fechafin);
                $fechafin = $fechafin->toDateString();


                $Resultados1 = $dispensado_api_medcold->whereBetween('fecha_suministro', [$fechaini . ' 00:00:00', $fechafin . ' 23:59:59']);
            } elseif (empty($request->fechaini) && empty($request->fechafin)) {

                $dispensado_api_medcold->whereBetween('fecha_suministro', [$fechaAi, $fechaAf]);
            }

            // Agregar una subconsulta para calcular la suma de la cuota moderadora
            $dispensado_api_medcold->selectRaw('*, 
                (CASE 
                    WHEN ROW_NUMBER() OVER(PARTITION BY factura ORDER BY id) = 1 
                        THEN (SELECT SUM(cuota_moderadora) FROM dispensado_medcold AS d2 WHERE d2.factura = dispensado_medcold.factura) 
                    ELSE 0 
                END) AS cuota_moderadora_sumada');

            $dispensado_api_medcold->where('estado', 'DISPENSADO')->orWhereNull('estado');

            $resultados = $dispensado_api_medcold->orderBy('fecha_suministro')->get();

            //dd($resultados);

            return DataTables()->of($resultados)
                ->addColumn('action', function ($pendiente) {
                    return '<input class="add_medicamento checkbox-large case tooltipsC" type="checkbox" title="Selecciona Orden" id="' . $pendiente->id . '" value="' . $pendiente->id . '">';
                })
                ->addColumn('fecha_orden', function ($pendiente) {
                    return '<input type="date" name="date_orden" id="' . $pendiente->id . '"
                                class="show_detail btn btn-xl bg-secondary tooltipsC" title="Fecha">';
                })
                ->addColumn('numero_entrega1', function ($pendiente) {
                    return '<input type="text" name="entrega" id="' . $pendiente->id . '"
                                class="show_detail btn btn-xl bg-secondary tooltipsC" title="entrega">';
                })
                ->addColumn('diagnostico', function ($pendiente) {
                    return '<select name="dx" id="' . $pendiente->id . '"
                                class="diagnos form-control select2bs4" style="width: 100%;" required></select>';
                })

                ->addColumn('autorizacion1', function ($pendiente) {
                    return '<input type="text" name="autorizacion" id="' . $pendiente->id . '"
                                class="show_detail btn btn-xl bg-warning tooltipsC"  title="autorizacion"
                                value="' . $pendiente->autorizacion . '">';
                })
                ->addColumn('mipres1', function ($pendiente) {
                    return '<input type="text" name="mipres" id="' . $pendiente->id . '"
                                class="show_detail btn btn-xl bg-warning tooltipsC" title="mipres">';
                })
                ->addColumn('reporte_entrega1', function ($pendiente) {
                    return '<input type="text" name="reporte" id="' . $pendiente->id . '"
                                class="show_detail btn btn-xl bg-info tooltipsC" title="Reporte de entrega">';
                })
                ->addColumn('id_medico1', function ($pendiente) {
                    return '<input type="text" name="id_medico1" id="' . $pendiente->id . '"
                                class="show_detail btn btn-xl bg-info tooltipsC" title="Id medico"
                                value="' . $pendiente->id_medico . '">';
                })
                ->addColumn('medico1', function ($pendiente) {
                    return '<input type="text" name="medico1" id="' . $pendiente->id . '"
                                class="show_detail btn btn-xl bg-info tooltipsC" title="Medico"
                                value="' . $pendiente->medico . '">';
                })
                ->addColumn('copago1', function ($pendiente) {
                    return '<input type="text" name="medico1" id="' . $pendiente->id . '"
                        class="show_detail btn btn-xl bg-info tooltipsC" title="Copago"
                        value="' . $pendiente->cuota_moderadora_sumada . '">';
                })
                ->addColumn('ips', function ($pendiente) {
                    return '<select name="ips" id="' . $pendiente->id . '"
                                class="ipsss form-control select2bs4" style="width: 100%;" required></select>';
                })
                ->rawColumns([
                    'action', 'fecha_orden', 'numero_entrega1', 'diagnostico',
                    'autorizacion1', 'mipres1', 'reporte_entrega1', 'id_medico1', 'medico1', 'copago1', 'ips'
                ])
                ->make(true);
        }

        return view('menu.Medcold.indexDispensado', ['droguerias' => $droguerias]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createdispensadoapi(Request $request)
    {
        $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = '123456';
        $usuario = Auth::user()->email;


        try {


            $response = Http::post("http://190.85.46.246:8000/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];



            if ($token) {


                try {



                    $responsefacturas = Http::withToken($token)->get("http://190.85.46.246:8000/api/dispensadoapi");



                    $facturassapi = $responsefacturas->json()['data'];

                    $contador = 0;



                    foreach ($facturassapi as $factura) {

                        $existe = DispensadoApiMedcold::where([['factura', $factura['factura']], ['codigo', $factura['codigo']]])->count();

                        $dispensados = [];

                        if ($existe == 0 || $existe == '') {

                            $dispensados[] = [
                                'idusuario'  => trim($factura['idusuario']),
                                'tipo'  => trim($factura['tipo']),
                                'facturad'  => trim($factura['facturad']),
                                'factura'  => trim($factura['factura']),
                                'tipodocument'  => trim($factura['tipodocument']),
                                'historia'  => trim($factura['historia']),
                                'autorizacion'  => trim($factura['autorizacion']),
                                'cums'  => trim($factura['cums']),
                                'expediente'  => trim($factura['expediente']),
                                'consecutivo'  => trim($factura['consecutivo']),
                                'cums_rips'  => trim($factura['cums_rips']),
                                'codigo'  => trim($factura['codigo']),
                                'tipo_medicamento'  => trim($factura['tipo_medicamento']),
                                'nombre_generico'  => trim($factura['nombre_generico']),
                                'atc'  => trim($factura['atc']),
                                'forma'  => trim($factura['forma']),
                                'concentracion'  => trim($factura['concentracion']),
                                'unidad_medicamento'  => trim($factura['unidad_medicamento']),
                                'numero_unidades'  => trim($factura['numero_unidades']),
                                'regimen'  => trim($factura['regimen']),
                                'paciente'  => trim($factura['paciente']),
                                'primer_apellido'  => trim($factura['primer_apellido']),
                                'segundo_apellido'  => trim($factura['segundo_apellido']),
                                'primer_nombre'  => trim($factura['primer_nombre']),
                                'segundo_nombre'  => trim($factura['segundo_nombre']),
                                'cuota_moderadora'  => trim($factura['cuota_moderadora']),
                                'copago'  => trim($factura['copago']),
                                'numero_entrega'  => trim($factura['numero_entrega']),
                                'fecha_ordenamiento'  => null,
                                'fecha_suministro'  => trim($factura['fecha_suministro']),
                                'dx'  => trim($factura['dx']),
                                'id_medico'  => trim($factura['id_medico']),
                                'medico'  => trim($factura['medico']),
                                'mipres'  => trim($factura['mipres']),
                                'precio_unitario'  => trim($factura['precio_unitario']),
                                'valor_total'  => trim($factura['valor_total']),
                                'reporte_entrega_nopbs'  => trim($factura['reporte_entrega_nopbs']),
                                'estado'  => trim($factura['estado']),
                                'centroprod'  => trim($factura['centroprod']),
                                'drogueria'  => trim($factura['drogueria']),
                                'cajero'  => trim($factura['cajero'])
                            ];

                            if (!empty($dispensados)) {
                                DispensadoApiMedcold::insert($dispensados);
                            }

                            $contador++;
                        }
                    }

                    /*if (!empty($dispensados)) {
              DispensadoApiMedcold::insert($dispensados);
            }*/

                    Http::withToken($token)->get("http://190.85.46.246:8000/api/closeallacceso");

                    Log::info('Desde la web syncapi Dolor ' . $contador . ' Lineas dispensadas' . ' Usuario: ' . $usuario);


                    return response()->json([
                        ['respuesta' => $contador . ' Lineas creadas', 'titulo' => 'Mixed lineas', 'icon' => 'success', 'position' => 'bottom-left']
                    ]);
                } catch (\Exception $e) {

                    // Manejo de la excepción
                    Log::error($e->getMessage()); // Registrar el error en los logs de Laravel

                    return response()->json([
                        ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
                    ]);
                }
            }
        } catch (\Exception $e) {



            try {


                $response = Http::post("http://192.168.50.98:8000/api/acceso", [
                    'email' =>  $email,
                    'password' => $password,
                ]);

                $token = $response->json()["token"];

                if ($token) {

                    try {




                        $responsefacturas = Http::withToken($token)->get("http://192.168.50.98:8000/api/dispensadoapi");

                        $facturassapi = $responsefacturas->json()['data'];

                        $contador = 0;


                        foreach ($facturassapi as $factura) {

                            $existe = DispensadoApiMedcold::where([['factura', $factura['factura']], ['codigo', $factura['codigo']]])->count();

                            $dispensados = [];

                            if ($existe == 0 || $existe == '') {

                                $dispensados[] = [

                                    'idusuario'  => trim($factura['idusuario']),
                                    'tipo'  => trim($factura['tipo']),
                                    'facturad'  => trim($factura['facturad']),
                                    'factura'  => trim($factura['factura']),
                                    'tipodocument'  => trim($factura['tipodocument']),
                                    'historia'  => trim($factura['historia']),
                                    'autorizacion'  => trim($factura['autorizacion']),
                                    'cums'  => trim($factura['cums']),
                                    'expediente'  => trim($factura['expediente']),
                                    'consecutivo'  => trim($factura['consecutivo']),
                                    'cums_rips'  => trim($factura['cums_rips']),
                                    'codigo'  => trim($factura['codigo']),
                                    'tipo_medicamento'  => trim($factura['tipo_medicamento']),
                                    'nombre_generico'  => trim($factura['nombre_generico']),
                                    'atc'  => trim($factura['atc']),
                                    'forma'  => trim($factura['forma']),
                                    'concentracion'  => trim($factura['concentracion']),
                                    'unidad_medicamento'  => trim($factura['unidad_medicamento']),
                                    'numero_unidades'  => trim($factura['numero_unidades']),
                                    'regimen'  => trim($factura['regimen']),
                                    'paciente'  => trim($factura['paciente']),
                                    'primer_apellido'  => trim($factura['primer_apellido']),
                                    'segundo_apellido'  => trim($factura['segundo_apellido']),
                                    'primer_nombre'  => trim($factura['primer_nombre']),
                                    'segundo_nombre'  => trim($factura['segundo_nombre']),
                                    'cuota_moderadora'  => trim($factura['cuota_moderadora']),
                                    'copago'  => trim($factura['copago']),
                                    'numero_entrega'  => trim($factura['numero_entrega']),
                                    'fecha_ordenamiento'  => null,
                                    'fecha_suministro'  => trim($factura['fecha_suministro']),
                                    'dx'  => trim($factura['dx']),
                                    'id_medico'  => trim($factura['id_medico']),
                                    'medico'  => trim($factura['medico']),
                                    'mipres'  => trim($factura['mipres']),
                                    'precio_unitario'  => trim($factura['precio_unitario']),
                                    'valor_total'  => trim($factura['valor_total']),
                                    'reporte_entrega_nopbs'  => trim($factura['reporte_entrega_nopbs']),
                                    'estado'  => trim($factura['estado']),
                                    'centroprod'  => trim($factura['centroprod']),
                                    'drogueria'  => trim($factura['drogueria']),
                                    'cajero'  => trim($factura['cajero'])
                                ];

                                if (!empty($dispensados)) {
                                    DispensadoApiMedcold::insert($dispensados);
                                }

                                $contador++;
                            }
                        }



                        Http::withToken($token)->get("http://192.168.10.27/api/closeallacceso");


                        Log::info('Desde la web syncapi Dolor local' . $contador . ' Lineas dispensadas' . ' Usuario: ' . $usuario);

                        return response()->json([
                            ['respuesta' => $contador . ' Lineas creadas', 'titulo' => 'Mixed lineas', 'icon' => 'success', 'position' => 'bottom-left']
                        ]);


                        /*return response()->json([
                ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
            ]);*/
                    } catch (\Exception $e) {


                        // Manejo de la excepción
                        \Log::error($e->getMessage()); // Registrar el error en los logs de Laravel


                        return response()->json([
                            ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
                        ]);
                    }
                }
            } catch (\Exception $e) {


                // Manejo de la excepción
                Log::error($e->getMessage()); // Registrar el error en los logs de Laravel


                return response()->json([
                    ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
                ]);
            }
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function disrevisado(Request $request)
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
        }



        //dd($drogueria);

        if ($request->ajax()) {

            if (Auth::user()->drogueria == '1') {

                $dispensado_api_medcol4 = DispensadoApiMedcold::where([['estado', 'REVISADO']])
                    ->orWhere('estado', NULL)
                    ->orderBy('id')
                    ->get()
                    ->map(function ($item) {
                        $ipsId = $item->ips;
                        $lista = ListasDetalle::where('id', $ipsId)->first();
                        $item->ips_nombre = $lista ? $lista->nombre : '';
                        return $item;
                    });
            } else {
                $dispensado_api_medcol4 = DispensadoApiMedcold::where([['estado', 'REVISADO'], ['centroprod', $drogueria]])
                    ->orWhere('estado', NULL)
                    ->orderBy('id')
                    ->get()
                    ->map(function ($item) {
                        $ipsId = $item->ips;
                        $lista = ListasDetalle::where('id', $ipsId)->first();
                        $item->ips_nombre = $lista ? $lista->nombre : '';
                        return $item;
                    });
            }
            return DataTables()->of($dispensado_api_medcol4)
                ->addColumn('action', function ($pendiente) {
                    $button = '<button type="button" name="show_detail" id="' . $pendiente->id . '" class="show_detail btn btn-app bg-secondary tooltipsC" title="Detalle">
                              <span class="badge bg-teal">Detalle</span>
                              <i class="fas fa-prescription-bottle-alt"></i>
                          </button>';

                    $button2 = '<button type="button" name="edit_pendiente" id="' . $pendiente->id . '" class="edit_pendiente btn btn-app bg-info tooltipsC" title="Editar">
                              <span class="badge bg-teal">Editar</span>
                              <i class="fas fa-pencil-alt"></i>
                          </button>';

                    $button3 = '<button type="button" name="gestionar_masivamente" id="' . $pendiente->id . '" class="gestionar_masivamente btn btn-app bg-warning tooltipsC" title="Gestionar Masivamente">
                              <span class="badge bg-teal">Gestionar</span>
                              <i class="fas fa-users"></i>
                          </button>';

                    return $button . ' ' . $button2 . ' ' . $button3;
                })
                ->addColumn('fecha Orden', function ($pendiente) {
                    $inputdate = '<input type="date" name="date_orden" id="' . $pendiente->id . '
                    " class="show_detail btn btn-app bg-secondary tooltipsC" title="Fecha">';

                    return $inputdate;
                })
                ->rawColumns(['action', 'fecha Orden'])
                ->make(true);
        }

        return view('menu.Medcol3.indexDispensado');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adddispensacionarray(Request $request)
    {

        $request->validate([
            'data.*.diagnostico' => 'required', // Campo 'diagnostico' requerido
            'data.*.fecha_orden' => 'required', // Campo 'fecha_orden' requerido
            'data.*.ips' => 'required', // Campo 'ips' requerido
            'data.*.numero_entrega1' => 'required', // Campo 'numero_entrega1' requerido
            'data.*.fecha_suministro',
        ]);


        $add_factura = $request->data;
        $fecha_suministro = Carbon::parse($request->input('fecha_suministro'))->format('Y-m-d');

        foreach ($add_factura as $add) {
            $fecha_orden = Carbon::parse($add['fecha_orden']);

            // Verificar si la fecha_orden es mayor a la fecha_suministro
            if ($fecha_orden->gt($fecha_suministro)) {
                return response()->json([
                    'errors' => [
                        'fecha_orden' => [
                            'La Fecha de Ordenamiento no puede ser superior a la Fecha de Suministro'
                        ]
                    ]
                ], 422);
            }

            DispensadoApiMedcold::where('id', $add['ID'])
                ->update(
                    [
                        'autorizacion'  => trim($add['autorizacion1']),
                        'copago'  => trim($add['copago1']),
                        'numero_entrega'  => trim($add['numero_entrega1']),
                        'fecha_ordenamiento'  => trim($add['fecha_orden']),
                        'dx'  => trim($add['diagnostico']),
                        'ips'  => trim($add['ips']),
                        'id_medico'  => trim($add['id_medico1']),
                        'medico'  => trim($add['medico1']),
                        'mipres'  => trim($add['mipres1']),
                        'reporte_entrega_nopbs'  => trim($add['reporte_entrega1']),
                        'estado'  => trim($add['estado']),
                        'user_id'  => trim($add['user_id']),
                        'updated_at' => now()
                    ]

                );
        }


        return response()->json(['success' => 'ok']);
    }


    public function updateanuladosapi(Request $request)
    {
        $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = '123456';
        $usuario = Auth::user()->email;

        try {
            $response = Http::post("http://190.85.46.246:8000/api/acceso", [
                'email' => $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];

            if ($token) {
                try {
                    $responsefacturas = Http::withToken($token)->get("http://190.85.46.246:8000/api/anuladosapi");
                    $facturassapi = $responsefacturas->json()['data'];

                    $contadorActualizados = 0;

                    foreach ($facturassapi as $factura) {
                        if (isset($factura['factura'])) {
                            $actualizados = DispensadoApiMedcold::where('factura', $factura['factura'])
                                ->whereIn('estado', ['DISPENSADO', 'REVISADO'])
                                ->update([
                                    'estado' => 'ANULADA',
                                    'updated_at' => now()
                                ]);
                        } elseif (isset($factura['factura_electronica'])) {
                            $actualizados = DispensadoApiMedcold::where(DB::raw("CONCAT(documento_origen, factura_origen)"), $factura['factura_electronica'])
                                ->whereIn('estado', ['DISPENSADO', 'REVISADO'])
                                ->update([
                                    'estado' => 'ANULADA',
                                    'updated_at' => now()
                                ]);
                        }

                        if ($actualizados) {
                            $contadorActualizados++;
                        }
                    }

                    // Close the acceso API
                    Http::withToken($token)->get("http://190.85.46.246:8000/api/closeallacceso");

                    // Consuming the other APIs
                    $accesoResponse = Http::post("http://192.168.50.98:8000/api/acceso", [
                        'email' => $email,
                        'password' => $password,
                    ]);

                    $accesoToken = $accesoResponse->json()["token"];

                    if ($accesoToken) {
                        $dispensadoResponse = Http::withToken($accesoToken)->get("http://192.168.50.98:8000/api/anuladosapi");
                        $dispensadoData = $dispensadoResponse->json()['data'];
                        $contadorActualizados = 0;

                        foreach ($dispensadoData as $factura) {
                            if (isset($factura['factura'])) {
                                $actualizados = DispensadoApiMedcold::where('factura', $factura['factura'])
                                    ->whereIn('estado', ['DISPENSADO', 'REVISADO'])
                                    ->update([
                                        'estado' => 'ANULADA',
                                        'updated_at' => now()
                                    ]);
                            } elseif (isset($factura['factura_electronica'])) {
                                $actualizados = DispensadoApiMedcold::where(DB::raw("CONCAT(documento_origen, factura_origen)"), $factura['factura_electronica'])
                                    ->whereIn('estado', ['DISPENSADO', 'REVISADO'])
                                    ->update([
                                        'estado' => 'ANULADA',
                                        'updated_at' => now()
                                    ]);
                            }

                            if ($actualizados) {
                                $contadorActualizados++;
                            }
                        }

                        // Close the acceso API
                        Http::withToken($accesoToken)->get("http://192.168.50.98/api/closeallacceso");
                    }

                    Log::info('Desde la web syncapi autopista anulados', [
                        'lineas_actualizadas' => $contadorActualizados,
                        'usuario' => $usuario
                    ]);

                    return response()->json(
                        [
                            [
                                'respuesta' => $contadorActualizados . " Facturas anuladas",
                                'titulo' => 'Lineas Actualizadas',
                                'icon' => 'success',
                                'position' => 'bottom-left'
                            ]
                        ]
                    );
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





    public function disanulado(Request $request)
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
        }



        //dd($drogueria);

        if ($request->ajax()) {

            if (Auth::user()->drogueria == '1') {

                $dispensado_api_medcol4 = DispensadoApiMedcold::where([['estado', 'ANULADA']])
                    ->orWhere('estado', NULL)
                    ->orderBy('id')
                    ->get()
                    ->map(function ($item) {
                        $ipsId = $item->ips;
                        $lista = ListasDetalle::where('id', $ipsId)->first();
                        $item->ips_nombre = $lista ? $lista->nombre : '';
                        return $item;
                    });
            } else {
                $dispensado_api_medcol4 = DispensadoApiMedcold::where([['estado', 'ANULADA'], ['centroprod', $drogueria]])
                    ->orWhere('estado', NULL)
                    ->orderBy('id')
                    ->get()
                    ->map(function ($item) {
                        $ipsId = $item->ips;
                        $lista = ListasDetalle::where('id', $ipsId)->first();
                        $item->ips_nombre = $lista ? $lista->nombre : '';
                        return $item;
                    });
            }
            return DataTables()->of($dispensado_api_medcol4)
                ->addColumn('action', function ($pendiente) {
                    $button = '<button type="button" name="show_detail" id="' . $pendiente->id . '" class="show_detail btn btn-app bg-secondary tooltipsC" title="Detalle">
                              <span class="badge bg-teal">Detalle</span>
                              <i class="fas fa-prescription-bottle-alt"></i>
                          </button>';

                    $button2 = '<button type="button" name="edit_pendiente" id="' . $pendiente->id . '" class="edit_pendiente btn btn-app bg-info tooltipsC" title="Editar">
                              <span class="badge bg-teal">Editar</span>
                              <i class="fas fa-pencil-alt"></i>
                          </button>';

                    $button3 = '<button type="button" name="gestionar_masivamente" id="' . $pendiente->id . '" class="gestionar_masivamente btn btn-app bg-warning tooltipsC" title="Gestionar Masivamente">
                              <span class="badge bg-teal">Gestionar</span>
                              <i class="fas fa-users"></i>
                          </button>';

                    return $button . ' ' . $button2 . ' ' . $button3;
                })
                ->addColumn('fecha Orden', function ($pendiente) {
                    $inputdate = '<input type="date" name="date_orden" id="' . $pendiente->id . '
                    " class="show_detail btn btn-app bg-secondary tooltipsC" title="Fecha">';

                    return $inputdate;
                })
                ->rawColumns(['action', 'fecha Orden'])
                ->make(true);
        }

        return view('menu.Medcol3.indexDispensado');
    }

    public function buscar($factura)
    {
        // Crear una instancia de la consulta principal sin ejecutarla de inmediato
        $dispensado_api_medcol4 = DispensadoApiMedcold::query();

        // Agregar una subconsulta para calcular la suma de la cuota moderadora usando selectRaw
        $dispensado_api_medcol4->selectRaw('*, 
        (CASE 
            WHEN ROW_NUMBER() OVER(PARTITION BY factura ORDER BY id) = 1 
                THEN (SELECT SUM(cuota_moderadora) FROM dispensado_medcold AS d2 WHERE d2.factura = dispensado_medcold.factura) 
            ELSE 0 
        END) AS cuota_moderadora_sumada');

        // Aplicar condiciones de búsqueda adicionales
        $resultados = $dispensado_api_medcol4
            ->where('factura', $factura)
            ->where('estado', 'DISPENSADO')
            ->orderBy('fecha_suministro')
            ->get();

        // Verificar si se encontraron resultados
        if ($resultados->isNotEmpty()) {
            // Mapear los resultados a un array asociativo para incluir campos adicionales
            $data = $resultados->map(function ($item) {
                // Convertir el modelo a un array asociativo
                $dataArray = $item->toArray();

                // Agregar campos HTML personalizados a los datos resultantes
                $dataArray['action'] = '<input class="add_medicamento checkbox-large checkbox2 tooltipsC" type="checkbox" title="Seleccionar" id="' . $item->id . '" value="' . $item->id . '">';
                $dataArray['autorizacion2'] = '<input type="text" name="autorizacion" id="' . $item->id . '" class="show_detail btn btn-xl bg-warning tooltipsC" style="max-width: 100%;" title="autorizacion" value="' . $item->autorizacion . '">';
                $dataArray['mipres2'] = '<input type="text" name="mipres" id="' . $item->id . '" class="show_detail form-control btn bg-info tooltipsC" style="max-width: 100%;" title="mipres">';
                $dataArray['reporte_entrega2'] = '<input type="text" name="reporte" id="' . $item->id . '" class="show_detail form-control btn bg-info tooltipsC" style="max-width: 100%;" title="Reporte de entrega">';
                $dataArray['cuota_moderadora2'] = '<input type="text" name="cuota_moderadora" id="' . $item->id . '" class="show_detail btn btn-xl bg-info tooltipsC" style="max-width: 100%;" title="cuota_moderadora" value="' . $item->cuota_moderadora_sumada . '">';

                return $dataArray;
            });

            // Retornar los datos en formato JSON para DataTable
            return response()->json($data);
        } else {
            // Retornar un error si no se encontraron resultados
            return response()->json(['error' => 'Factura no encontrada o no tiene estado DISPENSADO'], 404);
        }
    }



    //funcion para actualizar los datos de la factura haciendo la insercion de los datos que se validan en el front
    public function actualizarDispensacion(Request $request)
    {
        // Validar los campos requeridos
        $request->validate([
            'data.*.id', // Campo 'id' requerido
            'data.*.fecha_orden',
            'data.*.numero_entrega1',
            'data.*.diagnostico',
            'data.*.ips',
            'data.*.fecha_suministro',
        ]);

        // Obtener la fecha de suministro y formatearla como objeto Carbon
        $fechaSuministro = Carbon::parse($request->input('fecha_suministro'))->format('Y-m-d');


        try {
            // Obtener los registros de datos
            $datos = $request->input('data.registros');

            // Iterar sobre cada registro
            foreach ($datos as $idd) {
                // Obtener la fecha de ordenamiento y formatearla como objeto Carbon
                $fechaOrden = Carbon::parse($idd['fecha_orden'])->format('Y-m-d');
                //dd($idd);

                // Verificar si la fecha de ordenamiento es menor o igual a la fecha de suministro
                if (strtotime($fechaOrden) <= strtotime($fechaSuministro)) {
                    // Actualizar los datos en la base de datos
                    DispensadoApiMedcold::where('id', $idd['ID'])
                        ->update([
                            'autorizacion' => trim($idd['autorizacion']),
                            'cuota_moderadora' => trim($idd['cuota_moderadora']),
                            'copago' => trim($idd['cuota_moderadora']),
                            'mipres' => trim($idd['mipres']),
                            'reporte_entrega_nopbs' => trim($idd['reporte_entrega']),
                            'numero_entrega' => trim($idd['numero_entrega']),
                            'fecha_ordenamiento' => trim($idd['fecha_orden']),
                            'dx' => trim($idd['diagnostico']),
                            'ips' => trim($idd['ips']),
                            'estado' => trim($idd['estado']),
                            'user_id' => trim($idd['user_id']),
                            'updated_at' => now()
                        ]);
                } else {
                    // Mostrar mensaje de error si la fecha de ordenamiento es mayor a la fecha de suministro
                    return response()->json([
                        'error' => 'La Fecha de Ordenamiento no puede ser superior a la Fecha de Suministro'
                    ], 422);
                }
            }

            // Si se completó correctamente, devolver una respuesta JSON de éxito
            return response()->json(['success' => 'Datos actualizados correctamente'], 200);
        } catch (\Exception $e) {
            // Capturar excepciones y devolver un mensaje de error
            return response()->json(['error' => 'Error al actualizar los datos'], 500);
        }
    }
}
