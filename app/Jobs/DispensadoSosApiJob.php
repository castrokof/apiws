<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Medcol6\DispensadoApiMedcol6;

class DispensadoSosApiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    
    
    public $tries = 1; 
    public $timeout = 600;
    
    protected $email;
    protected $password;
    protected $usuario;
    
    

    /**
     * Create a new job instance.
     *
     * @return void
     */
     public function __construct($email, $password, $usuario)
    {
        $this->email = $email;
        $this->password = $password;
        $this->usuario = $usuario;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       
       $usuario = $this->usuario;

        try {


            $response = Http::post("http://hed08pf9dxt.sn.mynetname.net:8004/api/acceso", [
                'email' =>   $this->email,
                'password' => $this->password,
            ]);

            $token = $response->json()["token"];

            if ($token) {


                try {

                   

                    $responsefacturas = Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/dispensadoapi");

                    $facturassapi = $responsefacturas->json()['data'];
            
                    
            
                    $contadorei = 0;
                    $contador = 0;

                    foreach ($facturassapi as $factura) {

                        // Verificar si la factura ya existe en la base de datos


                        $existe = DispensadoApiMedcol6::where([['factura', $factura['factura']], ['codigo', $factura['codigo']]])->exists();

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
                                'tipoentrega'  => trim($factura['tipoentrega'])
                            ];

                            if (!empty($dispensados)) {
                                DispensadoApiMedcol6::insert($dispensados);
                            }

                            $contador++;
                        }
                    }


                    Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/closeallacceso");

                    Log::info('Desde la web syncapi centralizado' . $contador . ' Lineas dispensadas' . ' Usuario: ' . $usuario);

                    return response()->json([
                        ['respuesta' => $contador . ' Lineas creadas', 'titulo' => 'Mixed lineas', 'icon' => 'success', 'position' => 'bottom-left']
                    ]);
                } catch (\Exception $e) {

                    // Manejo de la excepción
                   Log::error('Error en la sincronización SERVER IP FIJA: '.$e->getMessage().'-'.$token); // 

                   
                }
            }
        } catch (\Exception $e) {



            try {


                $response = Http::post("http://192.168.66.91:8004/api/acceso", [
                    'email' =>  $email,
                    'password' => $password,
                ]);

                $token = $response->json()["token"];

                if ($token) {

                    try {


                     

                        $responsefacturas = Http::withToken($token)->get("http://192.168.66.91:8004/api/dispensadoapi");

                        $facturassapi = $responsefacturas->json()['data'];

                        $contador = 0;


                        foreach ($facturassapi as $factura) {

                            $existe = DispensadoApiMedcol6::where([['factura', $factura['factura']], ['codigo', $factura['codigo']]])->count();

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
                                    'tipoentrega'  => trim($factura['tipoentrega'])

                                ];

                                if (!empty($dispensados)) {
                                    DispensadoApiMedcol6::insert($dispensados);
                                }

                                $contador++;
                            }
                        }

                        Http::withToken($token)->get("http://192.168.66.91:8004/api/closeallacceso");


                        Log::info('Desde la web syncapi centralizado local' . $contador . ' Lineas dispensadas' . ' Usuario: ' . $usuario);



                        /*return response()->json([
                ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
            ]);*/
                    } catch (\Exception $e) {

                        // Manejo de la excepción
                        Log::error('Error en la sincronización LOCAL NO HAY COMUNICACIÓN CON IP FIJA: '.$e->getMessage()); // Registrar el error en los logs de Laravel

                      
                    }
                }
            } catch (\Exception $e) {


                // Manejo de la excepción
                Log::error('Error en la sincronización LOCAL NO HAY COMUNICACIÓN CON IP FIJA: '.$e->getMessage());

            }
        }
    }
}
