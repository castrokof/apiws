<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
use Illuminate\Support\Facades\Log;


class CronApiPendientes extends Command
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
    protected $signature = 'cron:api_pendientes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para sincronizar cada hora los pendientes que se hayan generado en RFAST';

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
        $password = 'colMed2023**';
        
         

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
                        'orden_externa' => trim($factura['ORDEN_EXTERNA']),
                        'centroproduccion' => trim($factura['CENTROPRODUCCION']),
                        'observaciones' => trim($factura['observaciones'])
                    ];

                    $contador++;
                }
            }

            if (!empty($pendientes)) {
                PendienteApiMedcol3::insert($pendientes);
            }

            Http::withToken($token)->get("http://hcp080m81s7.sn.mynetname.net:8001/api/closeallacceso");

            $var = $this->createentregadospi(null);
            
            
            // Escribir un mensaje en el registro de información
           Log::info('Cron Autopista ' .$contador . ' Lineas pendientes y ' . $var . ' Lineas entregadas '.' Usuario: Server');

           

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
                        'orden_externa' => trim($factura['ORDEN_EXTERNA']),
                        'centroproduccion' => trim($factura['CENTROPRODUCCION']),
                        'observaciones' => trim($factura['observaciones'])
                    ];

                    $contador++;
                }
            }

            if (!empty($pendientes)) {
                PendienteApiMedcol3::insert($pendientes);
            }

            Http::withToken($token)->get("http://192.168.10.27/api/closeallacceso");

            $var = $this->createentregadospilocal(null);

           
             
             Log::info('Cron Autopista local ' .$contador . ' Lineas pendientes y ' . $var . ' Lineas entregadas '.' Usuario: Server');

           
        }

      
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

          
          Log::info('Entro a funcion entregados');
          
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

        $pendientes = DB::table('pendiente_api_medcol3')
            ->join('entregadosapi', function ($join) {
                $join->on('pendiente_api_medcol3.orden_externa', '=', 'entregadosapi.orden_externa')
                    ->on('pendiente_api_medcol3.codigo', '=', 'entregadosapi.codigo');
            })
            ->select(
                'pendiente_api_medcol3.id as idd',
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



        return $this->var2 = $contadorei;
    }
    
   
}
