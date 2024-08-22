<?php

namespace App\Http\Controllers\Medcol5;

use App\Http\Controllers\Controller;
use App\Models\Medcol5\PendienteApiMedcol5;
use App\Models\Medcol5\EntregadosApiMedcol5;
use App\Models\Medcol5\ObservacionesApiMedcol5;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;

class PendienteApiMedcol5Controller extends Controller
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

        return view('menu.Medcol5.indexAnalista');
    }

    public function index1(Request $request)
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
        }



        //dd($drogueria);

        if ($request->ajax()) {

            if (Auth::user()->drogueria == '1') {

                $pendiente_api_medcol5 = PendienteApiMedcol5::where([['estado', 'PENDIENTE']])
                    ->orWhere('estado', NULL)
                    /* ->where('orden_externa', 'LIKE', '%MP%') */
                    ->orderBy('id')
                    ->get();
            } else {
                $pendiente_api_medcol5 = PendienteApiMedcol5::where([['estado', 'PENDIENTE'], ['centroproduccion', $drogueria]])
                    ->orWhere('estado', NULL)
                    /* ->where('orden_externa', 'LIKE', '%MP%') */
                    ->orderBy('id')
                    ->get();
            }
            return DataTables()->of($pendiente_api_medcol5)
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

        return view('menu.Medcol5.indexAnalista');
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

            $response = Http::post("http://hcp080m81s7.sn.mynetname.net:8001/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];


            $responsefacturas = Http::withToken($token)->get("http://hcp080m81s7.sn.mynetname.net:8001/api/pendientesapi");

            $facturassapi = $responsefacturas->json()['data'];

            $contador = 0;
            $pendientes = [];

            foreach ($facturassapi as $factura) {
                $existe = PendienteApiMedcol5::where('factura', $factura['factura'])->count();

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
                PendienteApiMedcol5::insert($pendientes);
            }

            Http::withToken($token)->get("http://hcp080m81s7.sn.mynetname.net:8001/api/closeallacceso");

            $var = $this->createentregadospi(null);

            Log::info('Desde la web syncapi ' . $contador . ' Lineas creadas y ' . $var . ' Lineas entregadas' . ' Usuario: ' . $usuario);

            return response()->json([
                ['respuesta' => $contador . ' Lineas creadas y ' . $var . ' Lineas entregadas', 'titulo' => 'Mixed lineas', 'icon' => 'success', 'position' => 'bottom-left']
            ]);
        } catch (\Exception $e) {


            $response = Http::post("http://192.168.10.27:8001/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];

            $responsefacturas = Http::withToken($token)->get("http://192.168.10.27:8001/api/pendientesapi");

            $facturassapi = $responsefacturas->json()['data'];

            $contador = 0;
            $pendientes = [];

            foreach ($facturassapi as $factura) {
                $existe = PendienteApiMedcol5::where('factura', $factura['factura'])->count();

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
                PendienteApiMedcol5::insert($pendientes);
            }

            Http::withToken($token)->get("http://192.168.10.27/api/closeallacceso");

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
                $drogueria = 'EMO1';
                break;
        }

        if ($request->ajax()) {

            if (Auth::user()->drogueria == '1') {
                $pendiente_api_medcol5 = PendienteApiMedcol5::where([['estado', 'TRAMITADO']])
                    ->orderBy('id')
                    ->get();
            } else {
                $pendiente_api_medcol5 = PendienteApiMedcol5::where([['estado', 'TRAMITADO'], ['centroproduccion', $drogueria]])
                    ->orderBy('id')
                    ->get();
            }






            return DataTables()->of($pendiente_api_medcol5)
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

        return view('menu.Medcol5.indexAnalista');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Medcol5\PendienteApiMedcol5  $pendienteApiMedcol5
     * @return \Illuminate\Http\Response
     */
    public function entregados(Request $request)
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
                $drogueria = 'EMO1';
                break;
        }
        if ($request->ajax()) {

            if (Auth::user()->drogueria == '1') {
                $pendiente_api_medcol5 = PendienteApiMedcol5::where([['estado', 'ENTREGADO']])
                    ->orderBy('id')
                    ->get();
            } else {
                $pendiente_api_medcol5 = PendienteApiMedcol5::where([['estado', 'ENTREGADO'], ['centroproduccion', $drogueria]])
                    ->orderBy('id')
                    ->get();
            }



            return DataTables()->of($pendiente_api_medcol5)
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

        return view('menu.Medcol5.indexAnalista');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Medcol5\PendienteApiMedcol5  $pendienteApiMedcol5
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
                $drogueria = 'EMO1';
                break;
        }
        if ($request->ajax()) {

            if (Auth::user()->drogueria == '1') {

                $pendiente_api_medcol5 = PendienteApiMedcol5::where([['estado', 'DESABASTECIDO']])
                    ->orderBy('id')
                    ->get();
            } else {


                $pendiente_api_medcol5 = PendienteApiMedcol5::where([['estado', 'DESABASTECIDO'], ['centroproduccion', $drogueria]])
                    ->orderBy('id')
                    ->get();
            }



            return DataTables()->of($pendiente_api_medcol5)
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

        return view('menu.Medcol5.indexAnalista');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Medcol5\PendienteApiMedcol5  $pendienteApiMedcol5
     * @return \Illuminate\Http\Response
     */
    public function getAnulados(Request $request)
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
                $drogueria = 'EMO1';
                break;
        }

        if ($request->ajax()) {

            if (Auth::user()->drogueria == '1') {
                $pendiente_api_medcol5 = PendienteApiMedcol5::where([['estado', 'ANULADO']])
                    ->orderBy('id')
                    ->get();
            } else {

                $pendiente_api_medcol5 = PendienteApiMedcol5::where([['estado', 'ANULADO'], ['centroproduccion', $drogueria]])
                    ->orderBy('id')
                    ->get();
            }



            return DataTables()->of($pendiente_api_medcol5)
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

        return view('menu.Medcol5.indexAnalista');
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
            $pendiente_api_medcol5 = PendienteApiMedcol5::findOrFail($id);
            $pendiente_api_medcol5->fill($request->all());
            $pendiente_api_medcol5->doc_entrega = $request->doc_entrega;
            $pendiente_api_medcol5->factura_entrega = $request->factura_entrega;
            $pendiente_api_medcol5->usuario = $request->name;

            if ($request->input('enviar_fecha_entrega') == 'true') {
                if ($request->fecha_entrega < $pendiente_api_medcol5->fecha || $request->fecha_entrega > now()->format('Y-m-d')) {
                    return response()->json(['errors' => ['La fecha de ENTREGA debe estar entre la fecha de la factura y la fecha actual']]);
                }
                $pendiente_api_medcol5->fecha_entrega = $request->fecha_entrega;
            }

            if ($request->input('enviar_fecha_impresion') == 'true') {
                if ($request->fecha_impresion < $pendiente_api_medcol5->fecha || $request->fecha_impresion > now()->format('Y-m-d')) {
                    return response()->json(['errors' => ['La fecha de TRAMITE debe estar entre la fecha de la factura y la fecha actual']]);
                }
                $pendiente_api_medcol5->fecha_impresion = $request->fecha_impresion;
            }

            if ($request->input('enviar_fecha_anulado') == 'true') {
                if ($request->fecha_anulado < $pendiente_api_medcol5->fecha || $request->fecha_anulado > now()->format('Y-m-d')) {
                    return response()->json(['errors' => ['La fecha de ANULACIÓN debe estar entre la fecha de la factura y la fecha actual']]);
                }
                $pendiente_api_medcol5->fecha_anulado = $request->fecha_anulado;
            }

            $pendiente_api_medcol5->save();

            // Guardar observación en la tabla ObservacionesApiMedcol5
            ObservacionesApiMedcol5::create([
                'pendiente_id' => $pendiente_api_medcol5->id,
                'observacion' => $request->input('observacion'),
                'usuario' => $request->input('name'),
                'estado' => $request->input('estado')
            ]);
        }

        return response()->json(['success' => 'ok1']);
    }

    public function saveObs(Request $request)
    {
        /* ObservacionesApiMedcol5::create([
            'pendiente_id' => $id,
            'observacion' => $request->input('observacion'),
            'estado' => $request->input('estado')
        ]); */
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Medcol5\PendienteApiMedcol5  $pendienteApiMedcol5
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
        return view('menu.Medcol5.indexAnalista');
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
            $pendiente = PendienteApiMedcol5::where('id', '=', $id)
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
        return view('menu.Medcol5.indexAnalista');
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
            $pendiente = PendienteApiMedcol5::where('id', '=', $id)
                ->first();

            $saldo_pendiente = $pendiente->cantord - $pendiente->cantdpx;

            return response()->json([
                'pendiente' => $pendiente,
                'saldo_pendiente' => $saldo_pendiente
            ]);
        }
        return view('menu.Medcol5.indexAnalista');
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
                $drogueria = 'EMO1';
                break;
        }

        if (Auth::user()->drogueria == '1') {

            $pendientes =  PendienteApiMedcol5::where([['estado', 'PENDIENTE']])->count();
            $entregados =  PendienteApiMedcol5::where([['estado', 'ENTREGADO']])->count();
            $tramitados =  PendienteApiMedcol5::where([['estado', 'TRAMITADO']])->count();
            $agotados =  PendienteApiMedcol5::where([['estado', 'DESABASTECIDO']])->count();
            $anulados =  PendienteApiMedcol5::where([['estado', 'ANULADO']])->count();
        } else {

            $pendientes =  PendienteApiMedcol5::where([['estado', 'PENDIENTE'], ['centroproduccion', $drogueria]])->count();
            $entregados =  PendienteApiMedcol5::where([['estado', 'ENTREGADO'], ['centroproduccion', $drogueria]])->count();
            $tramitados =  PendienteApiMedcol5::where([['estado', 'TRAMITADO'], ['centroproduccion', $drogueria]])->count();
            $agotados =  PendienteApiMedcol5::where([['estado', 'DESABASTECIDO'], ['centroproduccion', $drogueria]])->count();
            $anulados =  PendienteApiMedcol5::where([['estado', 'ANULADO'], ['centroproduccion', $drogueria]])->count();
        }



        return response()->json(['pendientes' => $pendientes, 'entregados' => $entregados, 'tramitados' => $tramitados, 'agotados' => $agotados, 'anulados' => $anulados]);
    }

    public function createentregadospi($var1)
    {
        $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = 'colMed2023**';

        $response = Http::post(
            "http://hcp080m81s7.sn.mynetname.net:8001/api/acceso",
            [
                'email' =>  $email,
                'password' => $password,
            ]
        );


        // $this->createapendientespi($request);

        $prueba = $response->json();
        $token = $prueba["token"];

        $responsefacturas = Http::withToken($token)->get("http://hcp080m81s7.sn.mynetname.net:8001/api/entregadosapi");

        $facturassapi = $responsefacturas->json();


        $contadorei = 0;
        $contador1 = 0;

        foreach ($facturassapi['data'] as $factura) {


            $existe =  EntregadosApiMedcol5::where('factura', $factura['factura'])->count();



            if ($existe == 0 || $existe == '') {



                EntregadosApiMedcol5::create([
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
            }
        }

        Http::withToken($token)->get("http://hcp080m81s7.sn.mynetname.net:8001/api/closeallacceso");

        $pendientes = DB::table('pendiente_api_medcol5')
            ->join('entregados_api_medcol3', function ($join) {
                $join->on('pendiente_api_medcol5.orden_externa', '=', 'entregados_api_medcol3.orden_externa')
                    ->on('pendiente_api_medcol5.codigo', '=', 'entregados_api_medcol3.codigo');
            })
            ->select(
                'pendiente_api_medcol5.id as idd',
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
                DB::table('pendiente_api_medcol5')
                ->where([
                    ['pendiente_api_medcol5.estado', '=', 'ENTREGADO'],
                    ['pendiente_api_medcol5.orden_externa', '=', $value->orden_externa],
                    ['pendiente_api_medcol5.codigo', '=', $value->codigo],
                    ['pendiente_api_medcol5.usuario', 'RFAST']
                ])->count();

            if ($entregados == 0 || $entregados == null) {

                DB::table('pendiente_api_medcol5')
                    ->where([
                        ['pendiente_api_medcol5.estado', '=', 'PENDIENTE'],
                        ['pendiente_api_medcol5.orden_externa', '=', $value->orden_externa],
                        ['pendiente_api_medcol5.codigo', '=', $value->codigo]
                    ])
                    ->update([
                        'pendiente_api_medcol5.fecha_entrega' =>  $value->fecha_factura,
                        'pendiente_api_medcol5.estado' => 'ENTREGADO',
                        'pendiente_api_medcol5.cantdpx' => $value->cantdpx,
                        'pendiente_api_medcol5.doc_entrega' => $value->documento,
                        'pendiente_api_medcol5.factura_entrega' => $value->factura,
                        'pendiente_api_medcol5.usuario' => 'RFAST',
                        'pendiente_api_medcol5.updated_at' => now()
                    ]);

                $contadorei++;
            }



            // Guardar observación en la tabla ObservacionesApi

            $entregado = ObservacionesApiMedcol5::where([
                ['pendiente_id', $value->idd],
                ['estado', 'ENTREGADO']
            ])->count();

            if ($entregado == 0 || $entregado == null) {

                ObservacionesApiMedcol5::create([
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
            "http://192.168.10.27:8001/api/acceso",
            [
                'email' =>  $email,
                'password' => $password,
            ]
        );


        // $this->createapendientespi($request);

        $prueba = $response->json();
        $token = $prueba["token"];

        $responsefacturas = Http::withToken($token)->get("http://192.168.10.27:8001/api/entregadosapi");

        $facturassapi = $responsefacturas->json();

        //dd($facturassapi);
        $contadorei = 0;
        $contador1 = 0;

        foreach ($facturassapi['data'] as $factura) {


            $existe =  EntregadosApiMedcol5::where('factura', $factura['factura'])->count();

            if ($existe == 0 || $existe == '') {
                EntregadosApiMedcol5::create([
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
            }
        }

        Http::withToken($token)->get("http://192.168.10.27:8001/api/closeallacceso");

        $pendientes = DB::table('pendiente_api_medcol5')
            ->join('entregadosapi', function ($join) {
                $join->on('pendiente_api_medcol5.orden_externa', '=', 'entregadosapi.orden_externa')
                    ->on('pendiente_api_medcol5.codigo', '=', 'entregadosapi.codigo');
            })
            ->select(
                'pendiente_api_medcol5.id as idd',
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
                DB::table('pendiente_api_medcol5')
                ->where([
                    ['pendiente_api_medcol5.estado', '=', 'ENTREGADO'],
                    ['pendiente_api_medcol5.orden_externa', '=', $value->orden_externa],
                    ['pendiente_api_medcol5.codigo', '=', $value->codigo],
                    ['pendiente_api_medcol5.usuario', 'RFAST']
                ])->count();

            if ($entregados == 0 || $entregados == null) {

                DB::table('pendiente_api_medcol5')
                    ->where([
                        ['pendiente_api_medcol5.estado', '=', 'PENDIENTE'],
                        ['pendiente_api_medcol5.orden_externa', '=', $value->orden_externa],
                        ['pendiente_api_medcol5.codigo', '=', $value->codigo]
                    ])
                    ->update([
                        'pendiente_api_medcol5.fecha_entrega' =>  $value->fecha_factura,
                        'pendiente_api_medcol5.estado' => 'ENTREGADO',
                        'pendiente_api_medcol5.cantdpx' => $value->cantdpx,
                        'pendiente_api_medcol5.doc_entrega' => $value->documento,
                        'pendiente_api_medcol5.factura_entrega' => $value->factura,
                        'pendiente_api_medcol5.usuario' => 'RFAST',
                        'pendiente_api_medcol5.updated_at' => now()
                    ]);

                $contadorei++;
            }


            // Guardar observación en la tabla ObservacionesApi

            $entregado = ObservacionesApiMedcol5::where([
                ['pendiente_id', $value->idd],
                ['estado', 'ENTREGADO']
            ])->count();

            if ($entregado == 0 || $entregado == null) {

                ObservacionesApiMedcol5::create([
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
                $drogueria = 'EMO1';
                break;
        }


        if (request()->ajax()) {

            if (Auth::user()->drogueria == '1') {

                $data = DB::table('pendiente_api_medcol5')
                    ->where('estado', '=', 'PENDIENTE')
                    ->select('nombre')
                    ->selectRaw('SUM(cantord) as cantord')
                    ->groupBy('nombre')
                    ->get();
            } else {

                $data = DB::table('pendiente_api_medcol5')
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
}
