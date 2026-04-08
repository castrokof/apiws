<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Controller;
use App\Models\Medcol6\PendienteApiMedcol6;
use App\Models\Medcol6\EntregadosApiMedcol6;
use App\Models\Medcol6\ObservacionesApiMedcol6;
use Carbon\Carbon;

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
        // Obtener la fecha límite de los últimos 7 días
        $fechaLimite = Carbon::now()->startOfWeek()->subDays(8)->startOfDay();

        $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = 'colMed2023**';
        $usuario = "Servidor";

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

        $contador = 0;
        $facturasExistentes = collect();

        // Consultar la base de datos en chunks para evitar demasiados placeholders
        foreach (array_chunk($facturassapi, 500) as $chunk) {
            $resultados = PendienteApiMedcol6::select('documento', 'factura', 'codigo')
                ->where(function($query) use ($chunk) {
                    foreach($chunk as $item) {
                        $query->orWhere(function($subQuery) use ($item) {
                            $subQuery->where('documento', trim($item['documento']))
                                    ->where('factura', trim($item['factura']))
                                    ->where('codigo', trim($item['codigo']));
                        });
                    }
                })
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
        
        
        // NO eliminar la variable aquí - la necesitamos después
        // unset($facturasExistentesFlip); // <-- QUITAR ESTA LÍNEA

        $pendientes = [];
        
        
              // Variables de control
        $totalAPI = count($facturassapi);
        $totalExistentes = count($facturasExistentesFlip);
        $omitidas = 0;
        $procesadas = 0;
        
        Log::info("🚀 INICIO: {$totalAPI} facturas del API, {$totalExistentes} ya en BD");
        
        foreach ($facturassapi as $factura) {
            $clave = trim($factura['documento']) . '-' . trim($factura['factura']) . '-' . trim($factura['codigo']);
            
            if (isset($facturasExistentesFlip[$clave])) {
                $omitidas++;
                // Solo mostrar las primeras 3 y cada 50
                if ($omitidas <= 3 || $omitidas % 50 == 0) {
                    Log::info("⏭️  OMITIDA CRON #{$omitidas}: {$clave}");
                }
                continue;
            }
            
            $procesadas++;
            // Solo mostrar las primeras 3 y cada 50  
            if ($procesadas <= 3 || $procesadas % 50 == 0) {
                Log::info("✅ NUEVA CRON #{$procesadas}: {$clave}");
            }

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
                'observaciones' => trim($factura['observaciones']),
                'convenio' => trim($factura['convenio']),
                'contrato' => trim($factura['contrato']),
                'dx_principal' => trim($factura['dx_principal']),
                'dx_relacionado' => trim($factura['dx_relacionado']),
                'concentracion' => trim($factura['concentracion']),
                'forma' => trim($factura['forma']),
                'via' => trim($factura['via']),
                'atc' => trim($factura['atc']),
                'nit_dispensario' => trim($factura['nit_dispensario']),
                'principio_activo' => trim($factura['principio_activo']),
                'genero' => trim($factura['genero']),
                'municipio' => trim($factura['municipio']),
                'invima' => trim($factura['invima']),
                'created_at' => now()
            ];

            $contador++;
        }

        if (!empty($pendientes)) {
            $chunks = array_chunk($pendientes, 500);

            foreach ($chunks as $chunk) {
                PendienteApiMedcol6::insertOrIgnore($chunk);
            }
        }

        // Liberar memoria
        unset($facturasExistentesFlip);
        unset($facturasExistentes);

        Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/closeallacceso");

        //$var = $this->createentregadospi(null);
        
        // Resultado final
        $porcentajeOmitidas = round(($omitidas / $totalAPI) * 100, 1);
        $porcentajeProcesadas = round(($procesadas / $totalAPI) * 100, 1);
        
        Log::info("🏁 RESULTADO CRON: {$omitidas} omitidas ({$porcentajeOmitidas}%) | {$procesadas} nuevas ({$porcentajeProcesadas}%)");
        
        // Ejecutar createentregadospi
        $lineasEntregadas = $this->createentregadospi(null);
        $pendientesActualizados = $lineasEntregadas['actualizados'] ?? 0;
                
        
        /*Log::info('Desde servidor syncapi pendiente ' . $contador . ' Lineas creadas y ' . $var . ' Lineas entregadas' . ' Usuario: ' . $usuario);*/
        Log::info('CRON: Desde servidor syncapi pendiente ' . $contador . ' Lineas creadas, ' . 
                  $lineasEntregadas . ' Lineas entregadas y ' . 
                  $pendientesActualizados . ' Pendientes actualizados - Usuario: ' . $usuario);

        return 0;

    } catch (\Exception $e) {

        try {
            $response = Http::post("http://192.168.66.95:8004/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];

            $responsefacturas = Http::withToken($token)->get("http://192.168.66.95:8004/api/pendientesapi");

            $facturassapi = $responsefacturas->json()['data'];

            $contador = 0;
            $pendientes = [];

            // Usar la misma lógica que en el try principal
            $facturasExistentes = collect();

            foreach (array_chunk($facturassapi, 500) as $chunk) {
                $resultados = PendienteApiMedcol6::select('documento', 'factura', 'codigo')
                    ->where(function($query) use ($chunk) {
                        foreach($chunk as $item) {
                            $query->orWhere(function($subQuery) use ($item) {
                                $subQuery->where('documento', trim($item['documento']))
                                        ->where('factura', trim($item['factura']))
                                        ->where('codigo', trim($item['codigo']));
                            });
                        }
                    })
                    ->where('fecha_factura', '>=', $fechaLimite)
                    ->get();
                    
                $facturasExistentes = $facturasExistentes->merge($resultados);
            }

            $facturasExistentesFlip = array_flip(
                $facturasExistentes->map(function ($item) {
                    return trim($item->documento) . '-' . trim($item->factura) . '-' . trim($item->codigo);
                })->toArray()
            );

            foreach ($facturassapi as $factura) {
                $clave = trim($factura['documento']) . '-' . trim($factura['factura']) . '-' . trim($factura['codigo']);

                if (!isset($facturasExistentesFlip[$clave])) {
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
                        'observaciones' => trim($factura['observaciones']),
                        'convenio' => trim($factura['convenio']),
                        'contrato' => trim($factura['contrato']),
                        'dx_principal' => trim($factura['dx_principal']),
                        'dx_relacionado' => trim($factura['dx_relacionado']),
                        'concentracion' => trim($factura['concentracion']),
                        'forma' => trim($factura['forma']),
                        'via' => trim($factura['via']),
                        'atc' => trim($factura['atc']),
                        'nit_dispensario' => trim($factura['nit_dispensario']),
                        'principio_activo' => trim($factura['principio_activo']),
                        'genero' => trim($factura['genero']),
                        'municipio' => trim($factura['municipio']),
                        'invima' => trim($factura['invima']),
                        'created_at' => now()
                    ];

                    $contador++;
                }
            }

            if (!empty($pendientes)) {
                $chunks = array_chunk($pendientes, 500);
                foreach ($chunks as $chunk) {
                    PendienteApiMedcol6::insertOrIgnore($chunk);
                }
            }

            Http::withToken($token)->get("http://192.168.66.95:8004/api/closeallacceso");

            $var = $this->createentregadospilocal(null);
            
            // Ejecutar createentregadospi
            $lineasEntregadas = $this->createentregadospi(null);

            Log::info('CRON: Desde servidor syncapi (servidor local) ' . $contador . ' Lineas creadas y ' . $var . ' Lineas entregadas' . ' Usuario: ' . $usuario);

          

        } catch (\Exception $localException) {
            Log::error('Error en ambos servidores. Error principal: ' . $e->getMessage() . 
                       ' - Error local: ' . $localException->getMessage() . ' Usuario: ' . $usuario);
            return 1;
        }
    }

      
    }
    
    
     private function actualizarPendientesOptimizado()
        {
            $contadorei = 0;
            
            try {
                // Obtener fecha límite para consulta (últimos 90 días)
                $fechaLimite = now()->subDays(90)->startOfDay();
                
                // Obtener pendientes que tienen entregas registradas DESPUÉS de su fecha de creación
                $pendientes = DB::table('pendiente_api_medcol6 as p')
                    ->join('entregados_api_medcol6 as e', function ($join) use ($fechaLimite) {
                        $join->on('p.orden_externa', '=', 'e.orden_externa')
                            ->on('p.codigo', '=', 'e.codigo')
                            // CLAVE: La fecha de entrega debe ser mayor o igual a la fecha del pendiente
                            ->whereColumn('e.fecha_factura', '>=', 'p.fecha_factura')
                            ->where('e.fecha_factura', '>=', $fechaLimite);
                    })
                    ->select(
                        'p.id as idd',
                        'p.orden_externa',
                        'p.codigo',
                        'p.fecha_factura as fecha_pendiente',
                        'e.cantdpx',
                        'e.fecha_factura as fecha_entrega',
                        'e.documento',
                        'e.factura',
                        DB::raw('DATEDIFF(e.fecha_factura, p.fecha_factura) as dias_diferencia')
                    )
                    ->whereIn('p.estado', ['PENDIENTE', 'DESABASTECIDO', 'TRAMITADO', 'VENCIDO', 'PENDIENTEV1'])
                    ->where('p.fecha_factura', '>=', $fechaLimite)
                    // Verificar que no se haya actualizado previamente por RFAST
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('pendiente_api_medcol6 as p2')
                            ->whereColumn('p2.orden_externa', 'p.orden_externa')
                            ->whereColumn('p2.codigo', 'p.codigo')
                            ->where('p2.estado', 'ENTREGADO')
                            ->where('p2.usuario', 'RFAST')
                            ->whereColumn('p2.id', '=', 'p.id');
                    })
                    // Ordenar por diferencia de días para procesar primero las entregas más antiguas
                    ->orderBy('p.orden_externa')
                    ->orderBy('p.codigo')
                    ->orderBy('e.fecha_factura')
                    ->get();
            
                // Agrupar por pendiente para tomar solo la primera entrega válida
                $pendientesAgrupados = $pendientes->groupBy('idd')->map(function ($grupo) {
                    return $grupo->first(); // Tomar la primera entrega (más antigua)
                });
            
                $observacionesData = [];
            
                foreach ($pendientesAgrupados as $pendiente) {
                    // Actualización individual con los datos específicos de cada entrega
                    DB::table('pendiente_api_medcol6')
                        ->where('id', $pendiente->idd)
                        ->whereIn('estado', ['PENDIENTE', 'DESABASTECIDO', 'TRAMITADO', 'VENCIDO', 'PENDIENTEV1'])
                        ->update([
                            'estado' => 'ENTREGADO',
                            'usuario' => 'RFAST',
                            'fecha_entrega' => $pendiente->fecha_entrega,
                            'cantdpx' => $pendiente->cantdpx,
                            'doc_entrega' => $pendiente->documento,
                            'factura_entrega' => $pendiente->factura,
                            't_entrega_dias' => $pendiente->dias_diferencia,
                            'updated_at' => now()
                        ]);
                    
                    // Preparar datos para inserción de observaciones
                    $observacionesData[] = [
                        'pendiente_id' => $pendiente->idd,
                        'observacion' => sprintf(
                            'Entregado automáticamente. Generado: %s, Entregado: %s (%d días después). Factura: %s, Cantidad: %s',
                            $pendiente->fecha_pendiente,
                            $pendiente->fecha_entrega,
                            $pendiente->dias_diferencia,
                            $pendiente->factura ?? 'N/A',
                            $pendiente->cantdpx ?? 'N/A'
                        ),
                        'usuario' => 'RFAST',
                        'estado' => 'ENTREGADO',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    
                    $contadorei++;
                }
            
                if ($contadorei > 0) {
                    // Inserción masiva optimizada de observaciones
                    $pendientesIds = array_column($observacionesData, 'pendiente_id');
                    $pendientesIdsExistentes = ObservacionesApiMedcol6::whereIn('pendiente_id', $pendientesIds)
                        ->where('estado', 'ENTREGADO')
                        ->pluck('pendiente_id')
                        ->toArray();
                    
                    $observacionesNuevas = [];
                    foreach ($observacionesData as $observacion) {
                        if (!in_array($observacion['pendiente_id'], $pendientesIdsExistentes)) {
                            $observacionesNuevas[] = $observacion;
                        }
                    }
                    
                    if (!empty($observacionesNuevas)) {
                        ObservacionesApiMedcol6::insert($observacionesNuevas);
                    }
            
                    Log::info("Actualizados {$contadorei} registros pendientes con entregas posteriores");
                } else {
                    Log::info("No se encontraron pendientes con entregas válidas para actualizar");
                }
            
                return $contadorei;
                
            } catch (\Exception $e) {
                Log::error('Error actualizando pendientes: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                return $contadorei;
            }
        }

    public function createentregadospi($var1)
{
    $email = 'castrokofdev@gmail.com';
    $password = 'colMed2023**';
    
    try {
        // Autenticación
        $response = Http::timeout(30)->post(
            "http://hed08pf9dxt.sn.mynetname.net:8004/api/acceso",
            [
                'email' => $email,
                'password' => $password,
            ]
        );

        if (!$response->successful()) {
            Log::error('Error en autenticación API: ' . $response->body());
            return ['insertados' => 0, 'actualizados' => 0];
        }

        $authData = $response->json();
        $token = $authData["token"] ?? null;

        if (!$token) {
            Log::error('Token no recibido en la respuesta de autenticación');
            return ['insertados' => 0, 'actualizados' => 0];
        }

        // Obtener datos de entregados
        $responsefacturas = Http::withToken($token)
            ->timeout(60)
            ->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/entregadosapi");

        if (!$responsefacturas->successful()) {
            Log::error('Error al obtener datos de entregados: ' . $responsefacturas->body());
            return ['insertados' => 0, 'actualizados' => 0];
        }

        $facturassapi = $responsefacturas->json();
        
        // Verificar estructura de datos
        if (!isset($facturassapi['data']) || !is_array($facturassapi['data'])) {
            Log::error('Estructura de datos inválida recibida de la API');
            return ['insertados' => 0, 'actualizados' => 0];
        }

        $contador1 = 0;
        $batchSize = 100;
        $facturas = collect($facturassapi['data']);
        
        // Obtener fecha límite para consulta local (últimos 30 días)
        $fechaLimite = now()->subDays(30)->startOfDay();

        Log::info("🔄 CRON: Iniciando inserción de entregados. Total a procesar: " . $facturas->count());

        // Procesar en lotes para mejorar rendimiento
        $facturas->chunk($batchSize)->each(function ($batch, $chunkIndex) use (&$contador1, $fechaLimite) {
            
            // Obtener registros existentes SOLO de los últimos 30 días
            $existingRecords = EntregadosApiMedcol6::whereIn('factura', $batch->pluck('factura'))
                ->whereIn('documento', $batch->pluck('documento'))
                ->whereIn('codigo', $batch->pluck('codigo'))
                ->where('created_at', '>=', $fechaLimite)
                ->select('factura', 'documento', 'codigo')
                ->get()
                ->keyBy(function ($item) {
                    return trim($item->factura) . '|' . trim($item->documento) . '|' . trim($item->codigo);
                });

            $dataToInsert = [];
            
            foreach ($batch as $factura) {
                $key = trim($factura['factura']) . '|' . trim($factura['documento']) . '|' . trim($factura['codigo']);
                
                if (!$existingRecords->has($key)) {
                    $dataToInsert[] = [
                        'Tipodocum' => trim($factura['Tipodocum'] ?? ''),
                        'cantdpx' => trim($factura['cantdpx'] ?? ''),
                        'cantord' => trim($factura['cantord'] ?? ''),
                        'fecha_factura' => trim($factura['fecha_factura'] ?? ''),
                        'fecha' => trim($factura['fecha'] ?? ''),
                        'historia' => trim($factura['historia'] ?? ''),
                        'apellido1' => trim($factura['apellido1'] ?? ''),
                        'apellido2' => trim($factura['apellido2'] ?? ''),
                        'nombre1' => trim($factura['nombre1'] ?? ''),
                        'nombre2' => trim($factura['nombre2'] ?? ''),
                        'cantedad' => trim($factura['cantedad'] ?? ''),
                        'direcres' => trim($factura['direcres'] ?? ''),
                        'telefres' => trim($factura['telefres'] ?? ''),
                        'documento' => trim($factura['documento'] ?? ''),
                        'factura' => trim($factura['factura'] ?? ''),
                        'codigo' => trim($factura['codigo'] ?? ''),
                        'nombre' => trim($factura['nombre'] ?? ''),
                        'cums' => trim($factura['cums'] ?? ''),
                        'cantidad' => trim($factura['cantidad'] ?? ''),
                        'cajero' => trim($factura['cajero'] ?? ''),
                        'orden_externa' => trim($factura['orden_externa'] ?? ''),
                        'doc_entrega' => trim($factura['documento'] ?? ''),
                        'factura_entrega' => trim($factura['factura'] ?? ''),
                        'centroproduccion' => trim($factura['CENTROPRODUCCION'] ?? ''),
                        'observaciones' => trim($factura['observaciones'] ?? ''),
                        'usuario' => 'RFAST',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    
                    $contador1++;
                }
            }
            
            // Inserción masiva si hay datos
            if (!empty($dataToInsert)) {
                EntregadosApiMedcol6::insert($dataToInsert);
                Log::info("✅ Lote " . ($chunkIndex + 1) . ": Insertados " . count($dataToInsert) . " registros");
            }
        });

        // Cerrar sesión en la API
        Http::withToken($token)
            ->timeout(30)
            ->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/closeallacceso");

        Log::info("📊 Insertados {$contador1} registros nuevos en entregados_api_medcol6");
        Log::info("🔄 Iniciando actualización de pendientes...");

        // Actualizar pendientes de forma optimizada
        $contadorei = $this->actualizarPendientesOptimizado();

        Log::info("✅ Proceso completado. Nuevos registros: {$contador1}, Pendientes actualizados: {$contadorei}");
        
        return [
            'insertados' => $contador1,
            'actualizados' => $contadorei
        ];
        
    } catch (\Exception $e) {
        Log::error('❌ Error en createentregadospi: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return ['insertados' => 0, 'actualizados' => 0];
    }
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
                    'observaciones' => trim($factura['observaciones']),
                    'usuario' => 'RFAST',
                    'created_at' => now(),
                    'updated_at' => now()
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
    
   
}
