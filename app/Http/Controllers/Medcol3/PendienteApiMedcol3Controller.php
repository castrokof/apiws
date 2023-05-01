<?php

namespace App\Http\Controllers\Medcol3;

use App\Http\Controllers\Controller;
use App\Models\Medcol3\PendienteApiMedcol3;
use App\Models\Medcol3\EntregadosApiMedcol3;
use App\Models\Medcol3\ObservacionesApiMedcol3;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;

class PendienteApiMedcol3Controller extends Controller
{

    public $var1 = null;
    public $var2 = null;
    public $ip = null;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        if ($request->ajax()) {
            $pendiente_api_medcol3 = PendienteApiMedcol3::where('estado', 'PENDIENTE')
                ->orWhere('estado', NULL)
                /* ->where('orden_externa', 'LIKE', '%MP%') */
                ->orderBy('id')
                ->get();

            return DataTables()->of($pendiente_api_medcol3)
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

        return view('menu.Medcol3.indexAnalista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createapendientespi(Request $request)
    {



        $email = 'sistemas.saludtempus@gmail.com'; // Auth::user()->email
        $password = '12345678';

        try {



            $response = Http::post("http://190.145.32.226:8000/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];

            $responsefacturas = Http::withToken($token)->get("http://190.145.32.226:8000/api/pendiente_api_medcol3");

            $facturassapi = $responsefacturas->json()['data'];

            $contador = 0;
            $pendientes = [];

            foreach ($facturassapi as $factura) {
                $existe = PendienteApiMedcol3::where('factura', $factura['factura'])->count();

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
                        'codigo' => trim($factura['codigo']),
                        'nombre' => trim($factura['nombre']),
                        'cums' => trim($factura['cums']),
                        'cantidad' => trim($factura['cantidad']),
                        'cajero' => trim($factura['cajero']),
                        'estado' => 'PENDIENTE',
                        'orden_externa' => trim($factura['ORDEN_EXTERNA'])
                    ];

                    $contador++;
                }
            }

            if (!empty($pendientes)) {
                PendienteApiMedcol3::insert($pendientes);
            }

            Http::withToken($token)->get("http://190.145.32.226:8000/api/closeallacceso");

            $var = $this->createentregadospi(null);

            return response()->json([
                ['respuesta' => $contador . ' Lineas creadas y' . $var . ' Lineas entregadas', 'titulo' => 'Mixed lineas', 'icon' => 'success', 'position' => 'bottom-left']
            ]);
        } catch (\Exception $e) {



            $response = Http::post("http://192.168.7.10:8000/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];

            $responsefacturas = Http::withToken($token)->get("http://192.168.7.10:8000/api/pendiente_api_medcol3");

            $facturassapi = $responsefacturas->json()['data'];

            $contador = 0;
            $pendientes = [];

            foreach ($facturassapi as $factura) {
                $existe = PendienteApiMedcol3::where('factura', $factura['factura'])->count();

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
                        'codigo' => trim($factura['codigo']),
                        'nombre' => trim($factura['nombre']),
                        'cums' => trim($factura['cums']),
                        'cantidad' => trim($factura['cantidad']),
                        'cajero' => trim($factura['cajero']),
                        'estado' => 'PENDIENTE',
                        'orden_externa' => trim($factura['ORDEN_EXTERNA'])
                    ];

                    $contador++;
                }
            }

            if (!empty($pendientes)) {
                PendienteApiMedcol3::insert($pendientes);
            }

            Http::withToken($token)->get("http://192.168.7.10:8000/api/closeallacceso");

            $var = $this->createentregadospilocal(null);

            return response()->json([
                ['respuesta' => $contador . ' Lineas creadas y' . $var . ' Lineas entregadas', 'titulo' => 'Usando Api Local', 'icon' => 'error', 'position' => 'bottom-left']
            ]);


            // return response()->json([
            //     ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
            // ]);
        }

        //     $response = Http::post(
        //         "http://190.145.32.226:8000/api/acceso",
        //         [
        //             'email' =>  $email,
        //             'password' => $password,
        //         ]
        //     );


        //     // $this->createapendientespi($request);

        //     $prueba = $response->json();
        //     $token = $prueba["token"];

        //     $responsefacturas = Http::withToken($token)->get("http://190.145.32.226:8000/api/pendiente_api_medcol3");

        //     $facturassapi = $responsefacturas->json();

        //     //dd($facturassapi);

        //     $contador = 0;



        //     foreach ($facturassapi['data'] as $factura) {


        //         $existe =  pendiente_api_medcol3::where('factura', $factura['factura'])->count();

        //         if ($existe == 0 || $existe == '') {
        //             pendiente_api_medcol3::create([
        //                 'Tipodocum' => trim($factura['Tipodocum']),
        //                 'cantdpx' => trim($factura['cantdpx']),
        //                 'cantord' => trim($factura['cantord']),
        //                 'fecha_factura' => trim($factura['fecha_factura']),
        //                 'fecha' => trim($factura['fecha']),
        //                 'historia' => trim($factura['historia']),
        //                 'apellido1' => trim($factura['apellido1']),
        //                 'apellido2' => trim($factura['apellido2']),
        //                 'nombre1' => trim($factura['nombre1']),
        //                 'nombre2' => trim($factura['nombre2']),
        //                 'cantedad' => trim($factura['cantedad']),
        //                 'direcres' => trim($factura['direcres']),
        //                 'telefres' => trim($factura['telefres']),
        //                 'documento' => trim($factura['documento']),
        //                 'factura' => trim($factura['factura']),
        //                 'codigo' => trim($factura['codigo']),
        //                 'nombre' => trim($factura['nombre']),
        //                 'cums' => trim($factura['cums']),
        //                 'cantidad' => trim($factura['cantidad']),
        //                 'cajero' => trim($factura['cajero']),
        //                 'estado' => 'PENDIENTE',
        //                 'orden_externa' => trim($factura['ORDEN_EXTERNA'])
        //             ]);

        //             $contador++;
        //         }
        //     }

        //     Http::withToken($token)->get("http://190.145.32.226:8000/api/closeallacceso");

        //    $var = $this->createentregadospi(null);
        //    $var;

        //    // if ($contador > 0) {
        //         return response()->json([['respuesta' => $contador . ' Lineas creadas y'. $var . ' Lineas entregadas', 'titulo' => 'Mixed lineas', 'icon' => 'success', 'position' => 'bottom-left']] );
        //    // } else {
        //    //     return response()->json([['respuesta' => $contador . ' Lineas creadas', 'titulo' => 'No se crearon lineas', 'icon' => 'warning',
        //   //  ],
        //   //  ['respuesta' => $var . ' Lineas entregadas', 'titulo' => 'No se entregaron lineas', 'icon' => 'warning'] ]);
        //   //  }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function porentregar(Request $request)
    {
        //
        if ($request->ajax()) {
            $pendiente_api_medcol3 = PendienteApiMedcol3::where('estado', 'TRAMITADO')
                ->orderBy('id')
                ->get();

            return DataTables()->of($pendiente_api_medcol3)
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

        return view('menu.Medcol3.indexAnalista');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Medcol3\PendienteApiMedcol3  $pendienteApiMedcol3
     * @return \Illuminate\Http\Response
     */
    public function entregados(Request $request)
    {
        //
        if ($request->ajax()) {
            $pendiente_api_medcol3 = PendienteApiMedcol3::where('estado', 'ENTREGADO')
                ->orderBy('id')
                ->get();

            return DataTables()->of($pendiente_api_medcol3)
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

        return view('menu.Medcol3.indexAnalista');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Medcol3\PendienteApiMedcol3  $pendienteApiMedcol3
     * @return \Illuminate\Http\Response
     */
    public function getDesabastecidos(Request $request)
    {
        //
        if ($request->ajax()) {
            $pendiente_api_medcol3 = PendienteApiMedcol3::where('estado', 'DESABASTECIDO')
                ->orderBy('id')
                ->get();

            return DataTables()->of($pendiente_api_medcol3)
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

        return view('menu.Medcol3.indexAnalista');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Medcol3\PendienteApiMedcol3  $pendienteApiMedcol3
     * @return \Illuminate\Http\Response
     */
    public function getAnulados(Request $request)
    {
        //

        if ($request->ajax()) {
            $pendiente_api_medcol3 = PendienteApiMedcol3::where('estado', 'ANULADO')
                ->orderBy('id')
                ->get();

            return DataTables()->of($pendiente_api_medcol3)
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

        return view('menu.Medcol3.indexAnalista');
    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'estado' => 'required'
        );

        if ($request->input('enviar_fecha_entrega') == 'true') {
            $rules['fecha_entrega'] = 'required';
        }

        if ($request->input('enviar_fecha_impresion') == 'true') {
            $rules['fecha_impresion'] = 'required';
        }

        if ($request->input('enviar_fecha_anulado') == 'true') {
            $rules['fecha_anulado'] = 'required';
        }

        if ($request->input('enviar_factura_entrega') == 'true') {
            $rules['doc_entrega'] = 'required';
            $rules['factura_entrega'] = 'required';
        }

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }


        if (request()->ajax()) {
            $pendiente_api_medcol3 = PendienteApiMedcol3::findOrFail($id);
            $pendiente_api_medcol3->fill($request->all());
            $pendiente_api_medcol3->doc_entrega = $request->doc_entrega;
            $pendiente_api_medcol3->factura_entrega = $request->factura_entrega;
            $pendiente_api_medcol3->usuario = $request->name;

            if ($request->input('enviar_fecha_entrega') == 'true') {
                if ($request->fecha_entrega < $pendiente_api_medcol3->fecha || $request->fecha_entrega > now()->format('Y-m-d')) {
                    return response()->json(['errors' => ['La fecha de ENTREGA debe estar entre la fecha de la factura y la fecha actual']]);
                }
                $pendiente_api_medcol3->fecha_entrega = $request->fecha_entrega;
            }

            if ($request->input('enviar_fecha_impresion') == 'true') {
                if ($request->fecha_impresion < $pendiente_api_medcol3->fecha || $request->fecha_impresion > now()->format('Y-m-d')) {
                    return response()->json(['errors' => ['La fecha de TRAMITE debe estar entre la fecha de la factura y la fecha actual']]);
                }
                $pendiente_api_medcol3->fecha_impresion = $request->fecha_impresion;
            }

            if ($request->input('enviar_fecha_anulado') == 'true') {
                if ($request->fecha_anulado < $pendiente_api_medcol3->fecha || $request->fecha_anulado > now()->format('Y-m-d')) {
                    return response()->json(['errors' => ['La fecha de ANULACIÓN debe estar entre la fecha de la factura y la fecha actual']]);
                }
                $pendiente_api_medcol3->fecha_anulado = $request->fecha_anulado;
            }

            $pendiente_api_medcol3->save();

            // Guardar observación en la tabla ObservacionesApiMedcol3
            ObservacionesApiMedcol3::create([
                'pendiente_id' => $pendiente_api_medcol3->id,
                'observacion' => $request->input('observacion'),
                'usuario' => $request->input('name'),
                'estado' => $request->input('estado')
            ]);
        }

        return response()->json(['success' => 'ok1']);
    }

    public function saveObs(Request $request)
    {
        /* ObservacionesApiMedcol3::create([
            'pendiente_id' => $id,
            'observacion' => $request->input('observacion'),
            'estado' => $request->input('estado')
        ]); */
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Medcol3\PendienteApiMedcol3  $pendienteApiMedcol3
     * @return \Illuminate\Http\Response
     */
    public function getObservaciones(Request $request)
    {
        $idlist = $request->id;

        if (request()->ajax()) {
            $data = DB::table('observaciones_api_medcol3')
                ->where('observaciones_api_medcol3.pendiente_id', '=', $idlist)
                ->get();

            return DataTables()->of($data)->make(true);
        }
        return view('menu.Medcol3.indexAnalista');
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
            $pendiente = PendienteApiMedcol3::where('id', '=', $id)
                ->first();

            $saldo_pendiente = $pendiente->cantord - $pendiente->cantdpx;

            return response()->json([
                'pendiente' => $pendiente,
                'saldo_pendiente' => $saldo_pendiente
            ]);
        }
        return view('menu.Medcol3.indexAnalista');
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
            $pendiente = PendienteApiMedcol3::where('id', '=', $id)
                ->first();

            $saldo_pendiente = $pendiente->cantord - $pendiente->cantdpx;

            return response()->json([
                'pendiente' => $pendiente,
                'saldo_pendiente' => $saldo_pendiente
            ]);
        }
        return view('menu.Medcol3.indexAnalista');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function informes()
    {

        $pendientes =  PendienteApiMedcol3::where('estado', 'PENDIENTE')->count();
        $entregados =  PendienteApiMedcol3::where('estado', 'ENTREGADO')->count();
        $tramitados =  PendienteApiMedcol3::where('estado', 'TRAMITADO')->count();
        $agotados =  PendienteApiMedcol3::where('estado', 'DESABASTECIDO')->count();
        $anulados =  PendienteApiMedcol3::where('estado', 'ANULADO')->count();

        return response()->json(['pendientes' => $pendientes, 'entregados' => $entregados, 'tramitados' => $tramitados, 'agotados' => $agotados, 'anulados' => $anulados]);
    }

    public function createentregadospi($var1)
    {
        $email = 'sistemas.saludtempus@gmail.com'; // Auth::user()->email
        $password = '12345678';

        $response = Http::post(
            "http://190.145.32.226:8000/api/acceso",
            [
                'email' =>  $email,
                'password' => $password,
            ]
        );


        // $this->createapendientespi($request);

        $prueba = $response->json();
        $token = $prueba["token"];

        $responsefacturas = Http::withToken($token)->get("http://190.145.32.226:8000/api/entregados_api_medcol3");

        $facturassapi = $responsefacturas->json();

        //dd($facturassapi);
        $contadorei = 0;
        $contador1 = 0;

        foreach ($facturassapi['data'] as $factura) {


            $existe =  EntregadosApiMedcol3::where('factura', $factura['factura'])->count();

            if ($existe == 0 || $existe == '') {
                EntregadosApiMedcol3::create([
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
                    'orden_externa' => trim($factura['ORDEN_EXTERNA']),
                    'doc_entrega' => trim($factura['documento']),
                    'factura_entrega' => trim($factura['factura'])
                ]);

                $contador1++;
            }
        }

        Http::withToken($token)->get("http://190.145.32.226:8000/api/closeallacceso");

        $pendientes = DB::table('pendiente_api_medcol3')
            ->join('entregados_api_medcol3', function ($join) {
                $join->on('pendiente_api_medcol3.orden_externa', '=', 'entregados_api_medcol3.orden_externa')
                    ->on('pendiente_api_medcol3.codigo', '=', 'entregados_api_medcol3.codigo');
            })
            ->select(
                'pendiente_api_medcol3.id as idd',
                'entregados_api_medcol3.orden_externa',
                'entregados_api_medcol3.codigo',
                'entregados_api_medcol3.cantdpx',
                'entregados_api_medcol3.fecha_factura',
                'entregados_api_medcol3.documento',
                'entregados_api_medcol3.factura'
            )
            ->get();




        foreach ($pendientes as $key => $value) {

            $entregados =
                DB::table('pendiente_api_medcol3')
                ->where([
                    ['pendiente_api_medcol3.estado', '=', 'ENTREGADO'],
                    ['pendiente_api_medcol3.orden_externa', '=', $value->orden_externa],
                    ['pendiente_api_medcol3.codigo', '=', $value->codigo],
                    ['pendiente_api_medcol3.usuario', 'RFAST']
                ])->count();

            if ($entregados == 0 || $entregados == null) {

                DB::table('pendiente_api_medcol3')
                    ->where([
                        ['pendiente_api_medcol3.estado', '=', 'PENDIENTE'],
                        ['pendiente_api_medcol3.orden_externa', '=', $value->orden_externa],
                        ['pendiente_api_medcol3.codigo', '=', $value->codigo]
                    ])
                    ->update([
                        'pendiente_api_medcol3.fecha_entrega' =>  $value->fecha_factura,
                        'pendiente_api_medcol3.estado' => 'ENTREGADO',
                        'pendiente_api_medcol3.cantdpx' => $value->cantdpx,
                        'pendiente_api_medcol3.doc_entrega' => $value->documento,
                        'pendiente_api_medcol3.factura_entrega' => $value->factura,
                        'pendiente_api_medcol3.usuario' => 'RFAST',
                        'pendiente_api_medcol3.updated_at' => now()
                    ]);

                $contadorei++;
            }



            // Guardar observación en la tabla ObservacionesApi

            $entregado = ObservacionesApiMedcol3::where([
                ['pendiente_id', $value->idd],
                ['estado', 'ENTREGADO']
            ])->count();

            if ($entregado == 0 || $entregado == null) {

                ObservacionesApiMedcol3::create([
                    'pendiente_id' => $value->idd,
                    'observacion' => 'Este resgistro se genero automaticamente al consumir la api',
                    'usuario' => 'RFAST',
                    'estado' => 'ENTREGADO'
                ]);
            }
        }



        return $this->var1 = $contadorei;
    }
}
