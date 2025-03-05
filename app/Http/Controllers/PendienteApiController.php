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
    public $ip = 'http://hef08s3bxw8.sn.mynetname.net';
    
    //public $ip = 'http://hed08pf9dxt.sn.mynetname.net';
    //public $puerto = ':8003';
    public $puerto = ':8000';
    
  
    
    public $res = false;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return view('menu.usuario.indexAnalista');
    }
    
       public function index1(Request $request)
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

            $response = Http::post($this->ip.$this->puerto."/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

    
           

            $token = $response->json()["token"];
            
             


            $responsefacturas = Http::withToken($token)->get($this->ip.$this->puerto."/api/pendientesapi");

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
                        'orden_externa' => trim($factura['ORDEN_EXTERNA']),
                        'centroproduccion' => trim($factura['CENTROPRODUCCION']),
                        'observaciones' => trim($factura['observaciones'])
                    ];

                    $contador++;
                }
            }

            if (!empty($pendientes)) {
                PendientesApi::insert($pendientes);
            }

            Http::withToken($token)->get($this->ip.$this->puerto."/api/closeallacceso");

            $var = $this->createentregadospi(null);

            return response()->json([
                ['respuesta' => $contador . ' Lineas creadas y ' . $var . ' Lineas entregadas', 'titulo' => 'Mixed lineas', 'icon' => 'success', 'position' => 'bottom-left']
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
                        'orden_externa' => trim($factura['ORDEN_EXTERNA']),
                        'centroproduccion' => trim($factura['CENTROPRODUCCION']),
                        'observaciones' => trim($factura['observaciones'])
                    ];

                    $contador++;
                }
            }

            if (!empty($pendientes)) {
                PendientesApi::insert($pendientes);
            }

            Http::withToken($token)->get("http://192.168.7.10:8000/api/closeallacceso");

            $var = $this->createentregadospilocal(null);

            // return response()->json([
            //     ['respuesta' => $contador . ' Lineas creadas y ' . $var . ' Lineas entregadas', 'titulo' => 'Usando Api Local', 'icon' => 'error', 'position' => 'bottom-left']
            // ]);

            return response()->json([
                ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
            ]);
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

        if ($request->input('enviar_factura_entrega') == 'true') {
            $rules['doc_entrega'] = 'required';
            $rules['factura_entrega'] = 'required';
        }

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }


        if (request()->ajax()) {
            $pendientesapi = PendientesApi::findOrFail($id);
            $pendientesapi->fill($request->all());
            $pendientesapi->doc_entrega = $request->doc_entrega;
            $pendientesapi->factura_entrega = $request->factura_entrega;
            $pendientesapi->usuario = $request->name;
            $pendientesapi->cantord = $request->cantord;

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
                    
                    // Concatenar los campos doc_entrega y factura_entrega
                    $fac_entrega = $pendiente->doc_entrega . ' ' . $pendiente->factura_entrega;

            return response()->json([
                'pendiente' => $pendiente,
                'saldo_pendiente' => $saldo_pendiente,
                'fac_entrega' => $fac_entrega
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
        
        //$ip = 'http://hef08s3bxw8.sn.mynetname.net';
        //$puerto = ':8000';
        
        
        
        $email = 'sistemas.saludtempus@gmail.com'; // Auth::user()->email
        $password = '12345678';

        $response = Http::post($this->ip.$this->puerto."/api/acceso",
            [
                'email' =>  $email,
                'password' => $password,
            ]
        );
        
        

        // $this->createapendientespi($request);

        $prueba = $response->json();
        $token = $prueba["token"];

        $responsefacturas = Http::withToken($token)->get($this->ip.$this->puerto."/api/entregadosapi");

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
                    'factura_entrega' => trim($factura['factura']),
                    'centroproduccion' => trim($factura['CENTROPRODUCCION']),
                    'observaciones' => trim($factura['observaciones'])
                ]);

                $contador1++;
            }
        }

        Http::withToken($token)->get($this->ip.$this->puerto."/api/closeallacceso");

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
                    'factura_entrega' => trim($factura['factura']),
                    'centroproduccion' => trim($factura['CENTROPRODUCCION']),
                    'observaciones' => trim($factura['observaciones'])
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
    
    public function updateanuladosapi(Request $request)
    {
        $email = 'sistemas.saludtempus@gmail.com'; // Auth::user()->email
        $password = '12345678';
        $usuario = Auth::user()->email;
        
        
  
    
        try {
            //$response = Http::post("http://hed08pf9dxt.sn.mynetname.net:8003/api/acceso", [
            $response = Http::post("http://hef08s3bxw8.sn.mynetname.net:8000/api/acceso", [
                'email' => $email,
                'password' => $password,
            ]);
    
            $token = $response->json()["token"] ?? null;
    
            if ($token) {
                try {
                    //$responsefacturas = Http::withToken($token)->get("http://http://hed08pf9dxt.sn.mynetname.net:8003/api/pendientesanuladosapi");
                    $responsefacturas = Http::withToken($token)->get("http://hef08s3bxw8.sn.mynetname.net:8000/api/pendientesanuladosapi");
                    $facturassapi = $responsefacturas->json()['data'] ?? [];
    
                    $contadorActualizados = 0;
                    
                    //dd($facturassapi);
    
                    foreach ($facturassapi as $factura) {
                        if (isset($factura['orden_externa'])) {
                            $actualizados = PendienteApiMedcol3::where('orden_externa', $factura['orden_externa'])
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
                    
                    
    
                    //Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8003/api/closeallacceso");
                    Http::withToken($token)->get("http://hef08s3bxw8.sn.mynetname.net:8000/api/closeallacceso");
    
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
}
