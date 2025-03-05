<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

use App\Http\Controllers\Controller;
use App\Models\Medcold\PendienteApiMedcold;
use App\Models\Medcold\EntregadosApiMedcold;
use App\Models\Medcold\ObservacionesApiMedcold;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;

class CronApiPendientesDolor extends Command
{
    public $var1 = null;
    public $var2 = null;
    public $ip = null;
    public $res = false;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:api_pendientesdolor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para sincronizar cada 5 minutos los pendientes de dolor que se hayan generado en RFAST';

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
        
         $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = '123456';

        try {

            $response = Http::post("http://190.85.46.246:8000/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);
            

            $token = $response->json()["token"];
            
        
            $responsefacturas = Http::withToken($token)->get("http://190.85.46.246:8000/api/pendientesapi");

            $facturassapi = $responsefacturas->json()['data'];
            
           
            $contador = 0;
            $pendientes = [];

            foreach ($facturassapi as $factura) {
                $existe = PendienteApiMedcold::where('factura', $factura['factura'])->count();

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
                PendienteApiMedcold::insert($pendientes);
            }

            Http::withToken($token)->get("http://190.85.46.246:8000/api/closeallacceso");

            $var = $this->createentregadospi(null);
            
            
            Log::info('Cron Dolor ' .$contador . ' Lineas pendientes y ' . $var . ' Lineas entregadas'. ' Usuario: Server');

        

        } catch (\Exception $e) {


            $response = Http::post("http://192.168.50.98:8000/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];

            $responsefacturas = Http::withToken($token)->get("http://192.168.50.98:8000/api/pendientesapi");

            $facturassapi = $responsefacturas->json()['data'];

            $contador = 0;
            $pendientes = [];

            foreach ($facturassapi as $factura) {
                $existe = PendienteApiMedcold::where('factura', $factura['factura'])->count();

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
                PendienteApiMedcold::insert($pendientes);
            }

            Http::withToken($token)->get("http://192.168.50.98:8000/api/closeallacceso");

            $var = $this->createentregadospilocal(null);

                Log::info('Cron Dolor local ' .$contador . ' Lineas pendientes y ' . $var . ' Lineas entregadas'. ' Usuario: Server');
        }


        
      
    }
    
     public function createentregadospi($var1)
    {
        $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = '123456';

        $response = Http::post(
            "http://190.85.46.246:8000/api/acceso",
            [
                'email' =>  $email,
                'password' => $password,
            ]
        );


        // $this->createapendientespi($request);

        $prueba = $response->json();
        $token = $prueba["token"];

        $responsefacturas = Http::withToken($token)->get("http://190.85.46.246:8000/api/entregadosapi");

        $facturassapi = $responsefacturas->json();
        
        //dd($facturassapi);
        
        $contadorei = 0;
        $contador1 = 0;

        foreach ($facturassapi['data'] as $factura) {


            $existe =  EntregadosApiMedcold::where('factura', $factura['factura'])->count();
            
            

            if ($existe == 0 || $existe == '') {
                EntregadosApiMedcold::create([
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

        Http::withToken($token)->get("http://190.85.46.246:8000/api/closeallacceso");

        $pendientes = DB::table('pendiente_api_medcold')
            ->join('entregados_api_medcold', function ($join) {
                $join->on('pendiente_api_medcold.orden_externa', '=', 'entregados_api_medcold.orden_externa')
                    ->on('pendiente_api_medcold.codigo', '=', 'entregados_api_medcold.codigo');
            })
            ->select(
                'pendiente_api_medcold.id as idd',
                'entregados_api_medcold.orden_externa',
                'entregados_api_medcold.codigo',
                'entregados_api_medcold.cantdpx',
                'entregados_api_medcold.fecha_factura',
                'entregados_api_medcold.documento',
                'entregados_api_medcold.factura'
            )
            ->get();




        foreach ($pendientes as $key => $value) {

            $entregados =
                DB::table('pendiente_api_medcold')
                ->where([
                    ['pendiente_api_medcold.estado', '=', 'ENTREGADO'],
                    ['pendiente_api_medcold.orden_externa', '=', $value->orden_externa],
                    ['pendiente_api_medcold.codigo', '=', $value->codigo],
                    ['pendiente_api_medcold.usuario', 'RFAST']
                ])->count();

            if ($entregados == 0 || $entregados == null) {

                DB::table('pendiente_api_medcold')
                    ->where([
                        ['pendiente_api_medcold.estado', '=', 'PENDIENTE'],
                        ['pendiente_api_medcold.orden_externa', '=', $value->orden_externa],
                        ['pendiente_api_medcold.codigo', '=', $value->codigo]
                    ])
                    ->update([
                        'pendiente_api_medcold.fecha_entrega' =>  $value->fecha_factura,
                        'pendiente_api_medcold.estado' => 'ENTREGADO',
                        'pendiente_api_medcold.cantdpx' => $value->cantdpx,
                        'pendiente_api_medcold.doc_entrega' => $value->documento,
                        'pendiente_api_medcold.factura_entrega' => $value->factura,
                        'pendiente_api_medcold.usuario' => 'RFAST',
                        'pendiente_api_medcold.updated_at' => now()
                    ]);

                $contadorei++;
            }



            // Guardar observación en la tabla ObservacionesApi

            $entregado = ObservacionesApiMedcold::where([
                ['pendiente_id', $value->idd],
                ['estado', 'ENTREGADO']
            ])->count();

            if ($entregado == 0 || $entregado == null) {

                ObservacionesApiMedcold::create([
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
        $password = '123456';

        $response = Http::post(
            "http://192.168.50.98:8000/api/acceso",
            [
                'email' =>  $email,
                'password' => $password,
            ]
        );


        // $this->createapendientespi($request);

        $prueba = $response->json();
        $token = $prueba["token"];

        $responsefacturas = Http::withToken($token)->get("http://192.168.50.98:8000/api/entregadosapi");

        $facturassapi = $responsefacturas->json();

        //dd($facturassapi);
        $contadorei = 0;
        $contador1 = 0;

        foreach ($facturassapi['data'] as $factura) {


            $existe =  EntregadosApiMedcold::where('factura', $factura['factura'])->count();
            
            //dd($existe);

            if ($existe == 0 || $existe == '') {
                EntregadosApiMedcold::create([
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

        Http::withToken($token)->get("http://192.168.50.98:8000/api/closeallacceso");

        $pendientes = DB::table('pendiente_api_medcold')
            ->join('entregadosapi', function ($join) {
                $join->on('pendiente_api_medcold.orden_externa', '=', 'entregadosapi.orden_externa')
                    ->on('pendiente_api_medcold.codigo', '=', 'entregadosapi.codigo');
            })
            ->select(
                'pendiente_api_medcold.id as idd',
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
                DB::table('pendiente_api_medcold')
                ->where([
                    ['pendiente_api_medcold.estado', '=', 'ENTREGADO'],
                    ['pendiente_api_medcold.orden_externa', '=', $value->orden_externa],
                    ['pendiente_api_medcold.codigo', '=', $value->codigo],
                    ['pendiente_api_medcold.usuario', 'RFAST']
                ])->count();

            if ($entregados == 0 || $entregados == null) {

                DB::table('pendiente_api_medcold')
                    ->where([
                        ['pendiente_api_medcold.estado', '=', 'PENDIENTE'],
                        ['pendiente_api_medcold.orden_externa', '=', $value->orden_externa],
                        ['pendiente_api_medcold.codigo', '=', $value->codigo]
                    ])
                    ->update([
                        'pendiente_api_medcold.fecha_entrega' =>  $value->fecha_factura,
                        'pendiente_api_medcold.estado' => 'ENTREGADO',
                        'pendiente_api_medcold.cantdpx' => $value->cantdpx,
                        'pendiente_api_medcold.doc_entrega' => $value->documento,
                        'pendiente_api_medcold.factura_entrega' => $value->factura,
                        'pendiente_api_medcold.usuario' => 'RFAST',
                        'pendiente_api_medcold.updated_at' => now()
                    ]);

                $contadorei++;
            }


            // Guardar observación en la tabla ObservacionesApi

            $entregado = ObservacionesApiMedcold::where([
                ['pendiente_id', $value->idd],
                ['estado', 'ENTREGADO']
            ])->count();

            if ($entregado == 0 || $entregado == null) {

                ObservacionesApiMedcold::create([
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
