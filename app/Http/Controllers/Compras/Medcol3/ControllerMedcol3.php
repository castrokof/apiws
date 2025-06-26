<?php
namespace App\Http\Controllers\Compras\Medcol3;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use App\Models\Listas\ListasDetalle;
use App\Models\compras\medcol3\Medcolterceros3;
use App\Models\compras\medcol3\Medcolmedicamentos3;
use App\Models\compras\medcol3\Medcolcompras3;
use App\Models\compras\Documentos;
use App\Imports\OrdenesImport;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\compras\medcol3\OrdenCompraMedcol3;
use App\Models\Medcol6\OrdenCompraMedcol6;
use SebastianBergmann\Environment\Console;

class ControllerMedcol3 extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
      public function index(Request $request)
{
    $ordenes = OrdenCompraMedcol6::paginate(200);

    if ($request->ajax()) {
        return response()->json([
            'tbody' => view('menu.Compras.Medcol3.tablas.tablaIndexOrdenes', compact('ordenes'))->render(),
            'pagination' => view('menu.Compras.Medcol3.tablas.paginacion', compact('ordenes'))->render(),
        ]);
    }

    return view('menu.Compras.Medcol3.index', compact('ordenes'));
}
    
    
    
    public function articulos(Request $request)
    {
        $array=[];


        if($request->has('q'))
        {
            $term = $request->get('q');

            $result = Medcolmedicamentos3::orderBy('id')
            ->where('estado', '1')
            ->where(function ($query) use ($term) {
                $query->where('nombre', 'LIKE', '%' . $term . '%')
                      ->orWhere('codigo', 'LIKE', '%' . $term . '%');
            })
            ->get();

            array_push($array, $result);

            return response()->json(['array'=>$array]);
        } else {

                array_push($array, Medcolmedicamentos3::orderBy('id')
                ->get());

                return response()->json(['array'=>$array]);

        }
        
    }

    public function articulosadd(Request $request)
    {
        $articulos = Medcolmedicamentos3::all();
         return response()->json($articulos);
        
    }

    public function obtenerArticulo($codigo)
{
    $articulo = Medcolmedicamentos3::where('codigo', $codigo)->first();
    if ($articulo) {
        return response()->json([
            'marca' => $articulo->marca,
            'nombre' => $articulo->nombre,
            'cums' => $articulo->cums,
            'forma' => $articulo->forma
        ]);
    } else {
        return response()->json(['message' => 'Artículo no encontrado'], 404);
    }
}
    
    public function proveedores(Request $request)
    {
        $array=[];

        if($request->has('q'))
        {
            $term = $request->get('q');


            $result = Medcolterceros3::orderBy('id')
            ->where('estado', '1')
            ->where(function ($query) use ($term) {
             $query->where('nombre_sucursal', 'LIKE', '%' . $term . '%')
              ->orWhere('codigo_tercero', 'LIKE', '%' . $term . '%');
             })
            ->get();

            array_push($array, $result);

            return response()->json(['array'=>$array]);
        }  else {

                array_push($array, Medcolterceros3::orderBy('id')
                ->get());

                return response()->json(['array'=>$array]);

        }
        
    }
    
    
    public function Ordcompras(Request $request)
{
    if ($request->ajax()) {
        $ordenes_compra_medcol3 = Medcolcompras3::with('proveedor')
            ->select('documentoOrden', 'numeroOrden', 'usuario_id', 'proveedor_id', 'created_at')
            ->distinct('numeroOrden')
            ->orderByDesc('numeroOrden');

        return DataTables()->of($ordenes_compra_medcol3)
            ->addColumn('proveedor_nombre', function ($orden) {
                return $orden->proveedor ? $orden->proveedor->nombre_sucursal : 'N/A';
            })
            ->addColumn('created_at', function ($orden) {
                return $orden->created_at ? $orden->created_at->format('Y-m-d') : 'N/A';
            })
            ->addColumn('action', function ($pendiente) {
                $button = '<button type="button" name="show_detail" id="' . $pendiente->id . '"
                class="show_detail btn btn-app bg-secondary tooltipsC" title="Detalle">
                <span class="badge bg-teal">Detalle</span><i class="fas fa-prescription-bottle-alt"></i> </button>';
                $button2 = '<button type="button" name="edit_orden" id="' . $pendiente->id . '"
                class="edit_orden btn btn-app bg-info tooltipsC" title="Editar">
                <span class="badge bg-teal">Editar</span><i class="fas fa-pencil-alt"></i> </button>';

                return $button . ' ' . $button2;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    return view('menu.Compras.Medcol3.index');
}


    
    public function showproveedor($id)
    {
        if (request()->ajax()) {
            $pendiente = Medcolterceros3::where('id', '=', $id)->first();
            
                return response()->json(['showproveedor' => $pendiente]);
        }
        return view('menu.Compras.Medcol3.index');
    }
    
    public function showarticulos($id)
    {
        if (request()->ajax()) {
            $pendiente = Medcolmedicamentos3::where('id', '=', $id)->first();
            
                return response()->json(['detalle' => $pendiente]);
        }
        return view('menu.Compras.Medcol3.index');
    }
   
   
// Funcions de los documentos

 public function documentos(Request $request)
    {


        $array=[];


        if($request->has('q'))
        {
            $term = $request->get('q');

            array_push($array, Documentos::orderBy('documento')->where([['documento', 'LIKE', '%' . $term . '%']])
            ->get());

            return response()->json(['array'=>$array]);

        }
        else {



                array_push($array, Documentos::orderBy('documento')
                ->where('documento','OCDL')
                ->get());


                return response()->json(['array'=>$array]);



        }

    }
    
    
     public function consecutivo($id)
    {
        if(request()->ajax()){
            $documento = Documentos::where('documento', '=', $id)->first();
            return response()->json(['documento'=>$documento]);
        }
    }
    
    
    // Función para guardar la entrada
    public function guardarDetalles(Request $request)
    {
        $datosEntrada = $request->input('data');

        try {
            DB::beginTransaction();

            // **1. Procesar y guardar la información principal en OrdenCompraMedcol6**
            $entradaOrdenData = $datosEntrada['entradaOrden'][0] ?? null; // Asumiendo que 'entradaOrden' tiene un solo objeto
            $detallesEntrada = $datosEntrada['entradadetalle'] ?? [];
            $DocumentoOrden = $detallesEntrada[0]['documentoOrden'] ?? null;
            if ($entradaOrdenData) {
                // Validar los datos para OrdenCompraMedcol6 (ajusta las reglas según tus necesidades)
                $rulesOrdenCompra = [
                    'orden_de_compra' => 'required|string|max:255',
                    'nit' => 'required|string|max:20',
                    'proveedor' => 'required|string|max:255',
                    'telefono' => 'nullable|string|max:20',
                    'fecha' => 'nullable|date',
                    'cod_farmacia' => 'required|string|max:255',
                    'num_orden_compra' => 'required|string|max:255|unique:ordenes_compra_medcol6,num_orden_compra',
                    'programa' => 'nullable|string|max:255',
                    'contacto' => 'nullable|string|max:255',
                    'direccion' => 'nullable|string|max:255',
                    'email' => 'nullable|email|max:255',
                    'codigo_proveedor' => 'required|string|max:255',
                    'user_create' => 'nullable|integer', // Asegúrate del tipo de dato correcto
                    'estado' => 'nullable|string|max:20',
                    'total' => 'nullable|numeric',
                    'sub-_total' => 'nullable|numeric',
                    'iva' => 'nullable|numeric',
                    'observaciones' => 'nullable|string',
                    'numeroOrden' => 'nullable|string|max:255', // Asegúrate de que este campo exista en el modelo
                ];

                $Proveedor = Medcolterceros3::where('codigo_tercero', 'LIKE', $entradaOrdenData['nit'])->first();
                $entradaOrdenData['telefono'] = $Proveedor->telefono;
                $entradaOrdenData['email'] = $Proveedor->e_mail;
                $entradaOrdenData['direccion'] = $Proveedor->direccion;
                $NumOrden = $entradaOrdenData['num_orden_compra']. $DocumentoOrden;
                $concatenadoNumeroOrden = Carbon::now()->year . $entradaOrdenData['orden_de_compra'] . "-" .$DocumentoOrden  . $entradaOrdenData['num_orden_compra'];
                $entradaOrdenData['num_orden_compra'] = $NumOrden;
                $entradaOrdenData['orden_de_compra'] = $concatenadoNumeroOrden;
                $entradaOrdenData['totalParcial'] = 0;
                $validatorOrdenCompra = Validator::make($entradaOrdenData, $rulesOrdenCompra);
                if ($validatorOrdenCompra->fails()) {
                    return response()->json(['errors' => $validatorOrdenCompra->errors()->all()], 422);
                }
                 OrdenCompraMedcol6::create($entradaOrdenData);
            }

            // **2. Procesar y guardar los detalles en Medcolcompras3**
            
            $rulesDetalle = [
                'documentoOrden' => 'required|string|max:255',
                'numeroOrden' => 'required|string|max:255',
                'proveedor_id' => 'required|integer',
                'contrato' => 'nullable|string|max:255',
                'usuario_id' => 'required|integer',
                'created_at' => 'nullable|date',
                'codigo' => 'required|string|max:255',
                'nombre' => 'required|string|max:255',
                'cums' => 'required|string|max:255',
                'marca' => 'required|string|max:255',
                'cantidad' => 'required|integer',
                'precio' => 'required|numeric',
                'subtotal' => 'required|numeric',
                'iva' => 'nullable|numeric',
                // ... otras reglas de validación para los detalles
            ];

            foreach ($detallesEntrada as $detalle) {
                $detalle['estado'] = 'Pendiente';
                $detalle['cantidadEntregada']  = 0; 
                $detalle['totalParcial'] = 0;
                 // Asegúrate de que este campo exista en el modelo
                $validatorDetalle = Validator::make($detalle, $rulesDetalle);
                if ($validatorDetalle->fails()) {
                    return response()->json(['errors' => $validatorDetalle->errors()->all()], 422);
                }

                // **Importante:** Asocia el 'numeroOrden' de OrdenCompraMedcol6 con los detalles de Medcolcompras3
                $detalle['numeroOrden'] = $entradaOrdenData['num_orden_compra'] ?? null; // Usar 'num_orden_compra' de la tabla 6
                Medcolcompras3::create($detalle);
            }

            // **3. Incrementar el consecutivo (si es necesario)**
            $documento = Documentos::where('documento', '=', $DocumentoOrden)->first();
            if ($documento) {
                $consecutivoActual = $documento->consecutivo;
                $documento->consecutivo += 1;
                $documento->save();
            } else {
                $consecutivoActual = null; // O manejar el caso en que el documento no existe
            }

            DB::commit();

            return response()->json([
                'success' => 'ok',
                'documento' => $documento->documento ?? null,
                'numeroOrden' => $entradaOrdenData['num_orden_compra'] ?? $consecutivoActual, // Usar el número de orden de la tabla 6
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => [$e->getMessage()]], 500);
        }
    }

    
// Funciones para el API de Terceros

     public function createterceroapi(Request $request)
    {
         $email = 'castrokofdev@gmail.com'; // Auth::user()->email
         $password = 'colMed2023**';
         $usuario = Auth::user()->email;
        
        
         try {
        
                
            $response = Http::post("http://hed08pf9dxt.sn.mynetname.net:8004/api/acceso", [
                'email' =>  $email,
                'password' => $password,
                ]);
    
                $token = $response->json()["token"];
            
          
                
            if($token) {
            

        try {

         

            $responseTerceros = Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/terceros");
            
            
    
            $tercerosApi = $responseTerceros->json()['data'];

            $contador = 0;
            
            
            
          
           

            foreach ($tercerosApi as $tercero) {
             
             $existe = Medcolterceros3::where('codigo_tercero', $tercero['codigo_tercero'])->count();
             
             
    
                $terceros3 = [];
                
                if ($existe == 0 || $existe == '') {
                    
                    $terceros3[] = [
                    'codigo_tercero'  => trim($tercero['codigo_tercero']),
                    'nombre_sucursal'  => trim($tercero['nombre_sucursal']),
                    'direccion'  => trim($tercero['direccion']),
                    'telefono'  => trim($tercero['telefono']),
                    'e_mail'  => trim($tercero['e_mail']),
                    'estado'  => '1',
                    'created_at'  => now()
                    
                    ];
                    
                     if (!empty($terceros3)) {
                      Medcolterceros3::insert($terceros3);
                    }

                    $contador++;
                    
                   
                }
            }

           

            Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/closeallacceso");

            Log::info('Desde la web syncapi terceros '.$contador . ' Lineas de terceros'. ' Usuario: '.$usuario);
            
            
            return response()->json([
                ['respuesta' => $contador . ' Lineas creadas', 'titulo' => 'Mixed lineas', 'icon' => 'success', 'position' => 'bottom-left']
            ]);

                    } catch (\Exception $e) {
                        
                         // Manejo de la excepción
                    Log::error($e->getMessage()); // Registrar el error en los logs de Laravel
                    
                    return response()->json([
                        ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
                    ]);
            
                    }
        
            }
        
         }catch (\Exception $e) {
             
             
             
              try {
        
                
             $response = Http::post("http://192.168.50.98:8000/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];
                
            if($token) {
             
            try {
             
             
           

            $responsefacturas = Http::withToken($token)->get("http://192.168.50.98:8000/api/dispensadoapi");

            $facturassapi = $responsefacturas->json()['data'];

            $contador = 0;
            

          
            foreach ($tercerosApi as $tercero) {
             
             $existe = Medcolterceros3::where(['codigo_tercero', $tercero['codigo_tercero']])->count();
    
                $terceros3 = [];
                
                if ($existe == 0 || $existe == '') {
                    
                    $terceros3[] = [
                    'codigo_tercero'  => trim($tercero['codigo_tercero']),
                    'nombre_sucursal'  => trim($tercero['nombre_sucursal']),
                    'direccion'  => trim($tercero['direccion']),
                    'telefono'  => trim($tercero['telefono']),
                    'e_mail'  => trim($tercero['e_mail']),
                    'estado'  => '1',
                    'created_at'  => now()
                    
                    ];
                    
                     if (!empty($terceros3)) {
                      Medcolterceros3::insert($terceros3);
                    }

                    $contador++;
                    
                   
                }
            }
          

            Http::withToken($token)->get("http://192.168.50.98/api/closeallacceso");

           
            Log::info('Desde la web syncapi Dolor local'.$contador . ' Lineas dispensadas'. ' Usuario: '.$usuario);           
            
             return response()->json([
                ['respuesta' => $contador . ' Lineas creadas', 'titulo' => 'Mixed lineas', 'icon' => 'success', 'position' => 'bottom-left']
            ]);


            }catch (\Exception $e) {
                
                
                         // Manejo de la excepción
                 Log::error($e->getMessage()); // Registrar el error en los logs de Laravel
             
                
                return response()->json([
                ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
            ]);
            }
            
            }
            
              }catch (\Exception $e) {
                
                
                         // Manejo de la excepción
                 Log::error($e->getMessage()); // Registrar el error en los logs de Laravel
             
                
                return response()->json([
                ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
            ]);
            }

    }

}

// Funciones para el API de Medicamentos

       public function createmedicamentosapi(Request $request)
        {
             $email = 'castrokofdev@gmail.com'; // Auth::user()->email
             $password = 'colMed2023**';
             $usuario = Auth::user()->email;
            
            
             try {
            
                    
                $response = Http::post("http://hed08pf9dxt.sn.mynetname.net:8004/api/acceso", [
                'email' =>  $email,
                'password' => $password,
                ]);
    
               $jsonResponse = $response->json();

                if (!$jsonResponse || !isset($jsonResponse["token"])) {
                    throw new \Exception("No se pudo obtener el token. Respuesta: " . $response->body());
                }
                
                $token = $jsonResponse["token"];
                
                
                    
                if($token) {
                
               
    
            try {
    
                $responseMedicamentos = Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/medicamentos");
                
               
        
                $medicamentosApi = $responseMedicamentos->json()['data'];
                
                
                
                $contador = 0;
               
              
    
                foreach ($medicamentosApi as $medicamento) {
                 
                 $existe = Medcolmedicamentos3::where('codigo', $medicamento['codigo'])->count();
                 
                 
        
                    $medicamentos3 = [];
                    
                    if ($existe == 0 || $existe == '') {
                        
                         $medicamentos3[] = [
                        'tipo_MI'  => trim($medicamento['tipo_MI']),
                        'codigo'  => trim($medicamento['codigo']),
                        'nombre'  => trim($medicamento['nombre']),
                        'marca'  => trim($medicamento['marca']),
                        'atc'  => trim($medicamento['atc']),
                        'forma'  => trim($medicamento['forma']),
                        'concentracion'  => trim($medicamento['concentracion']),
                        'cums'  => trim($medicamento['cums']),
                        'estado'  => '1',
                        'created_at'  => now()
                        
                        
                        ];
                        
                         if (!empty($medicamentos3)) {
                          Medcolmedicamentos3::insert($medicamentos3);
                        }
    
                        $contador++;
                        
                       
                    }
                }
    
             
                Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/closeallacceso");
    
                Log::info('Desde la web syncapi Dolor Medicamentos '.$contador . ' Lineas de medicamentos'. ' Usuario: '.$usuario);
                
                
                return response()->json([
                    ['respuesta' => $contador . ' Lineas creadas', 'titulo' => 'Mixed lineas', 'icon' => 'success', 'position' => 'bottom-left']
                ]);
    
                        } catch (\Exception $e) {
                            
                             // Manejo de la excepción
                        Log::error($e->getMessage()); // Registrar el error en los logs de Laravel
                        
                        return response()->json([
                            ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
                        ]);
                
                        }
            
                }
            
             }catch (\Exception $e) {
                 
                 
                 
                  try {
            
                    
                 $response = Http::post("http://192.168.50.98:8000/api/acceso", [
                    'email' =>  $email,
                    'password' => $password,
                ]);
    
                $token = $response->json()["token"];
                    
                if($token) {
                 
                try {
                 
                 
               
    
                $responseMedicamentos = Http::withToken($token)->get("http://192.168.50.98:8000/api/medicamentos");
    
                $medicamentosApi = $responseMedicamentos->json()['data'];
    
                $contador = 0;
                
    
              
                 foreach ($medicamentosApi as $medicamento) {
                 
                 $existe = Medcolmedicamentos3::where(['codigo', $medicamento['codigo']])->count();
        
                    $medicamentos3 = [];
                    
                    if ($existe == 0 || $existe == '') {
                        
                         $medicamentos3[] = [
                        'tipo_MI'  => trim($medicamento['tipo_MI']),
                        'codigo'  => trim($medicamento['codigo']),
                        'nombre'  => trim($medicamento['nombre']),
                        'marca'  => trim($medicamento['marca']),
                        'atc'  => trim($medicamento['atc']),
                        'forma'  => trim($medicamento['forma']),
                        'concentracion'  => trim($medicamento['concentracion']),
                        'cums'  => trim($medicamento['cums']),
                        'estado'  => '1',
                        'created_at'  => now()
                        
                        
                        ];
                        
                         if (!empty($medicamentos3)) {
                          Medcolmedicamentos3::insert($medicamentos3);
                        }
    
                        $contador++;
                        
                       
                    }
                }
              
    
                Http::withToken($token)->get("http://192.168.50.98/api/closeallacceso");
    
               
                Log::info('Desde la web syncapi Dolor local'.$contador . ' Lineas medicamentos'. ' Usuario: '.$usuario);           
                
                 return response()->json([
                    ['respuesta' => $contador . ' Lineas creadas', 'titulo' => 'Mixed lineas', 'icon' => 'success', 'position' => 'bottom-left']
                ]);
    
    
                }catch (\Exception $e) {
                    
                    
                             // Manejo de la excepción
                     Log::error($e->getMessage()); // Registrar el error en los logs de Laravel
                 
                    
                    return response()->json([
                    ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
                ]);
                }
                
                }
                
                  }catch (\Exception $e) {
                    
                    
                             // Manejo de la excepción
                     Log::error($e->getMessage()); // Registrar el error en los logs de Laravel
                 
                    
                    return response()->json([
                    ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
                ]);
                }
    
        }
    
    }
    
    
    
  public function importOrders(Request $request)
{
    $user_id = Auth::user()->id;

    if ($request->ajax()) {
        // Asegurarse de que el archivo ha sido subido
        $file = $request->file('file');

        if ($file == null) {
            return response()->json(['mensaje' => 'vacio']);
        }

        // Guardar el archivo en el sistema de almacenamiento
        $name = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('orders', $name, 'local');

        try {
            // Importar los datos desde el archivo Excel
            $import = new OrdenesImport($request->proveedor, $user_id);
            Excel::import($import, storage_path('app/' . $path));

            // Obtener los datos procesados desde la clase OrdenesImport
            $data = $import->getData();

            if ($data->isEmpty()) {
                return response()->json(['mensaje' => 'ng']);
            } else {
                return response()->json(['mensaje' => 'ok', 'data' => $data]);
            }
        } catch (\Exception $e) {
            // Registrar el error para depuración
            Log::error('Error during import: ' . $e->getMessage());
            return response()->json(['mensaje' => 'error', 'error' => $e->getMessage()]);
        }
    }
}


    
}
