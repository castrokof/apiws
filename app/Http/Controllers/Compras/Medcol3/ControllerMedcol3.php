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
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;

class ControllerMedcol3 extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //
        return view('menu.Compras.Medcol3.index');
    }


    public function articulos(Request $request)
    {
        $array = [];


        if ($request->has('q')) {
            $term = $request->get('q');


            $result = Medcolmedicamentos3::orderBy('id')
                ->where('estado', '1')
                ->where(function ($query) use ($term) {
                    $query->where('nombre', 'LIKE', '%' . $term . '%')
                        ->orWhere('codigo', 'LIKE', '%' . $term . '%');
                })
                ->get();

            array_push($array, $result);

            return response()->json(['array' => $array]);
        } else {



            array_push($array, Medcolmedicamentos3::orderBy('id')
                ->get());


            return response()->json(['array' => $array]);
        }
    }

    public function proveedores(Request $request)
    {
        $array = [];


        if ($request->has('q')) {
            $term = $request->get('q');


            $result = Medcolterceros3::orderBy('id')
                ->where('estado', '1')
                ->where(function ($query) use ($term) {
                    $query->where('nombre_sucursal', 'LIKE', '%' . $term . '%')
                        ->orWhere('codigo_tercero', 'LIKE', '%' . $term . '%');
                })
                ->get();

            array_push($array, $result);

            return response()->json(['array' => $array]);
        } else {



            array_push($array, Medcolterceros3::orderBy('id')
                ->get());


            return response()->json(['array' => $array]);
        }
    }


    public function Ordcompras(Request $request)
    {
        //
        if ($request->ajax()) {
            $ordenes_compra_medcol3 = Medcolcompras3::with('proveedor')
                ->orderBy('created_at')
                ->get();

            return DataTables()->of($ordenes_compra_medcol3)
                ->addColumn('proveedor_nombre', function ($orden) {
                    return $orden->proveedor ? $orden->proveedor->nombre_sucursal : 'N/A';
                })
                ->addColumn('action', function ($pendiente) {
                    $button = '<button type="button" name="show_detail" id="' . $pendiente->id . '
                    " class="show_detail btn btn-app bg-secondary tooltipsC" title="Detalle"  >
                    <span class="badge bg-teal">Detalle</span><i class="fas fa-prescription-bottle-alt"></i> </button>';
                    $button2 = '<button type="button" name="edit_orden" id="' . $pendiente->id . '
                    " class="edit_orden btn btn-app bg-info tooltipsC" title="Editar"  >
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


        $array = [];


        if ($request->has('q')) {
            $term = $request->get('q');


            array_push($array, Documentos::orderBy('documento')->where([['documento', 'LIKE', '%' . $term . '%'], ['documento', 'OCDL']])
                ->get());

            return response()->json(['array' => $array]);
        } else {

            array_push($array, Documentos::orderBy('documento')
                ->where('documento', 'OCDL')
                ->get());

            return response()->json(['array' => $array]);
        }
    }


    public function consecutivo($id)
    {
        if (request()->ajax()) {
            $documento = Documentos::where('documento', '=', $id)->first();
            return response()->json(['documento' => $documento]);
        }
    }


    // Función para guardar la entrada
    public function guardarDetalles(Request $request)
    {
        $datosEntrada = $request->input('data');

        // Definir reglas de validación
        $rules = [
            'documentoOrden' => 'required',
            'numeroOrden' => 'required',
            'proveedor_id' => 'required',
            'contrato' => 'required',
            'usuario_id' => 'required',
            'observaciones' => 'required',
            'created_at' => 'date',

            'codigo' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'cums' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'cantidad' => 'required|integer',
            'precio' => 'required|numeric',
            'subtotal' => 'required|numeric'
        ];

        // Validar cada entrada individualmente
        foreach ($datosEntrada as $entrada) {
            $validator = Validator::make($entrada, $rules);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }
        }

        try {
            // Iniciar transacción
            DB::beginTransaction();

            // Verificar si el numeroOrden ya existe
            foreach ($datosEntrada as $entrada) {
                $exists = Medcolcompras3::where('numeroOrden', $entrada['numeroOrden'])->exists();
                if ($exists) {
                    return response()->json(['errors' => ['El número de orden ' . $entrada['numeroOrden'] . ' ya existe.']]);
                }
            }

            // Guardar cada entrada en la base de datos
            foreach ($datosEntrada as $entrada) {
                Medcolcompras3::create($entrada);
            }

            // Incrementar el campo 'consecutivo' en la tabla 'documentos'
            $documento = Documentos::where('documento', '=', 'OCDL')->first(); // Asumiendo que solo hay una fila en la tabla 'documentos'
            $consecutivoActual = $documento->consecutivo; // Variable para guardar el valor actual del consecutivo

            $documento->consecutivo += 1;
            $documento->save();

            // Confirmar transacción
            DB::commit();

            // Retorna documento y número de orden
            return response()->json(['success' => 'ok', 'documento' => $documento->documento, 'numeroOrden' => $consecutivoActual]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->getMessage()]);
        }
    }



    // Funciones para el API de Terceros

    public function createterceroapi(Request $request)
    {
        $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = '123456';
        $usuario = Auth::user()->email;


        try {


            $response = Http::post("http://190.85.46.246:8000/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];



            if ($token) {


                try {



                    $responseTerceros = Http::withToken($token)->get("http://190.85.46.246:8000/api/terceros");



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

                    /*if (!empty($dispensados)) {
              DispensadoApiMedcold::insert($dispensados);
            }*/

                    Http::withToken($token)->get("http://190.85.46.246:8000/api/closeallacceso");

                    Log::info('Desde la web syncapi Dolor terceros ' . $contador . ' Lineas de terceros' . ' Usuario: ' . $usuario);


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
        } catch (\Exception $e) {



            try {


                $response = Http::post("http://192.168.50.98:8000/api/acceso", [
                    'email' =>  $email,
                    'password' => $password,
                ]);

                $token = $response->json()["token"];

                if ($token) {

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


                        Log::info('Desde la web syncapi Dolor local' . $contador . ' Lineas dispensadas' . ' Usuario: ' . $usuario);

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
            } catch (\Exception $e) {


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
        $password = '123456';
        $usuario = Auth::user()->email;


        try {


            $response = Http::post("http://190.85.46.246:8000/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];



            if ($token) {


                try {



                    $responseMedicamentos = Http::withToken($token)->get("http://190.85.46.246:8000/api/medicamentos");



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


                    Http::withToken($token)->get("http://190.85.46.246:8000/api/closeallacceso");

                    Log::info('Desde la web syncapi Dolor terceros ' . $contador . ' Lineas de medicamentos' . ' Usuario: ' . $usuario);


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
        } catch (\Exception $e) {



            try {


                $response = Http::post("http://192.168.50.98:8000/api/acceso", [
                    'email' =>  $email,
                    'password' => $password,
                ]);

                $token = $response->json()["token"];

                if ($token) {

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


                        Log::info('Desde la web syncapi Dolor local' . $contador . ' Lineas medicamentos' . ' Usuario: ' . $usuario);

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
            } catch (\Exception $e) {


                // Manejo de la excepción
                Log::error($e->getMessage()); // Registrar el error en los logs de Laravel


                return response()->json([
                    ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
                ]);
            }
        }
    }
}
