<?php

namespace App\Http\Controllers\Medcol6;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\DispensadoSosApiJob;

use App\Models\Listas\ListasDetalle;
use App\Models\Medcol6\DispensadoApiMedcol6;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;


class DispensadoApiMedcol6Controller extends Controller
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

    public function informes(Request $request)
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

            case "8":
                $drogueria = 'EM01';
                break;
            case "9":
                $drogueria = 'FSIO';
                break;
            case "10":
                $drogueria = 'FSOS';
                break;
            case "11":
                $drogueria = 'FSAU';
                break;
            case "12":
                $drogueria = 'EVSO';
                break;
            case "13":
                $drogueria = 'FRJA';
                break;
        }

        if (Auth::user()->drogueria == '1') {

            // Agrupar y contar por estado y centroprod
            $dispensado = DispensadoApiMedcol6::select('centroprod', DB::raw('count(*) as total'))
                ->where('estado', 'DISPENSADO')
                ->where('fecha_suministro', '>=', '2024-09-01 00:00:00')
                ->whereNotIn('codigo', ['1010', '1011', '1012'])
                ->groupBy('centroprod')
                ->get();

            $revisado = DispensadoApiMedcol6::select('centroprod', DB::raw('count(*) as total'))
                ->where('estado', 'REVISADO')
                ->where('fecha_suministro', '>=', '2024-09-01 00:00:00')
                ->whereNotIn('codigo', ['1010', '1011', '1012'])
                ->groupBy('centroprod')
                ->get();

            $anulado = DispensadoApiMedcol6::select('centroprod', DB::raw('count(*) as total'))
                ->where('estado', 'ANULADA')
                ->where('fecha_suministro', '>=', '2024-09-01 00:00:00')
                ->whereNotIn('codigo', ['1010', '1011', '1012'])
                ->groupBy('centroprod')
                ->get();
        } else {

            $dispensado = DispensadoApiMedcol6::select('centroprod', DB::raw('count(*) as total'))
                ->where('estado', 'DISPENSADO')
                ->where('centroprod', $drogueria)
                ->where('fecha_suministro', '>=', '2024-09-01 00:00:00')
                ->whereNotIn('codigo', ['1010', '1011', '1012'])
                ->groupBy('centroprod')
                ->get();

            $revisado = DispensadoApiMedcol6::select('centroprod', DB::raw('count(*) as total'))
                ->where('estado', 'REVISADO')
                ->where('centroprod', $drogueria)
                ->where('fecha_suministro', '>=', '2024-09-01 00:00:00')
                ->whereNotIn('codigo', ['1010', '1011', '1012'])
                ->groupBy('centroprod')
                ->get();

            $anulado = DispensadoApiMedcol6::select('centroprod', DB::raw('count(*) as total'))
                ->where('estado', 'ANULADA')
                ->where('centroprod', $drogueria)
                ->where('fecha_suministro', '>=', '2024-09-01 00:00:00')
                ->whereNotIn('codigo', ['1010', '1011', '1012'])
                ->groupBy('centroprod')
                ->get();
        }



        return response()->json(['dispensado' => $dispensado, 'revisado' => $revisado, 'anulado' => $anulado]);
    }

    public function index(Request $request)
    {

        return view('menu.Medcol6.indexDispensado');
    }


    /**
     * Método index1 para obtener y filtrar datos de dispensación de medicamentos.
     * 
     * 🔹 **Mejoras y lógica aplicada:**
     * - **Fechas (`fechaini`, `fechafin`) son obligatorias.** Si no se envían, se usa el día actual.
     * - **`contrato` y `cobertura` son opcionales**, y pueden combinarse de cualquier manera:
     *   - Se puede filtrar por (`fechaini`, `fechafin`) únicamente.
     *   - Se puede agregar `contrato` con (`fechaini`, `fechafin`).
     *   - Se puede agregar `cobertura` con (`fechaini`, `fechafin`).
     *   - Se pueden incluir `contrato` y `cobertura` juntos.
     * - **Optimización del código**:
     *   - Uso de `Carbon::parse()->startOfDay()` y `endOfDay()` para gestionar rangos de fechas.
     *   - Se mejora la lógica de filtros para evitar verificaciones innecesarias.
     *   - Se aplica la condición `where` de manera más estructurada.
     * - **Mejora en la consulta:** Se filtran datos por `estado` y códigos específicos de manera más eficiente.
     * 
     * @param  Request $request  Datos enviados desde la vista.
     * @return \Illuminate\Http\JsonResponse|Illuminate\View\View
     */
    public function index1(Request $request)
    {
        $fechaAi = now()->startOfDay()->toDateTimeString();
        $fechaAf = now()->endOfDay()->toDateTimeString();

        $droguerias = [
            '' => 'Todas',
            '2' => 'SALUD',
            '3' => 'DOLOR',
            '4' => 'PAC',
            '5' => 'EHU1',
            '6' => 'BIO1',
            '8' => 'EM01',
            '9' => 'FSIO',
            '10' => 'FSOS',
            '11' => 'FSAU',
            '12' => 'EVSO',
            '13' => 'FRJA'
        ];

        $drogueria = $droguerias[Auth::user()->drogueria] ?? '';

        if ($request->ajax()) {
            $query = DispensadoApiMedcol6::query();

            // Filtro por droguería
            if ($drogueria) {
                if ($drogueria === 'PAC') {
                    $query->whereIn('centroprod', ['FRJA', 'EVEN', 'PAC', 'EM01', 'EHU1']);
                } elseif ($drogueria !== 'Todas') {
                    $query->where('centroprod', $drogueria);
                }
            }

            // Validar que fechaini y fechafin sean obligatorios
            if (!empty($request->fechaini) && !empty($request->fechafin)) {
                $fechaIni = Carbon::parse($request->fechaini)->startOfDay()->toDateTimeString();
                $fechaFin = Carbon::parse($request->fechafin)->endOfDay()->toDateTimeString();
                $query->whereBetween('fecha_suministro', [$fechaIni, $fechaFin]);

                // Aplicar contrato si está presente
                if (!empty($request->contrato)) {
                    $query->where('centroprod', $request->contrato);
                }

                // Aplicar cobertura si está presente
                if (!empty($request->cobertura)) {
                    $query->where('tipo_medicamento', $request->cobertura);
                }
            } else {
                // Si no se pasan fechas, usar el rango del día actual
                $query->whereBetween('fecha_suministro', [$fechaAi, $fechaAf]);
            }

            // Aplicar filtros de estado y códigos
            $query->where(function ($q) {
                $q->where('estado', 'DISPENSADO')
                    ->whereNotIn('codigo', ['1010', '1011', '1012'])
                    ->orWhereNull('estado');
            });

            // Obtener resultados ordenados
            $resultados = $query->orderBy('fecha_suministro')->get();

            return DataTables()->of($resultados)
                ->addColumn('copago1', fn($dispensado) => $dispensado->cuota_moderadora_sumada)
                ->rawColumns(['copago1'])
                ->make(true);
        }

        return view('menu.Medcol6.indexDispensado', ['droguerias' => $droguerias]);
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createdispensadoapi(Request $request)
    {



        /* $email = 'castrokofdev@gmail.com';
    $password = 'colMed2023**';
    $usuario = Auth::user()->email;
    
     // Despachar el Job a la cola
    DispensadoSosApiJob::dispatch($email, $password, $usuario);

    return response()->json([
        'respuesta' => 'Sincronización en progreso. Te notificaremos cuando esté lista.',
        'titulo' => 'Sincronización en curso',
        'icon' => 'info',
        'position' => 'bottom-left'
    ]);*/



        $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = 'colMed2023**';
        $usuario = Auth::user()->email;

        set_time_limit(0);


        try {


            $response = Http::post("http://hed08pf9dxt.sn.mynetname.net:8004/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];



            if ($token) {


                try {



                    $responsefacturas = Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/dispensadoapi");

                    //dd($responsefacturas);

                    $facturassapi = $responsefacturas->json()['data'];

                    // dd($facturassapi);

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
                                'tipoentrega'  => trim($factura['tipoentrega']),
                                'ID_REGISTRO'  => trim($factura['ID_REGISTRO']),
                                'created_at'  => now()
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
                    Log::error('Error en la sincronización SERVER IP FIJA: ' . $e->getMessage()); // Registrar el error en los logs de Laravel

                    return response()->json([
                        ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
                    ]);
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
                                    'tipoentrega'  => trim($factura['tipoentrega']),
                                    'ID_REGISTRO'  => trim($factura['ID_REGISTRO']),
                                    'created_at'  => now()

                                ];

                                if (!empty($dispensados)) {
                                    DispensadoApiMedcol6::insert($dispensados);
                                }

                                $contador++;
                            }
                        }

                        Http::withToken($token)->get("http://192.168.66.91:8004/api/closeallacceso");


                        Log::info('Desde la web syncapi centralizado local' . $contador . ' Lineas dispensadas' . ' Usuario: ' . $usuario);

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


                // Manejo de la excepción
                Log::error($e->getMessage()); // Registrar el error en los logs de Laravel


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
        $fechaAi = now()->startOfDay()->toDateTimeString();
        $fechaAf = now()->endOfDay()->toDateTimeString();

        $droguerias = [
            '' => 'Todas',
            '2' => 'SALUD',
            '3' => 'DOLOR',
            '4' => 'PAC',
            '5' => 'EHU1',
            '6' => 'BIO1',
            '8' => 'EM01',
            '9' => 'FSIO',
            '10' => 'FSOS',
            '11' => 'FSAU',
            '12' => 'EVSO',
            '13' => 'FRJA'
        ];

        $drogueria = $droguerias[Auth::user()->drogueria] ?? '';

        if ($request->ajax()) {
            $query = DispensadoApiMedcol6::query();

            // Filtro por droguería
            if ($drogueria) {
                if ($drogueria === 'PAC') {
                    $query->whereIn('centroprod', ['FRJA', 'EVEN', 'PAC', 'EM01', 'EHU1']);
                } elseif ($drogueria !== 'Todas') {
                    $query->where('centroprod', $drogueria);
                }
            }

            // Validar que fechaini y fechafin sean obligatorios
            if (!empty($request->fechaini) && !empty($request->fechafin)) {
                $fechaIni = Carbon::parse($request->fechaini)->startOfDay()->toDateTimeString();
                $fechaFin = Carbon::parse($request->fechafin)->endOfDay()->toDateTimeString();
                $query->whereBetween('fecha_suministro', [$fechaIni, $fechaFin]);

                // Aplicar contrato si está presente
                if (!empty($request->contrato)) {
                    $query->where('centroprod', $request->contrato);
                }

                // Aplicar cobertura si está presente
                if (!empty($request->cobertura)) {
                    $query->where('tipo_medicamento', $request->cobertura);
                }
            } else {
                // Si no se pasan fechas, usar el rango del día actual
                $query->whereBetween('fecha_suministro', [$fechaAi, $fechaAf]);
            }

            // Aplicar filtros de estado y códigos
            $query->where(function ($q) {
                $q->where('estado', 'REVISADO')
                    ->whereNotIn('codigo', ['1010', '1011', '1012'])
                    ->orWhereNull('estado');
            });

            $resultados = $query->get()->map(function ($item) {
                $lista = ListasDetalle::find($item->ips);
                $item->ips_nombre = $lista->nombre ?? '';
                $item->nitips = $lista->slug ?? '';
                return $item;
            });

            return DataTables()->of($resultados)
                ->addColumn('action', function ($dispensado) {
                    return '
                        <button type="button" name="show_detail" id="' . $dispensado->id . '" 
                            class="show_detail btn btn-sm btn-secondary tooltipsC" title="Detalle">
                            <i class="fas fa-prescription-bottle-alt"></i> Detalle
                        </button>
                        <button type="button" name="edit_dispensado" id="' . $dispensado->id . '" 
                            class="edit_dispensado btn btn-sm btn-info tooltipsC" title="Editar">
                            <i class="fas fa-pencil-alt"></i> Editar
                        </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('menu.Medcol6.indexDispensado', ['droguerias' => $drogueria]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adddispensacionarray(Request $request)
    {
        $request->validate([
            'data.*.diagnostico' => 'required', // Campo 'diagnostico' requerido
            'data.*.fecha_orden' => 'required', // Campo 'fecha_orden' requerido
            'data.*.ips' => 'required', // Campo 'ips' requerido
            'data.*.numero_entrega1' => 'required', // Campo 'numero_entrega1' requerido
            'data.*.fecha_suministro',
        ]);

        $add_factura = $request->data;
        $fecha_suministro = Carbon::parse($request->input('fecha_suministro'))->format('Y-m-d');


        foreach ($add_factura as $add) {
            // Obtener la fecha de ordenamiento y formatearla como objeto Carbon
            $fechaOrden = Carbon::parse($add['fecha_orden'])->format('Y-m-d');

            // Verificar si la fecha_orden es mayor a la fecha_suministro
            if (strtotime($fechaOrden) > strtotime($fecha_suministro)) {
                return response()->json([
                    'errors' => [
                        'fecha_orden' => [
                            'La Fecha de Ordenamiento no puede ser superior a la Fecha de Suministro'
                        ]
                    ]
                ], 422);
            }


            DispensadoApiMedcol6::where('id', $add['ID'])
                ->update(
                    [
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
                        'updated_at' => now()
                    ]

                );
        }

        return response()->json(['success' => 'ok']);
    }



    public function updateanuladosapi(Request $request)
    {
        $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = 'colMed2023**';
        $usuario = Auth::user()->email;

        try {
            $response = Http::post("http://hed08pf9dxt.sn.mynetname.net:8004/api/acceso", [
                'email' => $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"] ?? null;

            if ($token) {
                try {
                    $responsefacturas = Http::withToken($token)->get("hed08pf9dxt.sn.mynetname.net:8004/api/anuladosapi");
                    $facturassapi = $responsefacturas->json()['data'] ?? [];

                    $contadorActualizados = 0;

                    //dd($facturassapi);

                    foreach ($facturassapi as $factura) {
                        if (isset($factura['factura'])) {
                            $actualizados = DispensadoApiMedcol6::where('factura', $factura['factura'])
                                ->whereIn('estado', ['DISPENSADO', 'REVISADO'])
                                ->update([
                                    'estado' => 'ANULADA',
                                    'updated_at' => now()
                                ]);
                        } elseif (isset($factura['factura_electronica'])) {
                            $actualizados = DispensadoApiMedcol6::whereRaw("CONCAT(documento_origen, factura_origen) = ?", [$factura['factura_electronica']])
                                ->whereIn('estado', ['DISPENSADO', 'REVISADO'])
                                ->update([
                                    'estado' => 'ANULADA',
                                    'updated_at' => now()
                                ]);
                        }

                        if ($actualizados) {
                            $contadorActualizados++;
                        }
                    }



                    Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/closeallacceso");

                    Log::info('Desde la web syncapi centralizado anulados', [
                        'lineas_actualizadas' => $contadorActualizados,
                        'usuario' => $usuario
                    ]);

                    return response()->json([
                        [
                            'respuesta' => $contadorActualizados . " Facturas anuladas",
                            'titulo' => 'Lineas Actualizadas',
                            'icon' => 'success',
                            'position' => 'bottom-left'
                        ]
                    ]);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());

                    return response()->json([
                        'respuesta' => 'Error: ' . $e->getMessage(),
                        'titulo' => 'Error',
                        'icon' => 'error',
                        'position' => 'bottom-left'
                    ]);
                }
            } else {
                return response()->json([
                    'respuesta' => 'Error: No se pudo obtener el token',
                    'titulo' => 'Error',
                    'icon' => 'error',
                    'position' => 'bottom-left'
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'respuesta' => 'Error: ' . $e->getMessage(),
                'titulo' => 'Error',
                'icon' => 'error',
                'position' => 'bottom-left'
            ]);
        }
    }


    public function disanulado(Request $request)
    {
        $fechaAi = now()->toDateString() . " 00:00:01";
        $fechaAf = now()->toDateString() . " 23:59:59";

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

        //$droguerias = $drogueria[Auth::user()->drogueria] ?? '';

        if ($request->ajax()) {
            $query = DispensadoApiMedcol6::whereIn('estado', ['ANULADA', null])
                ->orderBy('id');

            if (Auth::user()->drogueria != '1') {
                $query->where('centroprod', $drogueria);
            }

            if (!empty($request->fechaini) && !empty($request->fechafin)) {
                $fechaini = Carbon::parse($request->fechaini);
                $fechafin = Carbon::parse($request->fechafin)->endOfDay();
                $query->whereBetween('fecha_suministro', [$fechaini, $fechafin]);
            } else {
                $query->whereBetween('fecha_suministro', [$fechaAi, $fechaAf]);
            }

            $resultados = $query->get()->map(function ($item) {
                $ipsId = $item->ips;
                $lista = ListasDetalle::where('id', $ipsId)->first();
                $item->ips_nombre = $lista ? $lista->nombre : '';
                return $item;
            });

            return DataTables()->of($resultados)
                ->addColumn('action', function ($dispensado) {
                    $button = '<button type="button" name="show_detail" id="' . $dispensado->id . '
                        " class="show_detail btn btn-app bg-secondary tooltipsC" title="Detalle"  >
                        <span class="badge bg-teal">Detalle</span><i class="fas fa-prescription-bottle-alt"></i> </button>';
                    $button2 = '<button type="button" name="edit_dispensado" id="' . $dispensado->id . '
                        " class="edit_dispensado btn btn-app bg-info tooltipsC" title="Editar"  >
                        <span class="badge bg-teal">Editar</span><i class="fas fa-pencil-alt"></i> </button>';

                    return $button . ' ' . $button2;
                })
                ->addColumn('fecha Orden', function ($dispensado) {
                    $inputdate = '<input type="date" name="date_orden" id="' . $dispensado->id . '
                        " class="show_detail btn btn-app bg-secondary tooltipsC" title="Fecha">';

                    return $inputdate;
                })
                ->rawColumns(['action', 'fecha Orden'])
                ->make(true);
        }

        return view('menu.Medcol6.indexDispensado', ['droguerias' => $drogueria]);
    }


    public function buscar($factura)
    {
        // Verificar si la factura existe en la base de datos
        $facturaExistente = DispensadoApiMedcol6::where('factura', $factura)->whereNotIn('codigo', ['1010', '1011', '1012'])->exists();

        if (!$facturaExistente) {
            // Si la factura no existe, retornar un mensaje específico
            return response()->json(['error' => 'Factura no encontrada, no se ha sincronizado en la API'], 404);
        }

        // Verificar si la factura está en otro estado como "ANULADA" o "REVISADO"
        $facturaEstado = DispensadoApiMedcol6::where('factura', $factura)->whereNotIn('codigo', ['1010', '1011', '1012'])
            ->whereIn('estado', ['ANULADA'])
            ->first();

        if ($facturaEstado) {
            // Si la factura está en estado ANULADA o REVISADO, retornar un mensaje específico
            return response()->json(['error' => "La factura {$factura} se encuentra en estado {$facturaEstado->estado}."], 200);
        }

        // Continuar con la consulta de facturas en estado DISPENSADO
        $dispensado_api_medcol6 = DispensadoApiMedcol6::query();

        // Sumar la cuota moderadora y aplicar los filtros
        $resultados = $dispensado_api_medcol6->selectRaw(
            '*, 
                (CASE 
                    WHEN ROW_NUMBER() OVER(
                        PARTITION BY factura 
                        ORDER BY id
                    ) = 1 
                    AND codigo NOT IN ("1010", "1011", "1012") 
                        THEN (
                            CASE 
                                WHEN EXISTS (SELECT 1 FROM dispensado_medcol6 AS d2 WHERE d2.factura = dispensado_medcol6.factura AND d2.codigo = "1010") 
                                    THEN LEAST(4700, (SELECT SUM(cuota_moderadora) FROM dispensado_medcol6 AS d3 WHERE d3.factura = dispensado_medcol6.factura))
                                WHEN EXISTS (SELECT 1 FROM dispensado_medcol6 AS d2 WHERE d2.factura = dispensado_medcol6.factura AND d2.codigo = "1011") 
                                    THEN LEAST(19200, (SELECT SUM(cuota_moderadora) FROM dispensado_medcol6 AS d3 WHERE d3.factura = dispensado_medcol6.factura))
                                WHEN EXISTS (SELECT 1 FROM dispensado_medcol6 AS d2 WHERE d2.factura = dispensado_medcol6.factura AND d2.codigo = "1012") 
                                    THEN LEAST(50300, (SELECT SUM(cuota_moderadora) FROM dispensado_medcol6 AS d3 WHERE d3.factura = dispensado_medcol6.factura))
                                ELSE 
                                    (SELECT SUM(cuota_moderadora) FROM dispensado_medcol6 AS d3
                                     WHERE d3.factura = dispensado_medcol6.factura)
                            END
                        ) 
                    ELSE 0 
                END) AS cuota_moderadora_sumada'
        )
            ->where('factura', $factura)
            ->whereIn('estado', ['DISPENSADO', 'REVISADO'])
            ->whereNotIn('codigo', ['1010', '1011', '1012']) // Filtra los códigos después del cálculo
            ->orderBy('nombre_generico')
            ->get();

        // Verificar si se encontraron resultados
        if ($resultados->isEmpty()) {
            // Si no se encontraron resultados, retornar un mensaje específico
            return response()->json(['error' => 'Factura no encontrada en estado <strong>DISPENSADO</strong><br> o ya se encuentra <strong>REVISADA</strong>'], 404);
        }

        // Obtener la IPS de cada resultado
        $resultados = $resultados->map(function ($item) {
            $ipsId = $item->ips;
            $lista = !empty($ipsId) ? ListasDetalle::where('id', $ipsId)->first() : null;
            $item->ips_nombre = $lista ? $lista->nombre : 'Desconocido';
            return $item;
        });

        // Mapear los resultados a un array asociativo para incluir campos adicionales
        $data = $resultados->map(function ($item) {
            // Convertir el modelo a un array asociativo
            $dataArray = $item->toArray();

            // Agregar campos HTML personalizados a los datos resultantes
            $dataArray['action'] = '<input class="add_medicamento checkbox-large checkbox2 tooltipsC" type="checkbox" title="Seleccionar" id="' . $item->id . '" value="' . $item->id . '">';
            $dataArray['frecuencia2'] = '<input type="text" name="frecuencia2" id="' . $item->id . '" class="show_detail form-control btn bg-secondary tooltipsC" style="max-width: 100%;" title="Frecuencia con la cual el paciente debe tomar el medicamento" value="' . $item->frecuencia . '">';
            $dataArray['dosis2'] = '<input type="number" name="dosis2" id="' . $item->id . '" class="show_detail form-control btn bg-info tooltipsC" style="max-width: 100%;" title="Es la cantidad que debe tomar el paciente" value="' . $item->dosis . '">';
            $dataArray['duracion_tratamiento2'] = '<input type="number" name="duracion_tratamiento2" id="' . $item->id . '" class="show_detail form-control btn bg-info tooltipsC" style="max-width: 60%;" title="Poner la cantidad en días" value="' . $item->duracion_tratamiento . '">';
            $dataArray['autorizacion2'] = '<input type="text" name="autorizacion" id="' . $item->id . '" class="show_detail btn btn-xl bg-warning tooltipsC" style="max-width: 100%;" title="Autorizacion" value="' . $item->autorizacion . '">';
            $dataArray['mipres2'] = '<input type="text" name="mipres" id="' . $item->id . '" class="show_detail btn btn-xl bg-info tooltipsC" style="max-width: 100%;" title="mipres" value="' . $item->mipres . '">';
            $dataArray['reporte_entrega2'] = '<input type="text" name="reporte" id="' . $item->id . '" class="show_detail btn btn-xl bg-info tooltipsC" style="max-width: 100%;" title="Reporte de entrega" value="' . $item->reporte_entrega_nopbs . '">';
            $dataArray['cuota_moderadora2'] = '<input type="text" name="cuota_moderadora" id="' . $item->id . '" class="show_detail form-control btn bg-info tooltipsC" style="max-width: 65%;" title="Cuota Moderadora" value="' . $item->cuota_moderadora_sumada . '">';

            return $dataArray;
        });

        // Retornar los datos en formato JSON para DataTable
        return response()->json($data);
    }

    //funcion para actualizar los datos de la factura haciendo la insercion de los datos que se validan en el front
    public function actualizarDispensacion(Request $request)
    {
        // Validar los campos requeridos
        $request->validate([
            'data.*.id', // Campo 'id' requerido
            'data.*.fecha_orden',
            'data.*.numero_entrega',
            'data.*.diagnostico',
            'data.*.ips',
            'data.*.fecha_suministro',
            'data.*.num_total_entregas',
            'data.*.numero_orden',
            'data.*.duracion_tratamiento',
            //'data.*.plan',
            'data.*.frecuencia',
            'data.*.dosis'
        ]);

        // Obtener la fecha de suministro y formatearla como objeto Carbon
        $fechaSuministro = Carbon::parse($request->input('fecha_suministro'))->format('Y-m-d');

        try {
            // Obtener los registros de datos
            $datos = $request->input('data.registros');

            // Iterar sobre cada registro
            foreach ($datos as $idd) {
                // Obtener la fecha de ordenamiento y formatearla como objeto Carbon
                $fechaOrden = Carbon::parse($idd['fecha_orden'])->format('Y-m-d');

                // Verificar si la fecha de ordenamiento es menor o igual a la fecha de suministro
                if (strtotime($fechaOrden) > strtotime($fechaSuministro)) {
                    // Mostrar mensaje de error si la fecha de ordenamiento es mayor a la fecha de suministro
                    return response()->json([
                        'error' => 'La Fecha de Ordenamiento no puede ser superior a la Fecha de Suministro'
                    ], 422);
                }

                // Validación adicional: Verificar si el número de entrega es mayor que el número total de entregas
                // Mostrar mensaje de error si el número de entrega es mayor al número total de entregas
                if ($idd['numero_entrega'] > $idd['num_total_entregas']) {
                    return response()->json([
                        'error' => 'El Número de Entrega no puede ser mayor que el Número Total de Entregas'
                    ], 422);
                }

                // Actualizar los datos en la base de datos si pasa todas las validaciones
                DispensadoApiMedcol6::where('id', $idd['id'])
                    ->update([
                        'autorizacion' => trim($idd['autorizacion']),
                        'cuota_moderadora' => trim($idd['cuota_moderadora']),
                        'copago' => trim($idd['cuota_moderadora']),
                        'mipres' => trim($idd['mipres']),
                        'reporte_entrega_nopbs' => trim($idd['reporte_entrega']),
                        'numero_entrega' => trim($idd['numero_entrega']),
                        'fecha_ordenamiento' => trim($idd['fecha_orden']),
                        'dx' => trim($idd['diagnostico']),
                        'ips' => trim($idd['ips']),
                        'estado' => trim($idd['estado']),
                        'user_id' => trim($idd['user_id']),
                        'num_total_entregas' => trim($idd['num_total_entregas']),
                        'numero_orden' => trim($idd['numero_orden']),
                        'duracion_tratamiento' => trim($idd['duracion_tratamiento']),
                        'frecuencia' => trim($idd['frecuencia']),
                        'dosis' => trim($idd['dosis']),
                        'updated_at' => now()
                        //'plan' => trim($idd['plan']),
                    ]);
            }

            // Si se completó correctamente, devolver una respuesta JSON de éxito
            return response()->json(['success' => 'Datos actualizados correctamente'], 200);
        } catch (\Exception $e) {
            // Capturar excepciones y devolver un mensaje de error
            return response()->json(['error' => 'Error al actualizar los datos'], 500);
        }
    }

    public function gestionsdis(Request $request)
    {
        $i = Auth::user()->drogueria;

        // Mapeo de droguerías según el usuario
        $droguerias = [
            "1" => '',
            "2" => 'SALUD',
            "3" => 'DOLOR',
            "4" => 'PAC',
            "5" => 'EHU1',
            "6" => 'BIO1',
            "8" => 'EM01',
            "9" => 'FSIO',
            "10" => 'FSOS',
            "11" => 'FSAU',
            "12" => 'EVSO',
            "13" => 'FRJA',
        ];

        $drogueria = $droguerias[$i] ?? null;

        // Validar fechas y establecer valores por defecto
        $fechaInicio = $request->filled('fechaini')
            ? Carbon::parse($request->fechaini)->startOfDay()
            : now()->subMonth()->startOfDay();

        $fechaFin = $request->filled('fechafin')
            ? Carbon::parse($request->fechafin)->endOfDay()
            : now()->endOfDay();

        // Construcción de la consulta con filtros aplicados
        $queryBase = DispensadoApiMedcol6::select('centroprod', DB::raw('count(*) as total'))
            ->whereBetween('fecha_suministro', [$fechaInicio, $fechaFin])
            ->whereNotIn('codigo', ['1010', '1011', '1012'])
            ->groupBy('centroprod');

        // Aplicar filtro de droguería si el usuario no es admin (droguería "1")
        if ($i !== "1" && $drogueria) {
            $queryBase->where('centroprod', $drogueria);
        }

        // Obtener resultados para cada estado
        $dispensado = (clone $queryBase)->where('estado', 'DISPENSADO')->get();
        $revisado = (clone $queryBase)->where('estado', 'REVISADO')->get();
        $anulado = (clone $queryBase)->where('estado', 'ANULADA')->get();

        return response()->json([
            'dispensado' => $dispensado,
            'revisado' => $revisado,
            'anulado' => $anulado
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showdis($id)
    {
        if (request()->ajax()) {
            $dispensado = DispensadoApiMedcol6::find($id);

            return response()->json([
                'dispensado' => $dispensado
            ]);
        }
        return view('menu.Medcol6.indexDispensado');
    }

    public function gestionFacturasRevisadas(Request $request)
    {
        $i = Auth::user()->drogueria;

        // Mapeo de droguerías según el usuario
        $droguerias = [
            "1" => '',
            "2" => 'SALUD',
            "3" => 'DOLOR',
            "4" => 'PAC',
            "5" => 'EHU1',
            "6" => 'BIO1',
            "8" => 'EM01',
            "9" => 'FSIO',
            "10" => 'FSOS',
            "11" => 'FSAU',
            "12" => 'EVSO',
            "13" => 'FRJA',
        ];

        $drogueria = $droguerias[$i] ?? null;

        // Validar fechas y establecer valores por defecto
        $fechaInicio = $request->filled('fechaini')
            ? Carbon::parse($request->fechaini)->startOfDay()
            : now()->subMonth()->startOfDay();

        $fechaFin = $request->filled('fechafin')
            ? Carbon::parse($request->fechafin)->endOfDay()
            : now()->endOfDay();

        // Capturar tipo de medicamento enviado desde el frontend
        $tipoMedicamento = $request->input('tipo_medicamento', '2');

        // Construcción de la consulta con filtros aplicados
        $queryBase = DispensadoApiMedcol6::select(DB::raw('COUNT(DISTINCT(factura)) as total'))
            ->where('tipo_medicamento', $tipoMedicamento)
            ->whereBetween('fecha_suministro', [$fechaInicio, $fechaFin])
            ->where('estado', 'REVISADO');

        // Aplicar filtro de droguería si el usuario no es admin (droguería "1")
        if ($i !== "1" && $drogueria) {
            $queryBase->where('centroprod', $drogueria);
        }

        // Obtener total de facturas revisadas
        $totalFacturasRevisadas = $queryBase->first()->total;

        return response()->json([
            'total_facturas_revisadas' => $totalFacturasRevisadas
        ]);
    }
}
