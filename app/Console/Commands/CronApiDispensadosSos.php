<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Medcol6\DispensadoApiMedcol6;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;

class CronApiDispensadosSos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:api_dispensadossos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para sincronizar cada 5 minutos los dispensados que se hayan generado en RFAST';

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
        $fechaLimite = Carbon::now()->subDays(7)->format('Y-m-d');
       
        set_time_limit(0); // Desactivar el límite de tiempo de ejecución
        ini_set('memory_limit', '512M');
        
         $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = 'colMed2023**';
       
        
        
         try {
        
                
            $response = Http::post("http://hed08pf9dxt.sn.mynetname.net:8004/api/acceso", [
            'email' =>  $email,
            'password' => $password,
            ]);

            $token = $response->json()["token"];
                
            if($token) {
            

        try {

            

            $responsefacturas = Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/dispensadoapi");

            $facturassapi = $responsefacturas->json()['data'];
    
            $contadorei = 0;
            $contador = 0;
            
            
            // Obtener las facturas existentes en un solo query
            
            $facturasExistentes = DispensadoApiMedcol6::select('factura', 'codigo', 'ID_REGISTRO')
            ->whereIn('factura', array_column($facturassapi, 'factura'))
            ->whereIn('codigo', array_column($facturassapi, 'codigo'))
            ->whereIn('ID_REGISTRO', array_column($facturassapi, 'ID_REGISTRO'))
            ->where('fecha_suministro', '>=', $fechaLimite) // ✅ Comparación precisa con DATETIME
            ->get()
            ->map(function ($item) {
                return "{$item->factura}-{$item->codigo}-{$item->ID_REGISTRO}";
            })->toArray();
            
             $dispensados = [];
             
               foreach ($facturassapi as $factura) {
                // Verificar si la factura ya existe en el array obtenido antes
                $clave = "{$factura['factura']}-{$factura['codigo']}-{$factura['ID_REGISTRO']}";

                if (in_array($clave, $facturasExistentes)) {
                    continue; // Si ya existe, lo ignora
                }

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
                                'cantidad_ordenada'  => trim($factura['cantidad_ordenada']),
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
                                'numeroIdentificacion'  => trim($factura['docmedico']),
                                'mipres'  => trim($factura['mipres']),
                                'precio_unitario'  => trim($factura['precio_unitario']),
                                'valor_total'  => trim($factura['valor_total']),
                                'reporte_entrega_nopbs'  => trim($factura['reporte_entrega_nopbs']),
                                'estado'  => trim($factura['estado']),
                                'centroprod'  => trim($factura['centroprod']),
                                'drogueria'  => trim($factura['drogueria']),
                                'cajero'  => trim($factura['cajero']),
                                'documento_origen'  => trim($factura['documento_origen']),
                                'factura_origen'  => trim($factura['factura_origen']),
                                'ciudad'  => trim($factura['ciudad']),
                                'via'  => trim($factura['via']),
                                'ambito'  => trim($factura['ambito']),
                                'tipoidmedico'  => trim($factura['tipoidmedico']),
                                'especialidadmedico'  => trim($factura['especialidadmedico']),
                                'tipocontrato'  => trim($factura['tipocontrato']),
                                'cod_dispen_transacc'  => trim($factura['cod_dispen_transacc']),
                                'cobertura'  => trim($factura['cobertura']),
                                'cod_dispensario_sos'  => trim($factura['cod_dispensario_sos']),
                                'tipoentrega'  => trim($factura['tipoentrega']),
                                'ID_REGISTRO'  => trim($factura['ID_REGISTRO']),
                                'created_at'  => now()
                    ];
                    
                    $contador++;
                    
               }
               
                    
                   if (!empty($dispensados)) {
                    $chunks = array_chunk($dispensados, 500); // Divide en lotes de 500 registros
                    foreach ($chunks as $chunk) {
                        DispensadoApiMedcol6::insert($chunk);
                    }
                }

            
           
            Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/closeallacceso");
            
            Log::info('Cron Centralizado '.$contador . ' Lineas dispensadas'. ' Usuario: server');
            
           

                    } catch (\Exception $e) {
                        
                         // Manejo de la excepción
                    Log::error($e->getMessage()); // Registrar el error en los logs de Laravel
                    
                  
                    }
        
            }
        
         }catch (\Exception $e) {
             
             
      try {         
            
            $response = Http::post("http://192.168.66.91:8004/api/acceso", [
            'email' =>  $email,
            'password' => $password,
            ]);

            $token = $response->json()["token"];
                
            if($token) {
             
             
             
              try {
        
        
            $responsefacturas = Http::withToken($token)->get("http://192.168.66.91:8004/api/dispensadoapi");

            $facturassapi = $responsefacturas->json()['data'];
    
            $contadorei = 0;
            $contador = 0;
            
            
            // Obtener las facturas existentes en un solo query
            
           $facturasExistentes = DispensadoApiMedcol6::select('factura', 'codigo', 'ID_REGISTRO')
            ->whereIn('factura', array_column($facturassapi, 'factura'))
            ->whereIn('codigo', array_column($facturassapi, 'codigo'))
            ->whereIn('ID_REGISTRO', array_column($facturassapi, 'ID_REGISTRO'))
            ->where('fecha_suministro', '>=', $fechaLimite) // ✅ Comparación precisa con DATETIME
            ->get()
            ->map(function ($item) {
                return "{$item->factura}-{$item->codigo}-{$item->ID_REGISTRO}";
            })->toArray();
            
             $dispensados = [];
             
               foreach ($facturassapi as $factura) {
                // Verificar si la factura ya existe en el array obtenido antes
                $clave = "{$factura['factura']}-{$factura['codigo']}-{$factura['ID_REGISTRO']}";

                if (in_array($clave, $facturasExistentes)) {
                    continue; // Si ya existe, lo ignora
                }

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
                                'cantidad_ordenada'  => trim($factura['cantidad_ordenada']),
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
                                'numeroIdentificacion'  => trim($factura['docmedico']),
                                'mipres'  => trim($factura['mipres']),
                                'precio_unitario'  => trim($factura['precio_unitario']),
                                'valor_total'  => trim($factura['valor_total']),
                                'reporte_entrega_nopbs'  => trim($factura['reporte_entrega_nopbs']),
                                'estado'  => trim($factura['estado']),
                                'centroprod'  => trim($factura['centroprod']),
                                'drogueria'  => trim($factura['drogueria']),
                                'cajero'  => trim($factura['cajero']),
                                'documento_origen'  => trim($factura['documento_origen']),
                                'factura_origen'  => trim($factura['factura_origen']),
                                'ciudad'  => trim($factura['ciudad']),
                                'via'  => trim($factura['via']),
                                'ambito'  => trim($factura['ambito']),
                                'tipoidmedico'  => trim($factura['tipoidmedico']),
                                'especialidadmedico'  => trim($factura['especialidadmedico']),
                                'tipocontrato'  => trim($factura['tipocontrato']),
                                'cod_dispen_transacc'  => trim($factura['cod_dispen_transacc']),
                                'cobertura'  => trim($factura['cobertura']),
                                'cod_dispensario_sos'  => trim($factura['cod_dispensario_sos']),
                                'tipoentrega'  => trim($factura['tipoentrega']),
                                'ID_REGISTRO'  => trim($factura['ID_REGISTRO']),
                                'created_at'  => now()
                    ];
                    
                    $contador++;
                    
               }
               
                    
                   if (!empty($dispensados)) {
                    $chunks = array_chunk($dispensados, 500); // Divide en lotes de 500 registros
                    foreach ($chunks as $chunk) {
                        DispensadoApiMedcol6::insert($chunk);
                    }
                }

            
           
            Http::withToken($token)->get("http://192.168.66.91:8004/api/closeallacceso");
            
            Log::info('Cron Centralizado '.$contador . ' Lineas dispensadas'. ' Usuario: server');
            
           

                    } catch (\Exception $e) {
                        
                         // Manejo de la excepción
                    Log::error($e->getMessage()); // Registrar el error en los logs de Laravel
                    
                  
                    }
        
            
            }    
           
            
              }catch (\Exception $e) {
                
                
                         // Manejo de la excepción
                 Log::error($e->getMessage()); // Registrar el error en los logs de Laravel
             
               
            }

        }
    }
}
