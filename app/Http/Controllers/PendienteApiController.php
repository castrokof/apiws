<?php

namespace App\Http\Controllers;

use App\EntregadosApi;
use App\PendientesApi;
use App\ObservacionesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;

class PendienteApiController extends Controller
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
            $pendientesapi = PendientesApi::where('estado', 'PENDIENTE')
                ->orWhere('estado', NULL)
                /* ->where('orden_externa', 'LIKE', '%MP%') */
                ->orderBy('id')
                ->get();

            return DataTables()->of($pendientesapi)
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

        return view('menu.usuario.indexAnalista');
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

            $responsefacturas = Http::withToken($token)->get("http://190.145.32.226:8000/api/pendientesapi");

            $facturassapi = $responsefacturas->json()['data'];

            $contador = 0;
            $pendientes = [];

            foreach ($facturassapi as $factura) {
                $existe = PendientesApi::where('factura', $factura['factura'])->count();

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
                PendientesApi::insert($pendientes);
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

            $responsefacturas = Http::withToken($token)->get("http://192.168.7.10:8000/api/pendientesapi");

            $facturassapi = $responsefacturas->json()['data'];

            $contador = 0;
            $pendientes = [];

            foreach ($facturassapi as $factura) {
                $existe = PendientesApi::where('factura', $factura['factura'])->count();

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
                PendientesApi::insert($pendientes);
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

        //     $responsefacturas = Http::withToken($token)->get("http://190.145.32.226:8000/api/pendientesapi");

        //     $facturassapi = $responsefacturas->json();

        //     //dd($facturassapi);

        //     $contador = 0;



        //     foreach ($facturassapi['data'] as $factura) {


        //         $existe =  PendientesApi::where('factura', $factura['factura'])->count();

        //         if ($existe == 0 || $existe == '') {
        //             PendientesApi::create([
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
            $pendientesapi = PendientesApi::where('estado', 'TRAMITADO')
                ->orderBy('id')
                ->get();

            return DataTables()->of($pendientesapi)
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

        return view('menu.usuario.indexAnalista');
    }

    public function entregados(Request $request)
    {
        //
        if ($request->ajax()) {
            $pendientesapi = PendientesApi::where('estado', 'ENTREGADO')
                ->orderBy('id')
                ->get();

            return DataTables()->of($pendientesapi)
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

        return view('menu.usuario.indexAnalista');
    }

    public function getDesabastecidos(Request $request)
    {
        //
        if ($request->ajax()) {
            $pendientesapi = PendientesApi::where('estado', 'DESABASTECIDO')
                ->orderBy('id')
                ->get();

            return DataTables()->of($pendientesapi)
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

        return view('menu.usuario.indexAnalista');
    }


    public function getAnulados(Request $request)
    {
        //

        if ($request->ajax()) {
            $pendientesapi = PendientesApi::where('estado', 'ANULADO')
                ->orderBy('id')
                ->get();

            return DataTables()->of($pendientesapi)
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

        return view('menu.usuario.indexAnalista');
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

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }


        if (request()->ajax()) {
            $pendientesapi = PendientesApi::findOrFail($id);
            $pendientesapi->fill($request->all());
            $pendientesapi->usuario = $request->name;

            if ($request->input('enviar_fecha_entrega') == 'true') {
                if ($request->fecha_entrega < $pendientesapi->fecha || $request->fecha_entrega > now()->format('Y-m-d')) {
                    return response()->json(['errors' => ['La fecha de ENTREGA debe estar entre la fecha de la factura y la fecha actual']]);
                }
                $pendientesapi->fecha_entrega = $request->fecha_entrega;
            }

            if ($request->input('enviar_fecha_impresion') == 'true') {
                if ($request->fecha_impresion < $pendientesapi->fecha || $request->fecha_impresion > now()->format('Y-m-d')) {
                    return response()->json(['errors' => ['La fecha de TRAMITE debe estar entre la fecha de la factura y la fecha actual']]);
                }
                $pendientesapi->fecha_impresion = $request->fecha_impresion;
            }

            if ($request->input('enviar_fecha_anulado') == 'true') {
                if ($request->fecha_anulado < $pendientesapi->fecha || $request->fecha_anulado > now()->format('Y-m-d')) {
                    return response()->json(['errors' => ['La fecha de ANULACIÓN debe estar entre la fecha de la factura y la fecha actual']]);
                }
                $pendientesapi->fecha_anulado = $request->fecha_anulado;
            }

            $pendientesapi->save();

            // Guardar observación en la tabla ObservacionesApi
            ObservacionesApi::create([
                'pendiente_id' => $pendientesapi->id,
                'observacion' => $request->input('observacion'),
                'usuario' => $request->input('name'),
                'estado' => $request->input('estado')
            ]);
        }

        return response()->json(['success' => 'ok1']);
    }


    public function saveObs(Request $request)
    {
        /* ObservacionesApi::create([
            'pendiente_id' => $id,
            'observacion' => $request->input('observacion'),
            'estado' => $request->input('estado')
        ]); */
    }
    public function getObservaciones(Request $request)
    {
        $idlist = $request->id;

        if (request()->ajax()) {
            $data = DB::table('observacionesapi')
                ->where('observacionesapi.pendiente_id', '=', $idlist)
                ->get();

            return DataTables()->of($data)->make(true);
        }
        return view('menu.usuario.indexAnalista');
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
            $pendiente = PendientesApi::where('id', '=', $id)
                ->first();

            $saldo_pendiente = $pendiente->cantord - $pendiente->cantdpx;

            return response()->json([
                'pendiente' => $pendiente,
                'saldo_pendiente' => $saldo_pendiente
            ]);
        }
        return view('menu.usuario.indexAnalista');
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
            $pendiente = PendientesApi::where('id', '=', $id)
                ->first();

            $saldo_pendiente = $pendiente->cantord - $pendiente->cantdpx;

            return response()->json([
                'pendiente' => $pendiente,
                'saldo_pendiente' => $saldo_pendiente
            ]);
        }
        return view('menu.usuario.indexAnalista');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
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

        $responsefacturas = Http::withToken($token)->get("http://190.145.32.226:8000/api/entregadosapi");

        $facturassapi = $responsefacturas->json();

        //dd($facturassapi);
        $contadorei = 0;
        $contador1 = 0;

        foreach ($facturassapi['data'] as $factura) {


            $existe =  EntregadosApi::where('factura', $factura['factura'])->count();

            if ($existe == 0 || $existe == '') {
                EntregadosApi::create([
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

        $pendientes = DB::table('pendientesapi')
            ->join('entregadosapi', function ($join) {
                $join->on('pendientesapi.orden_externa', '=', 'entregadosapi.orden_externa')
                    ->on('pendientesapi.codigo', '=', 'entregadosapi.codigo');
            })
            ->select(
                'pendientesapi.id as idd',
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
                DB::table('pendientesapi')
                ->where([
                    ['pendientesapi.estado', '=', 'ENTREGADO'],
                    ['pendientesapi.orden_externa', '=', $value->orden_externa],
                    ['pendientesapi.codigo', '=', $value->codigo],
                    ['pendientesapi.usuario', 'RFAST']
                ])->count();

            if ($entregados == 0 || $entregados == null) {

                DB::table('pendientesapi')
                    ->where([
                        ['pendientesapi.estado', '=', 'PENDIENTE'],
                        ['pendientesapi.orden_externa', '=', $value->orden_externa],
                        ['pendientesapi.codigo', '=', $value->codigo]
                    ])
                    ->update([
                        'pendientesapi.fecha_entrega' =>  $value->fecha_factura,
                        'pendientesapi.estado' => 'ENTREGADO',
                        'pendientesapi.cantdpx' => $value->cantdpx,
                        'pendientesapi.doc_entrega' => $value->documento,
                        'pendientesapi.factura_entrega' => $value->factura,
                        'pendientesapi.usuario' => 'RFAST',
                        'pendientesapi.updated_at' => now()
                    ]);

                $contadorei++;
            }



            // Guardar observación en la tabla ObservacionesApi

            $entregado = ObservacionesApi::where([
                ['pendiente_id', $value->idd],
                ['estado', 'ENTREGADO']
            ])->count();

            if ($entregado == 0 || $entregado == null) {

                ObservacionesApi::create([
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
        $email = 'sistemas.saludtempus@gmail.com'; // Auth::user()->email
        $password = '12345678';

        $response = Http::post(
            "http://192.168.7.10:8000/api/acceso",
            [
                'email' =>  $email,
                'password' => $password,
            ]
        );


        // $this->createapendientespi($request);

        $prueba = $response->json();
        $token = $prueba["token"];

        $responsefacturas = Http::withToken($token)->get("http://192.168.7.10:8000/api/entregadosapi");

        $facturassapi = $responsefacturas->json();

        //dd($facturassapi);
        $contadorei = 0;
        $contador1 = 0;

        foreach ($facturassapi['data'] as $factura) {


            $existe =  EntregadosApi::where('factura', $factura['factura'])->count();

            if ($existe == 0 || $existe == '') {
                EntregadosApi::create([
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

        Http::withToken($token)->get("http://192.168.7.10:8000/api/closeallacceso");

        $pendientes = DB::table('pendientesapi')
            ->join('entregadosapi', function ($join) {
                $join->on('pendientesapi.orden_externa', '=', 'entregadosapi.orden_externa')
                    ->on('pendientesapi.codigo', '=', 'entregadosapi.codigo');
            })
            ->select(
                'pendientesapi.id as idd',
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
                DB::table('pendientesapi')
                ->where([
                    ['pendientesapi.estado', '=', 'ENTREGADO'],
                    ['pendientesapi.orden_externa', '=', $value->orden_externa],
                    ['pendientesapi.codigo', '=', $value->codigo],
                    ['pendientesapi.usuario', 'RFAST']
                ])->count();

            if ($entregados == 0 || $entregados == null) {

                DB::table('pendientesapi')
                    ->where([
                        ['pendientesapi.estado', '=', 'PENDIENTE'],
                        ['pendientesapi.orden_externa', '=', $value->orden_externa],
                        ['pendientesapi.codigo', '=', $value->codigo]
                    ])
                    ->update([
                        'pendientesapi.fecha_entrega' =>  $value->fecha_factura,
                        'pendientesapi.estado' => 'ENTREGADO',
                        'pendientesapi.cantdpx' => $value->cantdpx,
                        'pendientesapi.doc_entrega' => $value->documento,
                        'pendientesapi.factura_entrega' => $value->factura,
                        'pendientesapi.usuario' => 'RFAST',
                        'pendientesapi.updated_at' => now()
                    ]);

                $contadorei++;
            }


            // Guardar observación en la tabla ObservacionesApi

            $entregado = ObservacionesApi::where([
                ['pendiente_id', $value->idd],
                ['estado', 'ENTREGADO']
            ])->count();

            if ($entregado == 0 || $entregado == null) {

                ObservacionesApi::create([
                    'pendiente_id' => $value->idd,
                    'observacion' => 'Este resgistro se genero automaticamente al consumir la api',
                    'usuario' => 'RFAST',
                    'estado' => 'ENTREGADO'
                ]);
            }
        }



        return $this->var2 = $contadorei;
    }
}
