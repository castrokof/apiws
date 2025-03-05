<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

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

class CronApiPendientesSaludMental extends Command
{
    public $var1 = null;
    public $var2 = null;
    public $res = false;
    public $ip = 'http://hef08s3bxw8.sn.mynetname.net';
    public $puerto = ':8000';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:api_pendientessaludmental';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para sincronizar cada 5 minutos los pendientes de salud mental que se hayan generado en RFAST';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Request $request)
    {
        $ip = 'http://hef08s3bxw8.sn.mynetname.net';
        $puerto = ':8000';   
        $email = 'sistemas.saludtempus@gmail.com'; // Auth::user()->email
        $password = '12345678';

        try {

            $response = Http::post($ip.$puerto."/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];


            $responsefacturas = Http::withToken($token)->get($ip.$puerto."/api/pendientesapi");

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

            Http::withToken($token)->get($ip.$puerto."/api/closeallacceso");

            $var = $this->createentregadospi(null);

             Log::info('Cron Salud Mental ' .$contador . ' Lineas pendientes y ' . $var . ' Lineas entregadas'. ' Usuario: Server');

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

            Log::info('Cron Salud Mental local ' .$contador . ' Lineas pendientes y ' . $var . ' Lineas entregadas'. ' Usuario: Server');
        }

        
      
    }
    
      public function createentregadospi($var1)
    {   
        $ip = 'http://hef08s3bxw8.sn.mynetname.net';
        $puerto = ':8000';
        $email = 'sistemas.saludtempus@gmail.com'; // Auth::user()->email
        $password = '12345678';

        $response = Http::post(
            $ip.$puerto."/api/acceso",
            [
                'email' =>  $email,
                'password' => $password,
            ]
        );


        // $this->createapendientespi($request);

        $prueba = $response->json();
        $token = $prueba["token"];

        $responsefacturas = Http::withToken($token)->get($ip.$puerto."/api/entregadosapi");

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

        Http::withToken($token)->get($ip.$puerto."/api/closeallacceso");

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
    
    
    
   
}
