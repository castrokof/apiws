<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;

use App\Models\Bd_direccionados;
use App\Models\Bd_reporteentregado;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
     
     
    public $token;
    public $NIT;
     
    public function __construct()
    {
        $this->middleware('auth');
        $this->token = session('token');
        $this->NIT = '901601000';
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
//funciono para redirigiri al HomeController

    public function indexs(){
        
        return view('home');
        
        
    } 
 
//Funcion para consulta de lo direccionado por la EPS a el proveedor     
    public function index(Request $request)
    {   
        $fechaAi=now()->toDateString();
        $fechaini=$request->fechaini;
        $fechafin=$request->fechafin;
        $prescripcion = $request->prescripcion;
        
        
        $token = session('token');
        $cargaInicial = $request->cargaInicial;
        $TokenHercules = session('tokenh');
        
        $url = "";
       
    if (empty($token)) {
            return response()->json(['error' => 'Token de sesión no válido.'], 401);
        }
 
      try {
        if (empty($fechaini) && empty($prescripcion) && $cargaInicial) {
            // Consulta por fecha de hoy
            return $this->consultarPorFechas([$fechaAi], $token);
            
        } elseif (!empty($fechaini) && empty($prescripcion)) {
            // Consulta por rango de fechas
            $fechas = $this->generarRangoFechas($fechaini, $fechafin);
            return $this->consultarPorFechas($fechas, $token);
            
        } elseif (!empty($prescripcion)) {
            // Consulta por prescripción
            $prescripciones = explode(',', preg_replace("/\s+/", "", trim($prescripcion)));
            return $this->consultarPorPrescripcion($prescripciones, $token);
            
        } else {
            return response()->json(['error' => 'Parámetros inválidos o incompletos.'], 400);
        }
    } catch (ConnectionException $e) {
        
        return response()->json(['error' => 'No se pudo conectar con la API del Ministerio MIPRE 2.0, Intenta nuevamente o más tarde.']);
        
    } catch (\Exception $e) {
        
        return response()->json(['error' => 'Error inesperado: ' . $e->getMessage()], 500);
        
    }
 
      
                


    }
    private function generarRangoFechas($inicio, $fin)
                {
                    $fechas = [];
                    $start = Carbon::parse($inicio);
                    $end = Carbon::parse($fin);
                
                    while ($start->lte($end)) {
                        $fechas[] = $start->toDateString();
                        $start->addDay();
                    }
                
                    return $fechas;
                }
                
    private function consultarPorFechas(array $fechas, $token)
                {
                    $medicamentos2 = [];
                
                    foreach ($fechas as $fecha) {
                        $url = "https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/DireccionamientoXFecha/{$this->NIT}/$token/$fecha";
                        
                        $response = Http::retry(2, 3000)->timeout(5)->get($url);
                        $response->throw();
                
                        $medicamentos = $response->json();
                        $this->guardarDatos($medicamentos);
                        $medicamentos2[] = $medicamentos;
                    }
                
                    return response()->json(['success' => true, 'data' => $medicamentos2, 'message' => 'Datos por fecha consultados correctamente']);
                }
                
    private function consultarPorPrescripcion(array $prescripciones, $token)
                {
                    $medicamentos2 = [];
                
               
                    foreach ($prescripciones as $pres) {
                        $url = "https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/DireccionamientoXPrescripcion/{$this->NIT}/$token/$pres";
                        $response = Http::retry(2, 3000)->timeout(2)->get($url);
                        $response->throw();
                
                        $medicamentos = $response->json();
                        $this->guardarDatos($medicamentos);
                        $medicamentos2[] = $medicamentos;
                    }
                
                    return response()->json(['success' => true, 'data' => $medicamentos2, 'message' => 'Datos consultados correctamente']);
                }
                
    private function guardarDatos(array $medicamentos)
                {
                    foreach ($medicamentos as $data) {
                        Bd_direccionados::firstOrCreate(
                            ['Idt' => $data['ID']],
                            [
                                'IDDireccionamiento' => $data['IDDireccionamiento'],
                                'NoPrescripcion' => $data['NoPrescripcion'],
                                'TipoIDPaciente' => $data['TipoIDPaciente'],
                                'NoIDPaciente' => $data['NoIDPaciente'],
                                'CantTotAEntregar' => $data['CantTotAEntregar'],
                                'NoEntrega' => $data['NoEntrega'],
                                'TipoIDProv' => $data['TipoIDProv'],
                                'NoIDProv' => $data['NoIDProv'],
                                'CodSerTecAEntregar' => $data['CodSerTecAEntregar'],
                                'FecMaxEnt' => $data['FecMaxEnt'],
                                'FecDireccionamiento' => $data['FecDireccionamiento'],
                                'NoIDEPS' => $data['NoIDEPS'],
                                'CodEPS' => $data['CodEPS'],
                                'CodSedeProv' => 'PROV007788',
                                'IdProgramacion' => null,
                                'fechapro' => null,
                                'fechaanuladopro' => null,
                                'IdEntregado' => null,
                                'fechaentregado' => null,
                                'fechaanuladoentregado' => null,
                                'IdReporteEntrega' => null,
                                'fechareporteentregado' => null,
                                'fechaanuladoreporteentregado' => null,
                                'IdFacturado' => null,
                                'fechafacturado' => null,
                                'fechaanuladofacturado' => null,
                                'estado' => 1
                            ]
                        );
                    }
                }
    
//Funcion para consulta de lo direccionado por la EPS a Medcol por documento  
    public function direccionado(Request $request)
    {   
        $fechaAi=now()->toDateString();
        $fechaini=$request->fechaini;
        $fechafin=$request->fechafin;
        $prescripcion = $request->prescripcion;
        $tipo_documento = $request->tipo_documento;
        $documento = $request->documento;
        

        

        $TokenHercules = session('tokenh');




        
        if( $TokenHercules != '' ||  $TokenHercules != null){
        
        $NIT='901601000';
        //$response = Http::withOptions([
          //  'debug' => true,
        //])->get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/GenerarToken/$NIT/$TokenHercules");
     
        $response = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/GenerarToken/$NIT/$TokenHercules");
        
        
     
        $token=$response->json();
        $statusF=$response->status();

        //dd($token);
        if($statusF != 200){

            return view('direccionado', compact('statusF'));
        }


// Array fechas
        $fecha = [$fechaAi];
        $fechai = [];
        $fechainicio = Carbon::parse($fechaini);
        $fechafinal = Carbon::parse($fechafin);
        $dias = $fechafinal->diffInDays($fechainicio);
        
         
        if($dias == 0){

            $fechai[] = $fechaini; 
        }else{
            $dias = $dias + 1;

          for ($i=0; $i < $dias; $i++) { 
            
            
            $fechai[] = $fechaini;
           
            $fechaini++;
        
          }
         
        }
   
//Variable count de las fechas
    $pcf=count($fecha);
    $pcfi=count($fechai);



    if(empty($fechaini) && empty($prescripcion)){
    for ($i=0; $i < $pcf; $i++) { 

        $medicamentosF = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/DireccionamientoXFecha/$NIT/$token/$fecha[$i]");
    
        $medicamentos2[]= $medicamentosF->json();
        
        $statusF= $medicamentosF->status();
        
        }


        return view('direccionado', compact('medicamentos2','statusF'));

        }else if(!empty($fechaini) && !empty($documento) && !empty($tipo_documento)){
            
            for ($i=0; $i < $pcfi; $i++) { 

                $medicamentosF = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/DireccionamientoXPacienteFecha/$NIT/$fechai[$i]/$token/$tipo_documento/$documento");
                
                if($medicamentosF->status() == 200){ 
                    $medicamentos2[]= $medicamentosF->json();
                }
               
                $statusF= $medicamentosF->status();
                
            }
            
         
        return view('direccionado', compact('medicamentos2','statusF'));

        }

        
           }else{
                    
                   return redirect('tokenhercules')->with('mensaje', 'Debes ingresar primero el token solicitado');
                }
                

    }    


//funciono para redirigiri al HomeController

    public function indexpIndex(){
        
        return view('programado');
        
        
    } 

//Funcion para consultar lo programado
    public function indexp(Request $request)
    {   
        $fechaAi=now()->toDateString();
        $fechaini=$request->fechaini;
        $fechafin=$request->fechafin;
        $prescripcion = $request->prescripcion;
        $NIT='901601000';
        $token = session('token');
        $cargaInicial = $request->cargaInicial;
       
       
        if (empty($token)) {
                    return response()->json(['error' => 'Token de sesión no válido.'], 401);
                }
        
        try {
        if (empty($fechaini) && empty($prescripcion) && $cargaInicial) {
            // Consulta por fecha de hoy
            return $this->consultarPorFechasP([$fechaAi], $token);
           
            
        } elseif (!empty($fechaini) && empty($prescripcion)) {
            // Consulta por rango de fechas
            $fechas = $this->generarRangoFechasP($fechaini, $fechafin);
            return $this->consultarPorFechasP($fechas, $token);
            
            
        } elseif (!empty($prescripcion)) {
            // Consulta por prescripción
            $prescripciones = explode(',', preg_replace("/\s+/", "", trim($prescripcion)));
            return $this->consultarPorPrescripcionP($prescripciones, $token);
            
            
        } else {
            return response()->json(['error' => 'Parámetros inválidos o incompletos.'], 400);
            
        }
    } catch (ConnectionException $e) {
        
        return response()->json(['error' => 'No se pudo conectar con la API del Ministerio MIPRE 2.0, Intenta nuevamente o más tarde.']);
      
        
    } catch (\Exception $e) {
        
        return response()->json(['error' => 'Error inesperado: ' . $e->getMessage()], 500);
        
        
    }
        
        
        
    }
    
    private function generarRangoFechasP($inicio, $fin)
                {
                    $fechas = [];
                    $start = Carbon::parse($inicio);
                    $end = Carbon::parse($fin);
                
                    while ($start->lte($end)) {
                        $fechas[] = $start->toDateString();
                        $start->addDay();
                    }
                
                    return $fechas;
                }
                
    private function consultarPorFechasP(array $fechas, $token)
                {
                    $medicamentos2 = [];
                
                    foreach ($fechas as $fecha) {
                        $url = "https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ProgramacionXFecha/{$this->NIT}/$token/$fecha";
                        
                        $response = Http::retry(2, 3000)->timeout(5)->get($url);
                        $response->throw();
                
                        $medicamentos = $response->json();
                        $medicamentos2[] = $medicamentos;
                    }
                
                    return response()->json(['success' => true, 'data' => $medicamentos2, 'message' => 'Datos por fecha consultados correctamente']);
                    
                }
                
    private function consultarPorPrescripcionP(array $prescripciones, $token)
                {
                    $medicamentos2 = [];
                
               
                    foreach ($prescripciones as $pres) {
                        $url = "https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ProgramacionXPrescripcion/{$this->NIT}/$token/$pres";
                        $response = Http::retry(2, 3000)->timeout(2)->get($url);
                        $response->throw();
                
                        $medicamentos = $response->json();
                        $medicamentos2[] = $medicamentos;
                    }
                
                    return response()->json(['success' => true, 'data' => $medicamentos2, 'message' => 'Datos consultados correctamente']);
                }
    
    
    
  
    
//Funcion para consulta de lo direccionado por la EPS a Medcol por documento  
   
//Funcion para programar
   public function Programarm(Request $request){

    $TokenHercules = session('tokenh');
    $NIT = '901601000';
    $token = session('token');
   

    $pmipres = $request->data;
    $data1 = [];

   
    try {
        foreach ($pmipres as $mipre) {
            
            
            
            
            
            $responsepost = Http::withHeaders(['Content-Type' => 'application/json'])
                //->retry(2, 3)
                ->timeout(10)
                ->put("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/Programacion/$NIT/$token", [
                    'ID' => (int)$mipre['ID'],
                    'FecMaxEnt' => $mipre['FecMaxEnt'],
                    'TipoIDSedeProv' => $mipre['TipoIDSedeProv'],
                    'NoIDSedeProv' => $mipre['NoIDSedeProv'],
                    'CodSedeProv' => $mipre['CodSedeProv'],
                    'CodSerTecAEntregar' => $mipre['CodSerTecAEntregar'],
                    'CantTotAEntregar' => $mipre['CantTotAEntregar'],
                ]);
                
            $data1[] = $responsepost->body();
            
             
        }
        
         $status = $responsepost->status();
         
         if($status =='422'){
            return response()->json(['result' => $data1, 'success' => 'ya']);
         }else if($status =='200'){
            return response()->json(['result' => $data1, 'success' => 'ok']);}
    } catch (ConnectionException $e) {
        return response()->json(['result' => 'Error al conectar con la API del Ministerio MIPRES 2.0 después de varios intentos. Por favor, intenta nuevamente.', 'error' => 'ok2']);
    } catch (\Illuminate\Http\Client\RequestException $e) {
        // Capturamos errores específicos de la API (status codes 4xx o 5xx)
        return response()->json(['result' => 'Error en la API de MIPRES al programar: ' . $e->getMessage() . ' - Status Code: ' . $e->response->status(), 'success' => 'ya'], $e->response->status());
    } catch (\Exception $e) {
        // Capturamos cualquier otra excepción inesperada
        return response()->json(['result' => 'Ocurrió un error inesperado durante la programación: ' . $e->getMessage()], 500);
    }
}


      

        
          
            
        
    
//Funcion para direccionamiento de vista token hercules
    Public function tokenherculesindex(){
    
  
        return view('tokenhercules');
  
        
    }


//Funcion para cargar el token hercules en variable de sesion
    Public function tokenhercules(Request $request){
      
            session(['tokenh' => $request->tokenhercules]);
            
            session(['tokenp' => $request->tokenherculesp]);
            
            
            
    $TokenHercules = session('tokenh');
    $NIT='901601000';
            
     $url = "https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/GenerarToken/$NIT/$TokenHercules";
    $maxRetries = 2;
    $retryDelaySeconds = 3; // Puedes ajustar el tiempo entre reintentos
    $attempt = 0;

    while ($attempt < $maxRetries) {
        
                $attempt++;
                try {
                    $response = Http::timeout(5)->get($url); // Establecemos un timeout de 15 segundos
                    $response->throw(); // Lanza una excepción si el código de estado HTTP indica un error (4xx o 5xx)
                    $token = $response->json();
                    session(['token' => $token]);
        
                   // Si la petición AJAX es exitosa, devolvemos una respuesta JSON con el mensaje
                    return response()->json(['mensaje' => 'Token almacenado correctamente!!','token' => $token]);
        
        
                } catch (ConnectionException $e) {
                    // Si es el último intento, mostramos el error
                    if ($attempt === $maxRetries) {
                        // Si falla después de varios intentos, devolvemos un error JSON
                        return response()->json(['error' => 'Error al conectar con la API del Ministerio MIPRES 2.0 después de varios intentos. Por favor, intenta nuevamente.']); // Código de estado 500 para error del servidor
                
                    }
        
                    // Esperamos antes de reintentar
                    sleep($retryDelaySeconds);
                } catch (\Exception $e) {
                    // Capturamos otras excepciones que puedan ocurrir (ej. errores de la API)
                    return response()->json(['error' => 'Ocurrió un error al obtener el token: ' . $e->getMessage()], 500);
               
                }
            }
        
            return response()->json(['error' => 'Error al conectar con la API después de varios intentos. Por favor, intenta nuevamente.'], 500);
            
    }
      
//Funcion para anular programacion
    Public function Anularprogramacion(Request $request){
        
            $TokenHercules = session('tokenh');
            $NIT='901601000';
            $response = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/GenerarToken/$NIT/$TokenHercules");
           
            $token=$response->json();
    
           
            
            $pmipres = $request->data;
            
            
            foreach($pmipres as $mipre){
                
                $idpro = $mipre['IDProgramacion'];

               $responsepost = Http::put("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/AnularProgramacion/$NIT/$token/$idpro");
    
                $status = $responsepost->status();
                $data1=$responsepost->body();
            
                if($status != 200){
                return response()->json(['result'=>$data1, 'success' => 'ya']);
                
            }else if($status == 200){
                    return response()->json(['result'=>$data1, 'success' => 'ok']);
            }
    
        }        
    } 
    
    
//Funcion para Reportar dispensadión
    Public function  Reportardispensacion(Request $request)
    {


    $TokenHercules = session('tokenh');
    $NIT='901601000';
    $token = session('token');



    $pmipres = $request->data;


    foreach($pmipres as $mipre){

    $responsepost = Http::withHeaders(['Content-Type' => 'application/json'])
        ->timeout(20)
        ->put("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/Entrega/$NIT/$token",[

            'ID' => (int)$mipre['ID'],
            'CodSerTecEntregado' => $mipre['CodSerTecEntregado'],
            'CantTotEntregada' => $mipre['CantTotEntregada'],
            'EntTotal' => (int)$mipre['EntTotal'],
            'CausaNoEntrega' => $mipre['CausaNoEntrega'],
            'FecEntrega' => $mipre['FecEntrega'],
            'NoLote' => $mipre['NoLote'],
            'TipoIDRecibe' => $mipre['TipoIDRecibe'],
            'NoIDRecibe' => $mipre['NoIDRecibe']
        ]);

        $status = $responsepost->status();
        $data1=$responsepost->body();

        if($status == 422){
        return response()->json(['result'=>$data1, 'success' => 'ya']);

        }else if($status == 200){
            return response()->json(['result'=>$data1, 'success' => 'ok']);
        
            
        }else if($status == 500){
            return response()->json(['result'=>$data1, 'success' => 'er']);
        }

    }
    }
    
    
//Funcion para anular entrega
    Public function Anularentrega(Request $request)
    {

       $TokenHercules = session('tokenh');
       $NIT='901601000';
       $response = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/GenerarToken/$NIT/$TokenHercules");

       $token=$response->json();

       $data1 = [];  

       $pmipres = $request->data;


       foreach($pmipres as $mipre){

           $idpro = $mipre['IDEntrega'];

          $responsepost = Http::put("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/AnularEntrega/$NIT/$token/$idpro");

           $status = $responsepost->status();
           $data1[]=$responsepost->body();

           
       }
       if($status != 200){
           return response()->json(['result'=>$data1, 'success' => 'ya']);

            }else if($status == 200){
               return response()->json(['result'=>$data1, 'success' => 'ok']);
           }

   }


//Funcion para consulta de lo reportado como entregado
    public function indexrepe(Request $request)
        {
            $fechaAi=now()->toDateString();
            $fechaini=$request->fechaini;
            $fechafin=$request->fechafin;
            $prescripcion = $request->prescripcion;




            $TokenHercules = session('tokenh');
            
 if( $TokenHercules != '' ||  $TokenHercules != null){

            $NIT='901601000';
            $response = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/GenerarToken/$NIT/$TokenHercules");

            $token=$response->json();
            $statusF=$response->status();


            if($statusF != 200){

                return view('repentregado', compact('statusF'));
            }


    // Array fechas
            $fecha = [$fechaAi];
            $fechai = [];
            $fechainicio = Carbon::parse($fechaini);
            $fechafinal = Carbon::parse($fechafin);
            $dias = $fechafinal->diffInDays($fechainicio);


            if($dias == 0){

                $fechai[] = $fechaini;
            }else{
                $dias = $dias + 1;

              for ($i=0; $i < $dias; $i++) {


                $fechai[] = $fechaini;

                $fechaini++;

              }

            }

    //Variable count de las fechas
        $pcf=count($fecha);
        $pcfi=count($fechai);



        if(empty($fechaini) && empty($prescripcion)){
        for ($i=0; $i < $pcf; $i++) {

            $medicamentosF = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ReporteEntregaXFecha/$NIT/$token/$fecha[$i]");


            $medicamentos2[]= $medicamentosF->json();

            $statusF= $medicamentosF->status();

            }


            return view('repentregado', compact('medicamentos2','statusF'));

            }else if(!empty($fechaini) && empty($prescripcion)){

                for ($i=0; $i < $pcfi; $i++) {

                    $medicamentosF = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ReporteEntregaXFecha/$NIT/$token/$fechai[$i]");

                    $medicamentos2[]= $medicamentosF->json();

                    $statusF= $medicamentosF->status();

                    }

            return view('repentregado', compact('medicamentos2','statusF'));

            }else if(empty($fechaini) && !empty($prescripcion)){

                $prescripcion = preg_replace("/\s+/", "", trim($request->prescripcion));

                $prescripciona = explode(',',$prescripcion);

                $pc = count($prescripciona);

                for ($i=0; $i < $pc; $i++) {



                    $medicamentosF = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ReporteEntregaXPrescripcion/$NIT/$token/$prescripciona[$i]");


                    $medicamentos2[]= $medicamentosF->json();

                    $statusF= $medicamentosF->status();
                }
                
                

            return view('repentregado', compact('medicamentos2','statusF'));

            }

             }else{
            
             return redirect('tokenhercules')->with('mensaje', 'Debes ingresar primero el token solicitado');
        }

    }
//Funcion para consulta de lo entregado por el dispensador
    public function indexe(Request $request)
   {
        $fechaAi=now()->toDateString();
        $fechaini=$request->fechaini;
        $fechafin=$request->fechafin;
        $prescripcion = $request->prescripcion;




        $TokenHercules = session('tokenh');


 if( $TokenHercules != '' ||  $TokenHercules != null){

        $NIT='901601000';
        $response = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/GenerarToken/$NIT/$TokenHercules");

        $token=$response->json();
        $statusF=$response->status();


        if($statusF != 200){

            return view('entregado', compact('statusF'));
        }


    // Array fechas
        $fecha = [$fechaAi];
        $fechai = [];
        $fechainicio = Carbon::parse($fechaini);
        $fechafinal = Carbon::parse($fechafin);
        $dias = $fechafinal->diffInDays($fechainicio);


        if($dias == 0){

            $fechai[] = $fechaini;
        }else{
            $dias = $dias + 1;

        for ($i=0; $i < $dias; $i++) {


            $fechai[] = $fechaini;

            $fechaini++;

        }

        }

        //Variable count de las fechas
        $pcf=count($fecha);
        $pcfi=count($fechai);



        if(empty($fechaini) && empty($prescripcion)){
        for ($i=0; $i < $pcf; $i++) {

        $medicamentosF = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/EntregaXFecha/$NIT/$token/$fecha[$i]");


        $medicamentos2[]= $medicamentosF->json();

        $statusF= $medicamentosF->status();

        }


        return view('entregado', compact('medicamentos2','statusF'));

        }else if(!empty($fechaini) && empty($prescripcion)){

            for ($i=0; $i < $pcfi; $i++) {

                $medicamentosF = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/EntregaXPrescripcion/$NIT/$token/$fechai[$i]");

                $medicamentos2[]= $medicamentosF->json();

                $statusF= $medicamentosF->status();

                }

        return view('entregado', compact('medicamentos2','statusF'));

        }else if(empty($fechaini) && !empty($prescripcion)){

            $prescripcion = preg_replace("/\s+/", "", trim($request->prescripcion));

            $prescripciona = explode(',',$prescripcion);

            $pc = count($prescripciona);

            for ($i=0; $i < $pc; $i++) {



                $medicamentosF = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/EntregaXPrescripcion/$NIT/$token/$prescripciona[$i]");


                $medicamentos2[]= $medicamentosF->json();

                $statusF= $medicamentosF->status();
            }

        return view('entregado', compact('medicamentos2','statusF'));

        }
        
        
           }else{
                            
                            return redirect('tokenhercules')->with('mensaje', 'Debes ingresar primero el token solicitado');
                        }
                


 }

//Funcion para Reportar entrega
    Public function  Reportarentrega(Request $request)
    {


    $TokenHercules = session('tokenh');
    $NIT='901601000';
    $response = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/GenerarToken/$NIT/$TokenHercules");

    $token=$response->json();



    $pmipres = $request->data;


    foreach($pmipres as $mipre){

       $responsepost = Http::withHeaders(['Content-Type' => 'application/json'])
        ->put("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ReporteEntrega/$NIT/$token",[

            'ID' => (int)$mipre['ID'],
            'EstadoEntrega' => $mipre['EstadoEntrega'],
            'CausaNoEntrega' => $mipre['CausaNoEntrega'],
            'ValorEntregado' => $mipre['ValorEntregado'],
        ]);
        
       
        
        $status = $responsepost->status();
        $data1=$responsepost->body();

        if($status == 422){
        return response()->json(['result'=>$data1, 'success' => 'ya']);

    }else if($status == 200){
            return response()->json(['result'=>$data1, 'success' => 'ok']);
        }

    }
    }
    
//Funcion para anular reporte de entrega
    Public function Anularrentrega(Request $request)
    {

    $TokenHercules = session('tokenh');
    $NIT='901601000';
    $response = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/GenerarToken/$NIT/$TokenHercules");

    $token=$response->json();


    $pmipres = $request->data;


    foreach($pmipres as $mipre){

        $idpro = $mipre['IDReporteEntrega'];

        $responsepost = Http::put("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/AnularReporteEntrega/$NIT/$token/$idpro");

        $status = $responsepost->status();
        $data1=$responsepost->body();

        if($status != 200){
        return response()->json(['result'=>$data1, 'success' => 'ya']);

    }else if($status == 200){
            return response()->json(['result'=>$data1, 'success' => 'ok']);
        }

    }
    }    
    
//Funcion para Reportar la facturacion
    Public function  Reportarfactura(Request $request)
    {
    
    
    $TokenHercules = session('tokenh');
    $NIT='901601000';
    $response = Http::get("https://wsmipres.sispro.gov.co/WSFACMIPRESNOPBS/api/GenerarToken/$NIT/$TokenHercules");
    
    $token=$response->json();
    
    
    
    $pmipres = $request->data;
    
    
    foreach($pmipres as $mipre){
    
       $responsepost = Http::withHeaders(['Content-Type' => 'application/json'])
        ->put("https://wsmipres.sispro.gov.co/WSFACMIPRESNOPBS/api/Facturacion//$NIT/$token",[
    
            "NoPrescripcion" => $mipre['NoPrescripcion'],
            "TipoTec" => $mipre['TipoTec'],
            "ConTec" => (int)$mipre['ConTec'],
            "TipoIDPaciente" => $mipre['TipoIDPaciente'],
            "NoIDPaciente" => $mipre['NoIDPaciente'],
            "NoEntrega" => (int)$mipre['NoEntrega'],
            "NoSubEntrega" => $mipre['NoSubEntrega'],
            "NoFactura" => $mipre['NoFactura'],
            "NoIDEPS" => $mipre['NoIDEPS'],
            "CodEPS" => $mipre['CodEPS'],
            "CodSerTecAEntregado" => $mipre['CodSerTecAEntregado'],
            "CantUnMinDis" => $mipre['CantUnMinDis'],
            "ValorUnitFacturado" => $mipre['ValorUnitFacturado'],
            "ValorTotFacturado" => $mipre['ValorTotFacturado'],
            "CuotaModer" => $mipre['CuotaModer'],
            "Copago" => $mipre['Copago']
    
        ]);
    
        $status = $responsepost->status();
        $data1=$responsepost->body();
    
        if($status == 422){
        return response()->json(['result'=>$data1, 'success' => 'ya']);
    
    }else if($status == 200){
            return response()->json(['result'=>$data1, 'success' => 'ok']);
        }
    
    }
    } 
    
//Funcion para consulta de lo reportado como facturado
    public function indexf(Request $request)
        {
            $fechaAi=now()->toDateString();
            $fechaini=$request->fechaini;
            $fechafin=$request->fechafin;
            $prescripcion = $request->prescripcion;




            $TokenHercules = session('tokenh');

 if( $TokenHercules != '' ||  $TokenHercules != null){

            $NIT='901601000';
            $response = Http::get("https://wsmipres.sispro.gov.co/WSFACMIPRESNOPBS/api/GenerarToken/$NIT/$TokenHercules");

            $token=$response->json();
            $statusF=$response->status();


            if($statusF != 200){

                return view('repfacturacion', compact('statusF'));
            }


    // Array fechas
            $fecha = [$fechaAi];
            $fechai = [];
            $fechainicio = Carbon::parse($fechaini);
            $fechafinal = Carbon::parse($fechafin);
            $dias = $fechafinal->diffInDays($fechainicio);


            if($dias == 0){

                $fechai[] = $fechaini;
            }else{
                $dias = $dias + 1;

              for ($i=0; $i < $dias; $i++) {


                $fechai[] = $fechaini;

                $fechaini++;

              }

            }

    //Variable count de las fechas
        $pcf=count($fecha);
        $pcfi=count($fechai);



        if(empty($fechaini) && empty($prescripcion)){
        for ($i=0; $i < $pcf; $i++) {

            $medicamentosF = Http::get("https://wsmipres.sispro.gov.co/WSFACMIPRESNOPBS/api/FacturacionXFecha/$NIT/$token/$fecha[$i]");


            $medicamentos2[]= $medicamentosF->json();

            $statusF= $medicamentosF->status();

            }


            return view('repfacturacion', compact('medicamentos2','statusF'));

            }else if(!empty($fechaini) && empty($prescripcion)){

                for ($i=0; $i < $pcfi; $i++) {

                    $medicamentosF = Http::get("https://wsmipres.sispro.gov.co/WSFACMIPRESNOPBS/api/FacturacionXFecha/$NIT/$token/$fechai[$i]");

                    $medicamentos2[]= $medicamentosF->json();

                    $statusF= $medicamentosF->status();

                    }

            return view('repfacturacion', compact('medicamentos2','statusF'));

            }else if(empty($fechaini) && !empty($prescripcion)){

                $prescripcion = preg_replace("/\s+/", "", trim($request->prescripcion));

                $prescripciona = explode(',',$prescripcion);

                $pc = count($prescripciona);

                for ($i=0; $i < $pc; $i++) {



                    $medicamentosF = Http::get("https://wsmipres.sispro.gov.co/WSFACMIPRESNOPBS/api/FacturacionXPrescripcion/$NIT/$token/$prescripciona[$i]");


                    $medicamentos2[]= $medicamentosF->json();

                    $statusF= $medicamentosF->status();
                }



            return view('repfacturacion', compact('medicamentos2','statusF'));

            }
            
            }else{
                            
                            return redirect('tokenhercules')->with('mensaje', 'Debes ingresar primero el token solicitado');
                     }
                

    }

//Funcion para anular reporte de factura
    Public function Anularfactura(Request $request)
    {

        $TokenHercules = session('tokenh');
        $NIT='901601000';
        $response = Http::get("https://wsmipres.sispro.gov.co/WSFACMIPRESNOPBS/api/GenerarToken/$NIT/$TokenHercules");

        $token=$response->json();



        $pmipres = $request->data;
        
         $row = 0;
         $data1 = [];
         $status = [];


        foreach($pmipres as $mipre){

            $idpro = $mipre['IDFacturacion'];

            $responsepost = Http::put("https://wsmipres.sispro.gov.co/WSFACMIPRESNOPBS/api/FacturacionAnular/$NIT/$token/$idpro");

            $status[] = $responsepost->status();
            $data1[]=$responsepost->body();

             $row++;

        }
        
        /*if($status != 200){
            return response()->json(['result'=>$data1, 'success' => 'ya']);

        }else if($status == 200){*/
                return response()->json(['result'=>$data1, 'result1' => $status, 'registros' => $row]);
          //  }

    }
} 
        
        





