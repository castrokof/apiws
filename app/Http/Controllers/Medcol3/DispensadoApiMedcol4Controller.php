<?php

namespace App\Http\Controllers\Medcol3;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Medcol3\DispensadoApiMedcol4;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;

class DispensadoApiMedcol4Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

             return view('menu.Medcol3.indexDispensado');
    }
    
      public function index1(Request $request)
        {
            $fechaAi=now()->toDateString()." 00:00:01";
            $fechaAf=now()->toDateString()." 23:59:59";
            
            $droguerias = [
                '' => 'Todas',
                '2' => 'SALUD',
                '3' => 'DOLOR',
                '4' => 'PAC',
                '5' => 'EHU1',
                '6' => 'BIO1'
            ];
        
            $drogueria = $droguerias[Auth::user()->drogueria] ?? '';
        
            if ($request->ajax()) {
                
               $dispensado_api_medcol4 = DispensadoApiMedcol4::query();
               
              
                if (!empty($drogueria)) {
                    
                    $dispensado_api_medcol4->where('centroprod', $drogueria);
                }
        
               
        
                if (!empty($request->fechaini) && !empty($request->fechafin))
                
                {
                    $fechaini = new Carbon($request->fechaini);
                    $fechaini = $fechaini->toDateString();
        
                    $fechafin = new Carbon($request->fechafin);
                    $fechafin = $fechafin->toDateString();
                    
                 
                    $Resultados1 = $dispensado_api_medcol4->whereBetween('fecha_suministro',[$fechaini.' 00:00:00',$fechafin.' 23:59:59']);
                   
                    
                } elseif (empty($request->fechaini) && empty($request->fechafin)) {
                 
                    $dispensado_api_medcol4->whereBetween('fecha_suministro', [$fechaAi, $fechaAf]);
               
                }
                
                $dispensado_api_medcol4->where('estado', 'DISPENSADO')->orWhereNull('estado');
        
                $resultados = $dispensado_api_medcol4->orderBy('fecha_suministro')->get();
                
              
                return DataTables()->of($resultados)
                    ->addColumn('action', function ($pendiente) {
                        return '<button type="button" name="add_medicamento" id="' . $pendiente->id . '"
                            class="add_medicamento btn btn-app bg-secondary tooltipsC" title="Revisado">
                            <span class="badge bg-teal">Add+</span><i class="fas fa-prescription-bottle-alt"></i>
                        </button>';
                    })
                    ->addColumn('fecha_orden', function ($pendiente) {
                        return '<input type="date" name="date_orden" id="' . $pendiente->id . '"
                            class="show_detail btn btn-xl bg-secondary tooltipsC" title="Fecha">';
                    })
                    ->addColumn('numero_entrega1', function ($pendiente) {
                        return '<input type="text" name="entrega" id="' . $pendiente->id . '"
                            class="show_detail btn btn-xl bg-secondary tooltipsC" title="entrega">';
                    })
                    ->addColumn('diagnostico', function ($pendiente) {
                        return '<select name="dx" id="' . $pendiente->id . '"
                            class="diagnos form-control select2bs4" style="width: 100%;" required></select>';
                    })
                   
                    ->addColumn('autorizacion1', function ($pendiente) {
                        return '<input type="text" name="autorizacion" id="' . $pendiente->id . '"
                            class="show_detail btn btn-xl bg-warning tooltipsC"  title="autorizacion"
                            value="' . $pendiente->autorizacion . '">';
                    })
                    ->addColumn('mipres1', function ($pendiente) {
                        return '<input type="text" name="mipres" id="' . $pendiente->id . '"
                            class="show_detail btn btn-xl bg-warning tooltipsC" title="mipres">';
                    })
                    ->addColumn('reporte_entrega1', function ($pendiente) {
                        return '<input type="text" name="reporte" id="' . $pendiente->id . '"
                            class="show_detail btn btn-xl bg-info tooltipsC" title="Reporte de entrega">';
                    })
                    ->addColumn('id_medico1', function ($pendiente) {
                        return '<input type="text" name="id_medico1" id="' . $pendiente->id . '"
                            class="show_detail btn btn-xl bg-info tooltipsC" title="Id medico"
                            value="' . $pendiente->id_medico . '">';
                    })
                    ->addColumn('medico1', function ($pendiente) {
                        return '<input type="text" name="medico1" id="' . $pendiente->id . '"
                            class="show_detail btn btn-xl bg-info tooltipsC" title="Medico"
                            value="' . $pendiente->medico . '">';
                    })
                    ->addColumn('copago1', function ($pendiente) {
                        return '<input type="text" name="medico1" id="' . $pendiente->id . '"
                            class="show_detail btn btn-xl bg-info tooltipsC" title="Copago"
                            value="' . $pendiente->copago . '">';
                    })
                    ->addColumn('ips', function ($pendiente) {
                        return '<select name="ips" id="' . $pendiente->id . '"
                            class="ipsss form-control select2bs4" style="width: 100%;" required></select>';
                    })
                    ->rawColumns(['action', 'fecha_orden', 'numero_entrega1', 'diagnostico', 
                        'autorizacion1', 'mipres1', 'reporte_entrega1', 'id_medico1', 'medico1', 'copago1', 'ips'])
                    ->make(true);
            }
        
            return view('menu.Medcol3.indexDispensado', ['droguerias' => $droguerias]);
        }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createdispensadoapi(Request $request)
    {
        $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = 'colMed2023**';
        
        
        
         try {
        
                
            $response = Http::post("http://hcp080m81s7.sn.mynetname.net:8001/api/acceso", [
            'email' =>  $email,
            'password' => $password,
            ]);

            $token = $response->json()["token"];
                
            if($token) {
            

        try {

            $response = Http::post("http://hcp080m81s7.sn.mynetname.net:8001/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];


            $responsefacturas = Http::withToken($token)->get("http://hcp080m81s7.sn.mynetname.net:8001/api/dispensadoapi");

            $facturassapi = $responsefacturas->json()['data'];
    
            $contador = 0;
           

            foreach ($facturassapi as $factura) {
                
                 // Verificar si la factura ya existe en la base de datos
   
             
             $existe = DispensadoApiMedcol4::where([['factura', $factura['factura']], ['codigo', $factura['codigo']]])->exists();
    
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
                    'cajero'  => trim($factura['cajero'])
                    ];
                    
                              if (!empty($dispensados)) {
                            DispensadoApiMedcol4::insert($dispensados);
                        }

                    $contador++;
                        }
                        
                        
                       
                       
                    } 
              
               
            
            /*if (!empty($dispensados)) {
              DispensadoApiMedcol4::insert($dispensados);
            }*/

            Http::withToken($token)->get("http://hcp080m81s7.sn.mynetname.net:8001/api/closeallacceso");

            
            return response()->json([
                ['respuesta' => $contador . ' Lineas creadas', 'titulo' => 'Mixed lineas', 'icon' => 'success', 'position' => 'bottom-left']
            ]);

                    } catch (\Exception $e) {
                        
                         // Manejo de la excepción
                    \Log::error($e->getMessage()); // Registrar el error en los logs de Laravel
                    
                    return response()->json([
                        ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
                    ]);
            
                    }
        
            }
        
         }catch (\Exception $e) {
             
             
             
              try {
        
                
             $response = Http::post("http://192.168.10.27:8001/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];
                
            if($token) {
             
            try {
             
             
            $response = Http::post("http://192.168.10.27:8001/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];

            $responsefacturas = Http::withToken($token)->get("http://192.168.10.27:8001/api/dispensadoapi");

            $facturassapi = $responsefacturas->json()['data'];

            $contador = 0;
            

            foreach ($facturassapi as $factura) {
               
                 $existe = DispensadoApiMedcol4::where([['factura', $factura['factura']], ['codigo', $factura['codigo']]])->count();
                
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
                    'cajero'  => trim($factura['cajero'])
                    ];
                    
                      if (!empty($dispensados)) {
                            DispensadoApiMedcol4::insert($dispensados);
                        }

                    $contador++;
                }
            }

          

            Http::withToken($token)->get("http://192.168.10.27/api/closeallacceso");

           
           
            return response()->json([
                ['respuesta' => $contador . ' Lineas creadas', 'titulo' => 'Mixed lineas', 'icon' => 'success', 'position' => 'bottom-left']
            ]);

           
            /*return response()->json([
                ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
            ]);*/
            }catch (\Exception $e) {
                
                
                         // Manejo de la excepción
                 \Log::error($e->getMessage()); // Registrar el error en los logs de Laravel
             
                
                return response()->json([
                ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
            ]);
            }
            
            }
            
              }catch (\Exception $e) {
                
                
                         // Manejo de la excepción
                 \Log::error($e->getMessage()); // Registrar el error en los logs de Laravel
             
                
                return response()->json([
                ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
            ]);
            }

    }

}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function disrevisado(Request $request)
    {
        
        
        $i = Auth::user()->drogueria;
        
        switch ($i) {
                    case "1":
                        $drogueria = '';
                        break;
                    case "2":
                        $drogueria = 'SALUD';
                        break;
                    case "3":
                       $drogueria = 'DOLOR';
                        break;
                    case "4":
                        $drogueria = 'PAC';
                        break;
                    case "5":
                        $drogueria = 'EHU1';
                        break;
                    case "6":
                         $drogueria = 'BIO1';
                        break;    
                }
        
        
        
        //dd($drogueria);

        if ($request->ajax()) {
            
            if(Auth::user()->drogueria == '1'){
            
            $dispensado_api_medcol4 = DispensadoApiMedcol4::where([['estado', 'REVISADO']])
                 ->orWhere('estado', NULL)
                 ->orderBy('id')
                ->get();

            
        }else{
                    $dispensado_api_medcol4 = DispensadoApiMedcol4::where([['estado', 'REVISADO'],['centroprod',$drogueria]])
                 ->orWhere('estado', NULL)
                 ->orderBy('id')
                 ->get();

           
        }
         return DataTables()->of($dispensado_api_medcol4)
                ->addColumn('action', function ($pendiente) {
                    $button = '<button type="button" name="show_detail" id="' . $pendiente->id . '
                    " class="show_detail btn btn-app bg-secondary tooltipsC" title="Detalle"  >
                    <span class="badge bg-teal">Detalle</span><i class="fas fa-prescription-bottle-alt"></i> </button>';
                    $button2 = '<button type="button" name="edit_pendiente" id="' . $pendiente->id . '
                    " class="edit_pendiente btn btn-app bg-info tooltipsC" title="Editar"  >
                    <span class="badge bg-teal">Editar</span><i class="fas fa-pencil-alt"></i> </button>';

                    return $button . ' ' . $button2;
                })
                ->addColumn('fecha Orden', function ($pendiente) {
                    $inputdate = '<input type="date" name="date_orden" id="' . $pendiente->id . '
                    " class="show_detail btn btn-app bg-secondary tooltipsC" title="Fecha">';
                    
                    return $inputdate ;
                })
                ->rawColumns(['action','fecha Orden'])
                ->make(true);
            
        }

        return view('menu.Medcol3.indexAnalista');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adddispensacionarray(Request $request)
    {
         $add_factura = $request->data;
        
        
       foreach ($add_factura as $add) {
               
                DispensadoApiMedcol4::where('id',$add['id'])
                ->update([
                    'autorizacion'  => trim($add['autorizacion1']),
                    'copago'  => trim($add['copago1']),
                    'numero_entrega'  => trim($add['numero_entrega1']),
                    'fecha_ordenamiento'  => trim($add['fecha_orden']),
                    'dx'  => trim($add['diagnostico']),
                    'ips'  => trim($add['ips']),
                    'id_medico'  => trim($add['id_medico1']),
                    'medico'  => trim($add['medico1']),
                    'mipres'  => trim($add['mipres1']),
                    'reporte_entrega_nopbs'  => trim($add['reporte_entrega1']),
                    'estado'  => trim($add['estado']),
                    'user_id'  => trim($add['user_id']),
                    'updated_at'=>now()]
                  
                    );
       
       
                }
        
       
                return response()->json(['success' => 'ok']);
    }
    
    
    
   /* public function filfech(Request $request)
        {
            
                $fechaAi=now()->toDateString()." 00:00:01";
                $fechaAf=now()->toDateString()." 23:59:59";
    
            if ($request->ajax()) {
                
                $dispensadoapi = DispensadoApiMedcol4::query();
                
              if($request->fechaini != '' && $request->fechafin != '' ){  
                
                $fechaini = new Carbon($request->fechaini);
                $fechaini = $fechaini->toDateString();
    
                $fechafin = new Carbon($request->fechafin);
                $fechafin = $fechafin->toDateString();
                
                $dispensadoapi->whereBetween('fecha_suministro', [$fechaini.' 00:00:00',$fechafin.' 23:59:59']);
                
              }
              
              
              /*if($request->historia != ''){  
                  
                $historia = preg_replace("/\s+/", "", trim($request->historia));
                
                $historia = explode(',',$historia);
                
                $pc = count($historia);
                
                for ($i=0; $i < $pc; $i++) { 
    
                     $dispensadoapi->WhereIn('historia', $historia);
                    
                }
                
              }*/
              
              
             /* if($request->fechaini == '' && $request->fechafin == ''){  
                
                  $dispensadoapi->whereBetween('fecha_suministro', [$fechaAi,$fechaAf]);
                  $dispensadoapi->where([
                    ['fecha_suministro', '>=', '2024-02-01'.' 00:00:00']]
                );
                
              }
              
              
                
               $dispensadoapi->get();
               
               
               return DataTables()->of($dispensadoapi)
                ->make(true);
              }
                  
              
            }*/

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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
