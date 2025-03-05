<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Medcol5\DispensadoApiMedcol5;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;

class CronApiDispensadosEmcali extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:api_dispensadosemcali';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para sincronizar cada  minuto los dispensados que se hayan generado en RFAST';

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
        
        
        set_time_limit(0); // Desactivar el límite de tiempo de ejecución
        
         $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = 'colMed2023**';
       
        
        
         try {
        
                
            $response = Http::post("http://hed08pf9dxt.sn.mynetname.net:8000/api/acceso", [
            'email' =>  $email,
            'password' => $password,
            ]);

            $token = $response->json()["token"];
                
            if($token) {
            

        try {


            $responsefacturas = Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8000/api/dispensadoapi");

            $facturassapi = $responsefacturas->json()['data'];
    
            $contadorei = 0;
            $contador = 0;

            foreach ($facturassapi as $factura) {
                
            // Verificar si la factura ya existe en la base de datos
   
             
             $existe = DispensadoApiMedcol5::where([['factura', $factura['factura']], ['codigo', $factura['codigo']]])->exists();
    
                $dispensados = [];
                
                if (!$existe) {
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
                    'mipres'  => trim($factura['mipres']),
                    'precio_unitario'  => trim($factura['precio_unitario']),
                    'valor_total'  => trim($factura['valor_total']),
                    'reporte_entrega_nopbs'  => trim($factura['reporte_entrega_nopbs']),
                    'estado'  => trim($factura['estado']),
                    'centroprod'  => trim($factura['centroprod']),
                    'drogueria'  => trim($factura['drogueria']),
                    'cajero'  => trim($factura['cajero']),
                    'documento_origen'  => trim($factura['documento_origen']),
                    'factura_origen'  => trim($factura['factura_origen'])
                    ];
                    
                              if (!empty($dispensados)) {
                            DispensadoApiMedcol5::insert($dispensados);
                        }

                    $contador++;
                        }
                       
                    } 
            
           
            Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8000/api/closeallacceso");
            
            Log::info('Cron Emcali '.$contador . ' Lineas dispensadas'. ' Usuario: server');
            
           

                    } catch (\Exception $e) {
                        
                         // Manejo de la excepción
                    Log::error($e->getMessage()); // Registrar el error en los logs de Laravel
                    
                   
                    }
        
            }
        
         }catch (\Exception $e) {
             
             
             
              try {
        
                
             $response = Http::post("http://192.168.6.8:8000/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];
                
            if($token) {
             
            try {
             
             
            $response = Http::post("http://192.168.6.8:8000/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];

            $responsefacturas = Http::withToken($token)->get("http://192.168.6.8:8000/api/dispensadoapi");

            $facturassapi = $responsefacturas->json()['data'];

            $contador = 0;
            

            foreach ($facturassapi as $factura) {
               
                 $existe = DispensadoApiMedcol5::where([['factura', $factura['factura']], ['codigo', $factura['codigo']]])->count();
                
                $dispensados = [];

                if ($existe == 0 || $existe == '') {
                   
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
                    'mipres'  => trim($factura['mipres']),
                    'precio_unitario'  => trim($factura['precio_unitario']),
                    'valor_total'  => trim($factura['valor_total']),
                    'reporte_entrega_nopbs'  => trim($factura['reporte_entrega_nopbs']),
                    'estado'  => trim($factura['estado']),
                    'centroprod'  => trim($factura['centroprod']),
                    'drogueria'  => trim($factura['drogueria']),
                    'cajero'  => trim($factura['cajero']),
                    'documento_origen'  => trim($factura['documento_origen']),
                    'factura_origen'  => trim($factura['factura_origen'])
                    
                    ];
                    
                      if (!empty($dispensados)) {
                            DispensadoApiMedcol5::insert($dispensados);
                        }

                    $contador++;
                }
            }

            Http::withToken($token)->get("http://192.168.6.8:8000/api/closeallacceso");

           
            Log::info('Cron Emcali local'.$contador . ' Lineas dispensadas'. ' Usuario: Servidor');
           
       
            }catch (\Exception $e) {
                
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
