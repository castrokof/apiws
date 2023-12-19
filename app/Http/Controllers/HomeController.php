<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
//Funcion para consulta de lo direccionado por la EPS a medcol     
    public function index(Request $request)
    {   
        $fechaAi=now()->toDateString();
        $fechaini=$request->fechaini;
        $fechafin=$request->fechafin;
        $prescripcion = $request->prescripcion;
        

        

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

            return view('home', compact('statusF'));
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


        return view('home', compact('medicamentos2','statusF'));

        }else if(!empty($fechaini) && empty($prescripcion)){
            
            for ($i=0; $i < $pcfi; $i++) { 

                $medicamentosF = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/DireccionamientoXFecha/$NIT/$token/$fechai[$i]");
            
                $medicamentos2[]= $medicamentosF->json();
                
                $statusF= $medicamentosF->status();
                
                }
            
        return view('home', compact('medicamentos2','statusF'));

        }else if(empty($fechaini) && !empty($prescripcion)){
            
             $prescripcion = preg_replace("/\s+/", "", trim($request->prescripcion));
            
            $prescripciona = explode(',',$prescripcion);
            
            $pc = count($prescripciona);
            
            for ($i=0; $i < $pc; $i++) { 

                $medicamentosF = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/DireccionamientoXPrescripcion/$NIT/$token/$prescripciona[$i]");
            
                $medicamentos2[]= $medicamentosF->json();
                
                $statusF= $medicamentosF->status();
                
            }
            
              
            
        return view('home', compact('medicamentos2','statusF'));

        }
        
        
           }else{
                    
                    return redirect('tokenhercules')->with('mensaje', 'Debes ingresar primero el token solicitado');
                   
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
    

//Funcion para consultar lo programado
    public function indexp(Request $request)
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

            return view('programado', compact('statusF'));
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

        $medicamentosF = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ProgramacionXFecha/$NIT/$token/$fecha[$i]");
        
    
        $medicamentos2[]= $medicamentosF->json();
        
        $statusF= $medicamentosF->status();
        
        }


        return view('programado', compact('medicamentos2','statusF'));

        }else if(!empty($fechaini) && empty($prescripcion)){
            
            for ($i=0; $i < $pcfi; $i++) { 

                $medicamentosF = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ProgramacionXFecha/$NIT/$token/$fechai[$i]");
            
                $medicamentos2[]= $medicamentosF->json();
                
                $statusF= $medicamentosF->status();
                
                }
            
        return view('programado', compact('medicamentos2','statusF'));

        }else if(empty($fechaini) && !empty($prescripcion)){
            
            $prescripcion = preg_replace("/\s+/", "", trim($request->prescripcion));
            
            $prescripciona = explode(',',$prescripcion);
            
            $pc = count($prescripciona);
            
            for ($i=0; $i < $pc; $i++) { 

           

                $medicamentosF = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ProgramacionXPrescripcion/$NIT/$token/$prescripciona[$i]");
            
            
                $medicamentos2[]= $medicamentosF->json();
                
                $statusF= $medicamentosF->status();
            } 
              
            
        return view('programado', compact('medicamentos2','statusF'));

        }


        }else{
            
             return redirect('tokenhercules')->with('mensaje', 'Debes ingresar primero el token solicitado');
        }

    }
   
//Funcion para programar
    Public function Programarm(Request $request){
        
        $TokenHercules = session('tokenh');
        $NIT='901601000';
        $response = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/GenerarToken/$NIT/$TokenHercules");
       
        $token=$response->json();


        
        $pmipres = $request->data;
        
        
        foreach($pmipres as $mipre){
    
           $responsepost = Http::withHeaders(['Content-Type' => 'application/json'])
            ->put("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/Programacion/$NIT/$token",[
              
                'ID' => (int)$mipre['ID'],
                'FecMaxEnt' => $mipre['FecMaxEnt'],
                'TipoIDSedeProv' => $mipre['TipoIDSedeProv'],
                'NoIDSedeProv' => $mipre['NoIDSedeProv'],
                'CodSedeProv' => $mipre['CodSedeProv'],
                'CodSerTecAEntregar' => $mipre['CodSerTecAEntregar'],
                'CantTotAEntregar' => $mipre['CantTotAEntregar']
              
                
            ]);

            $status = $responsepost->status();
            $data1[]=$responsepost->body();
        
           

        }
        
       
                return response()->json(['result'=>$data1, 'success' => 'ok']);
          
    }      
        
    
//Funcion para direccionamiento de vista token hercules
    Public function tokenherculesindex(){
    
  
        return view('tokenhercules');
  
        
    }


//Funcion para cargar el token hercules en variable de sesion
    Public function tokenhercules(Request $request){
        
            session(['tokenh' => $request->tokenhercules]);
            
            return redirect('tokenhercules')->with('mensaje', 'Token almacenado correctamente!!
            dirigete a direccionamiento en la barra superior');
            
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
    $response = Http::get("https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/GenerarToken/$NIT/$TokenHercules");

    $token=$response->json();



    $pmipres = $request->data;


    foreach($pmipres as $mipre){

    $responsepost = Http::withHeaders(['Content-Type' => 'application/json'])
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


        foreach($pmipres as $mipre){

            $idpro = $mipre['IDFacturacion'];

            $responsepost = Http::put("https://wsmipres.sispro.gov.co/WSFACMIPRESNOPBS/api/FacturacionAnular/$NIT/$token/$idpro");

            $status = $responsepost->status();
            $data1=$responsepost->body();

            if($status != 200){
            return response()->json(['result'=>$data1, 'success' => 'ya']);

        }else if($status == 200){
                return response()->json(['result'=>$data1, 'success' => 'ok']);
            }

        }

    }
} 
        
 

