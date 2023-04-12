<?php

namespace App\Http\Controllers;

use App\EntregadosApi;
use App\PendientesApi;
use App\ObservacionesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class PendienteApiController extends Controller
{
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
                    $button = '<button type="button" name="edit_pendiente" id="' . $pendiente->id . '" class="edit_pendiente btn btn-app bg-info tooltipsC" title="Editar"  ><span class="badge bg-teal">Editar</span><i class="fas fa-pen"></i> Editar </button>';

                    return $button;
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
    public function createapendientespi()
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

        $responsefacturas = Http::withToken($token)->get("http://190.145.32.226:8000/api/pendientesapi");

        $facturassapi = $responsefacturas->json();

        //dd($facturassapi);

        $contador = 0;

        foreach ($facturassapi['data'] as $factura) {


            $existe =  PendientesApi::where('factura', $factura['factura'])->count();

            if ($existe == 0 || $existe == '') {
                PendientesApi::create([
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
                ]);

                $contador++;
            }
        }

        Http::withToken($token)->get("http://190.145.32.226:8000/api/closeallacceso");

        $this->createentregadospi();


        if ($contador > 0) {
            return response()->json(['respuesta' => $contador . ' Lineas creadas', 'titulo' => 'Creando lineas', 'icon' => 'success']);
        } else {
            return response()->json(['respuesta' => $contador . ' Lineas creadas', 'titulo' => 'No se crearon lineas', 'icon' => 'warning']);
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
                    $button = '<button type="button" name="edit_pendiente" id="' . $pendiente->id . '" class="edit_pendiente btn btn-app bg-info tooltipsC" title="Editar pendiente"  ><span class="badge bg-teal">Editar</span><i class="fas fa-pen"></i> Editar </button>';

                    return $button;
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
                    $button = '<button type="button" name="edit_pendiente" id="' . $pendiente->id . '" class="edit_pendiente btn btn-app bg-info tooltipsC" title="Editar pendiente"  ><span class="badge bg-teal">Editar</span><i class="fas fa-pen"></i> Editar </button>';

                    return $button;
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
                    $button = '<button type="button" name="edit_pendiente" id="' . $pendiente->id . '" class="edit_pendiente btn btn-app bg-info tooltipsC" title="Editar pendiente"  ><span class="badge bg-teal">Editar</span><i class="fas fa-pen"></i> Editar </button>';

                    return $button;
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
                    $button = '<button type="button" name="edit_pendiente" id="' . $pendiente->id . '" class="edit_pendiente btn btn-app bg-info tooltipsC" title="Editar pendiente"  ><span class="badge bg-teal">Editar</span><i class="fas fa-pen"></i> Editar </button>';

                    return $button;
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

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }


        if (request()->ajax()) {
            $pendientesapi = PendientesApi::findOrFail($id);
            $pendientesapi->fill($request->all());

            if ($request->input('enviar_fecha_entrega') == 'true') {
                if ($request->fecha_entrega < $pendientesapi->fecha || $request->fecha_entrega > now()->format('Y-m-d')) {
                    return response()->json(['errors' => ['La fecha de entrega debe estar entre la fecha de la factura y la fecha actual']]);
                }
                $pendientesapi->fecha_entrega = $request->fecha_entrega;
            }

            if ($request->input('enviar_fecha_impresion') == 'true') {
                if ($request->fecha_impresion < $pendientesapi->fecha || $request->fecha_impresion > now()->format('Y-m-d')) {
                    return response()->json(['errors' => ['La fecha de impresión debe estar entre la fecha de la factura y la fecha actual']]);
                }
                $pendientesapi->fecha_impresion = $request->fecha_impresion;
            }

            $pendientesapi->save();

            // Guardar observación en la tabla ObservacionesApi
            ObservacionesApi::create([
                'pendiente_id' => $pendientesapi->id,
                'observacion' => $request->input('observacion'),
                'estado' => $request->input('estado')
            ]);
        }

        return response()->json(['success' => 'ok1']);
    }




    public function update3(Request $request, $id)
    {
        $rules = array(
            'fecha_entrega' => 'required',
            'estado' => 'required',
            'Tipodocum' => 'required',
            'cantdpx' => 'required',
            'cantord' => 'required',
            'fecha_factura' => 'required',
            'fecha' => 'required',
            'historia' => 'required',
            'apellido1' => 'required',
            'apellido2' => 'required',
            'nombre1' => 'required',
            'nombre2' => 'required',
            'cantedad' => 'required',
            'direcres' => 'required',
            'telefres' => 'required',
            'documento' => 'required',
            'factura' => 'required',
            'codigo' => 'required',
            'nombre' => 'required',
            'cums' => 'required',
            'cantidad' => 'required',
            'cajero' => 'required',
            'usuario',
            'fecha_impresion'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $data = DB::table('pendientesapi')->where('id', '=', $id)
            ->update([
                'fecha_entrega' => $request->fecha_entrega,
                'estado' => $request->estado,
                'Tipodocum' => $request->Tipodocum,
                'cantdpx' => $request->cantdpx,
                'cantord' => $request->cantord,
                'fecha_factura' => $request->fecha_factura,
                'fecha' => $request->fecha,
                'historia' => $request->historia,
                'apellido1' => $request->apellido1,
                'apellido2' => $request->apellido2,
                'nombre1' => $request->nombre1,
                'nombre2' => $request->nombre2,
                'cantedad' => $request->cantedad,
                'direcres' => $request->direcres,
                'telefres' => $request->telefres,
                'documento' => $request->documento,
                'factura' => $request->factura,
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'cums' => $request->cums,
                'cantidad' => $request->cantidad,
                'cajero' => $request->cajero,
                'updated_at' => now(),
            ]);

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
    public function destroy($id)
    {
        //
    }


    public function createentregadospi()
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
                    'orden_externa' => trim($factura['ORDEN_EXTERNA'])
                ]);

                $contador1++;
            }
        }

        Http::withToken($token)->get("http://190.145.32.226:8000/api/closeallacceso");

       $pendientes = DB::table('pendientesapi')
       ->join('entregadosapi',function($join){
        $join->on('pendientesapi.orden_externa','=','entregadosapi.orden_externa')
        ->on('pendientesapi.codigo','=','entregadosapi.codigo');
       })
       ->select('entregadosapi.orden_externa','entregadosapi.codigo','entregadosapi.cantdpx', 'entregadosapi.fecha_factura')
       ->get();


foreach ($pendientes as $key => $value) {


    DB::table('pendientesapi')
    ->where([
        ['pendientesapi.estado', '=', 'PENDIENTE'],
        ['pendientesapi.orden_externa','=',$value->orden_externa],
        ['pendientesapi.codigo','=',$value->codigo]
        ])
    ->update([
        'pendientesapi.fecha_entrega'=>  $value->fecha_factura,
        'pendientesapi.estado' => 'ENTREGADO',
        'pendientesapi.cantdpx' => $value->cantdpx,
        'pendientesapi.usuario'=> 'RFAST',
        'pendientesapi.updated_at' => now()
    ]);
}




       $datas1 = DB::table('pendientesapi')
        ->join('entregadosapi',function($join){
         $join->on('pendientesapi.orden_externa','=','entregadosapi.orden_externa')
         ->on('pendientesapi.codigo','=','entregadosapi.codigo');
        })
         ->where([
             ['pendientesapi.estado', '=', 'PENDIENTE']


           ])->get();

            dd($datas1);

        return $datas1;


    }

}
