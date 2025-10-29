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
        // Validar fechas requeridas
        $request->validate([
            'fechaini' => 'required|date',
            'fechafin' => 'required|date|after_or_equal:fechaini'
        ]);

        $fechaini = Carbon::parse($request->fechaini)->startOfDay()->toDateTimeString();
        $fechafin = Carbon::parse($request->fechafin)->endOfDay()->toDateTimeString();
        $contrato = $request->contrato;

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
                ->whereBetween('fecha_suministro', [$fechaini, $fechafin])
                ->whereNotIn('codigo', ['1010', '1011', '1012'])
                ->when($contrato, function($query) use ($contrato) {
                    return $query->where('centroprod', $contrato);
                })
                ->orderBy('total', 'desc')
                ->groupBy('centroprod')
                ->get();

            $revisado = DispensadoApiMedcol6::select('centroprod', DB::raw('count(*) as total'))
                ->where('estado', 'REVISADO')
                ->whereBetween('fecha_suministro', [$fechaini, $fechafin])
                ->whereNotIn('codigo', ['1010', '1011', '1012'])
                ->when($contrato, function($query) use ($contrato) {
                    return $query->where('centroprod', $contrato);
                })
                ->orderBy('total', 'desc')
                ->groupBy('centroprod')
                ->get();

            $anulado = DispensadoApiMedcol6::select('centroprod', DB::raw('count(*) as total'))
                ->where('estado', 'ANULADA')
                ->whereBetween('fecha_suministro', [$fechaini, $fechafin])
                ->whereNotIn('codigo', ['1010', '1011', '1012'])
                ->when($contrato, function($query) use ($contrato) {
                    return $query->where('centroprod', $contrato);
                })
                ->orderBy('total', 'desc')
                ->groupBy('centroprod')
                ->get();
        } else {

            $dispensado = DispensadoApiMedcol6::select('centroprod', DB::raw('count(*) as total'))
                ->where('estado', 'DISPENSADO')
                ->where('centroprod', $drogueria)
                ->whereBetween('fecha_suministro', [$fechaini, $fechafin])
                ->whereNotIn('codigo', ['1010', '1011', '1012'])
                ->when($contrato, function($query) use ($contrato) {
                    return $query->where('centroprod', $contrato);
                })
                ->orderBy('total', 'desc')
                ->groupBy('centroprod')
                ->get();

            $revisado = DispensadoApiMedcol6::select('centroprod', DB::raw('count(*) as total'))
                ->where('estado', 'REVISADO')
                ->where('centroprod', $drogueria)
                ->whereBetween('fecha_suministro', [$fechaini, $fechafin])
                ->whereNotIn('codigo', ['1010', '1011', '1012'])
                ->when($contrato, function($query) use ($contrato) {
                    return $query->where('centroprod', $contrato);
                })
                ->orderBy('total', 'desc')
                ->groupBy('centroprod')
                ->get();

            $anulado = DispensadoApiMedcol6::select('centroprod', DB::raw('count(*) as total'))
                ->where('estado', 'ANULADA')
                ->where('centroprod', $drogueria)
                ->whereBetween('fecha_suministro', [$fechaini, $fechafin])
                ->whereNotIn('codigo', ['1010', '1011', '1012'])
                ->when($contrato, function($query) use ($contrato) {
                    return $query->where('centroprod', $contrato);
                })
                ->orderBy('total', 'desc')
                ->groupBy('centroprod')
                ->get();
        }

        // Mapeo de códigos a nombres de farmacias
        $nombresFarmacias = [
            'BIO1' => 'BIO1-FARMACIA BIOLOGICOS',
            'DLR1' => 'DLR1-FARMACIA DOLOR',
            'DPA1' => 'DPA1-FARMACIA PALIATIVOS',
            'EM01' => 'EM01-FARMACIA EMCALI',
            'EHU1' => 'EHU1-FARMACIA HUERFANAS',
            'FRJA' => 'FRJA-FARMACIA JAMUNDI',
            'INY' => 'INY-FARMACIA INYECTABLES',
            'PAC' => 'PAC-FARMACIA PAC',
            'SM01' => 'SM01-FARMACIA SALUD MENTAL',
            'BPDT' => 'BPDT-BOLSA',
            'EVEN' => 'EVEN-FARMACIA EVENTO',
            'EVSM' => 'EVSM-EVENTO SALUD MENTAL',
            'SALUD' => 'SALUD-FARMACIA SALUD MENTAL',
            'DOLOR' => 'DOLOR-FARMACIA DOLOR',
            'FSIO' => 'FSIO-FARMACIA SIO',
            'FSOS' => 'FSOS-FARMACIA SOS',
            'FSAU' => 'FSAU-FARMACIA SAU',
            'EVSO' => 'EVSO-EVENTO SOS',
            'FRIO' => 'FRIO-FARMACIA FRIO',
            'FRIP' => 'FRIP-FARMACIA FRIP',
            'F24H' => 'F24H-FARMACIA 24 HORAS',
            'FRPE' => 'FRPE-FARMACIA PREVENTIVA'
        ];

        // Calcular totales
        $totalDispensado = $dispensado->sum('total');
        $totalRevisado = $revisado->sum('total');
        $totalAnulado = $anulado->sum('total');

        // Generar HTML para dispensados
        $htmlDispensado = '<table class="table table-sm table-bordered table-striped">';
        $htmlDispensado .= '<thead class="thead-light"><tr><th>Farmacia</th><th class="text-center">Cantidad</th></tr></thead><tbody>';
        foreach ($dispensado as $item) {
            $nombreFarmacia = $nombresFarmacias[$item->centroprod] ?? $item->centroprod;
            $htmlDispensado .= '<tr><td>' . $nombreFarmacia . '</td><td class="text-center font-weight-bold">' . number_format($item->total) . '</td></tr>';
        }
        if ($dispensado->isEmpty()) {
            $htmlDispensado .= '<tr><td colspan="2" class="text-center text-muted">No hay registros</td></tr>';
        }
        $htmlDispensado .= '</tbody><tfoot><tr class="bg-primary text-white font-weight-bold"><td>TOTAL</td><td class="text-center">' . number_format($totalDispensado) . '</td></tr></tfoot></table>';

        // Generar HTML para revisados
        $htmlRevisado = '<table class="table table-sm table-bordered table-striped">';
        $htmlRevisado .= '<thead class="thead-light"><tr><th>Farmacia</th><th class="text-center">Cantidad</th></tr></thead><tbody>';
        foreach ($revisado as $item) {
            $nombreFarmacia = $nombresFarmacias[$item->centroprod] ?? $item->centroprod;
            $htmlRevisado .= '<tr><td>' . $nombreFarmacia . '</td><td class="text-center font-weight-bold">' . number_format($item->total) . '</td></tr>';
        }
        if ($revisado->isEmpty()) {
            $htmlRevisado .= '<tr><td colspan="2" class="text-center text-muted">No hay registros</td></tr>';
        }
        $htmlRevisado .= '</tbody><tfoot><tr class="bg-success text-white font-weight-bold"><td>TOTAL</td><td class="text-center">' . number_format($totalRevisado) . '</td></tr></tfoot></table>';

        // Generar HTML para anulados
        $htmlAnulado = '<table class="table table-sm table-bordered table-striped">';
        $htmlAnulado .= '<thead class="thead-light"><tr><th>Farmacia</th><th class="text-center">Cantidad</th></tr></thead><tbody>';
        foreach ($anulado as $item) {
            $nombreFarmacia = $nombresFarmacias[$item->centroprod] ?? $item->centroprod;
            $htmlAnulado .= '<tr><td>' . $nombreFarmacia . '</td><td class="text-center font-weight-bold">' . number_format($item->total) . '</td></tr>';
        }
        if ($anulado->isEmpty()) {
            $htmlAnulado .= '<tr><td colspan="2" class="text-center text-muted">No hay registros</td></tr>';
        }
        $htmlAnulado .= '</tbody><tfoot><tr class="bg-danger text-white font-weight-bold"><td>TOTAL</td><td class="text-center">' . number_format($totalAnulado) . '</td></tr></tfoot></table>';

        return response()->json([
            'dispensado' => $htmlDispensado,
            'revisado' => $htmlRevisado,
            'anulados' => $htmlAnulado
        ]);
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
            try {
                $query = DispensadoApiMedcol6::query();

                // Filtro por droguería del usuario logueado
                if ($drogueria && $drogueria !== 'Todas') {
                    if ($drogueria === 'PAC') {
                        $query->whereIn('centroprod', ['FRJA', 'EVEN', 'PAC', 'EM01', 'EHU1', 'FRIO', 'FRIP']);
                    } else {
                        $query->where('centroprod', $drogueria);
                    }
                }

                // Validar que fechaini y fechafin estén presentes
                if (!empty($request->fechaini) && !empty($request->fechafin)) {
                    $fechaIni = Carbon::parse($request->fechaini)->startOfDay()->toDateTimeString();
                    $fechaFin = Carbon::parse($request->fechafin)->endOfDay()->toDateTimeString();
                    $query->whereBetween('fecha_suministro', [$fechaIni, $fechaFin]);
                } else {
                    // Si no se pasan fechas, usar el rango del día actual
                    $query->whereBetween('fecha_suministro', [$fechaAi, $fechaAf]);
                }

                // Aplicar filtro de contrato si está presente (es un string único)
                if (!empty($request->contrato)) {
                    $query->where('centroprod', $request->contrato);
                }

                // Aplicar cobertura si está presente (1=PBS, 2=NOPBS)
                if (!empty($request->cobertura)) {
                    $query->where('tipo_medicamento', $request->cobertura);
                }

                // Aplicar filtros de estado y códigos excluidos
                $query->where('estado', 'DISPENSADO')
                      ->whereNotIn('codigo', ['1010', '1011', '1012']);

                // Log para debugging
                \Log::info('Query Dispensados:', [
                    'sql' => $query->toSql(),
                    'bindings' => $query->getBindings(),
                    'fechaini' => $request->fechaini,
                    'fechafin' => $request->fechafin,
                    'contrato' => $request->contrato,
                    'cobertura' => $request->cobertura
                ]);

                return DataTables()->of($query)
                    ->make(true);

            } catch (\Exception $e) {
                \Log::error('Error en index1 DataTables:', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);

                return response()->json([
                    'error' => 'Error al procesar la solicitud: ' . $e->getMessage()
                ], 500);
            }
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


        // Obtener la fecha límite de los últimos 7 días
        $fechaLimite = Carbon::now()->startOfWeek()->subDays(8)->startOfDay();



        $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = 'colMed2023**';
        $usuario = Auth::user()->email;

        set_time_limit(0);
        ini_set('memory_limit', '512M');


        try {


            $response = Http::post("http://hed08pf9dxt.sn.mynetname.net:8004/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];



            if ($token) {


                try {




                    $responsefacturas = Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/dispensadoapi");



                    $facturassapi = $responsefacturas->json()['data'];

                    $contadorei = 0;
                    $contador = 0;


                    // Obtener las facturas existentes en un solo query

                    // Crear las claves únicas de cada entrada en $facturassapi
                    $clavesFacturasApi = array_map(function ($f) {
                        return trim($f['factura']) . '-' . trim($f['codigo']) . '-' . trim($f['ID_REGISTRO']);
                    }, $facturassapi);

                    $facturasExistentes = collect();

                    // Consultar la base de datos en chunks para evitar demasiados placeholders
                    foreach (array_chunk($facturassapi, 500) as $chunk) {
                        $facturas = array_map(fn($f) => trim($f['factura']), $chunk);
                        $codigos = array_map(fn($f) => trim($f['codigo']), $chunk);
                        $ids = array_map(fn($f) => trim($f['ID_REGISTRO']), $chunk);

                        $resultados = DispensadoApiMedcol6::select('factura', 'codigo', 'ID_REGISTRO')
                            ->whereIn('factura', $facturas)
                            ->whereIn('codigo', $codigos)
                            ->whereIn('ID_REGISTRO', $ids)
                            ->where('fecha_suministro', '>=', $fechaLimite)
                            ->get();

                        $facturasExistentes = $facturasExistentes->merge($resultados);
                    }

                    // Crear claves únicas de los existentes en base de datos
                    $facturasExistentesFlip = array_flip(
                        $facturasExistentes->map(function ($item) {
                            return trim($item->factura) . '-' . trim($item->codigo) . '-' . trim($item->ID_REGISTRO);
                        })->toArray()
                    );

                    $dispensados = [];

                    foreach ($facturassapi as $factura) {

                        // Verificar si la factura ya existe en el array obtenido antes
                        $clave = trim($factura['factura']) . '-' . trim($factura['codigo']) . '-' . trim($factura['ID_REGISTRO']);



                        if (isset($facturasExistentesFlip[$clave])) {
                            // Registrar en el log como "NO" (porque ya existe)
                            Log::info("{$clave} => NO (ya existe)");
                            continue;
                        }

                        // Registrar en el log como "SI" (porque se va a insertar)
                        Log::info("{$clave} => SI (se inserta)");



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
                            'numeroIdentificacion'  => trim($factura['numeroIdentificacion']),
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
                            DispensadoApiMedcol6::insertOrIgnore($chunk);
                        }
                    }



                    Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/closeallacceso");

                    Log::info('Desde la web syncapi centralizado ' . $contador . ' Lineas dispensadas' . ' Usuario: ' . $usuario);

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


                $response = Http::post("http://192.168.66.95:8004/api/acceso", [
                    'email' =>  $email,
                    'password' => $password,
                ]);

                $token = $response->json()["token"];

                if ($token) {

                    try {




                        $responsefacturas = Http::withToken($token)->get("http://192.168.66.95:8004/api/dispensadoapi");

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
                                    'numeroIdentificacion'  => trim($factura['numeroIdentificacion']),
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

                        Http::withToken($token)->get("http://192.168.66.95:8004/api/closeallacceso");


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
        // Validar parámetros de DataTables
        $request->validate([
            'draw' => 'required|integer',
            'start' => 'integer',
            'length' => 'integer',
            //'fechaini' => 'nullable|date',
            //'fechafin' => 'nullable|date|after_or_equal:fechaini',
            //'contrato' => 'nullable|string',
            //'cobertura' => 'nullable|string'
        ]);

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

        $query = DispensadoApiMedcol6::query();

        // Aplicar filtro de droguería
        if ($drogueria) {
            if ($drogueria === 'PAC') {
                $query->whereIn('centroprod', ['FRJA', 'EVEN', 'PAC', 'EM01', 'EHU1', 'BPDT']);
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
                $query->whereIn('centroprod', $request->contrato);
            }

            // Aplicar cobertura si está presente
            if (!empty($request->cobertura)) {
                $query->where('tipo_medicamento', $request->cobertura);
            }
        } else {
            // Si no se pasan fechas, usar el rango del día actual
            $query->whereBetween('fecha_suministro', [$fechaAi, $fechaAf]);
        }

        // Aplicar filtro de estado y códigos
        $query->where(function ($q) {
            $q->where('estado', 'REVISADO')
                ->whereNotIn('codigo', ['1010', '1011', '1012']);
        })->orWhere(function ($q) {
            $q->whereNull('estado');
        });

        // Obtener total de registros sin filtrar
        $totalRecords = DispensadoApiMedcol6::count();

        // Obtener total filtrado
        $filteredRecords = $query->count();

        // Paginación y obtención de datos
        $data = $query->skip($request->start)
            ->take($request->length)
            ->get();

        // Obtener información de ListasDetalle
        $ipsIds = $data->pluck('ips')->filter()->unique();
        $listas = ListasDetalle::whereIn('id', $ipsIds)->get()->keyBy('id');

        // Formatear datos
        $formattedData = $data->map(function ($item) use ($listas) {
            $lista = $listas[$item->ips] ?? null;

            return [
                // Tus campos de datos aquí
                'id' => $item->id,
                'idusuario' => $item->idusuario,
                'tipo' => $item->tipo,
                'facturad' => $item->facturad ?? '',
                'factura' => $item->factura ?? '',
                'tipodocument' => $item->tipodocument ?? '',
                'historia' => $item->historia ?? '',
                'cums' => $item->cums ?? '',
                'expediente' => $item->expediente ?? '',
                'consecutivo' => $item->consecutivo ?? '',
                'cums_rips' => $item->cums_rips ?? '',
                'codigo' => $item->codigo ?? '',
                'tipo_medicamento' => $item->tipo_medicamento ?? '',
                'nombre_generico' => $item->nombre_generico ?? '',
                'atc' => $item->atc ?? '',
                'forma' => $item->forma ?? '',
                'concentracion' => $item->concentracion ?? '',
                'unidad_medicamento' => $item->unidad_medicamento ?? '',
                'numero_unidades' => $item->numero_unidades ?? '',
                'regimen' => $item->regimen ?? '',
                'paciente' => $item->paciente ?? '',
                'primer_apellido' => $item->primer_apellido ?? '',
                'segundo_apellido' => $item->segundo_apellido ?? '',
                'primer_nombre' => $item->primer_nombre ?? '',
                'segundo_nombre' => $item->segundo_nombre ?? '',
                'cuota_moderadora' => $item->cuota_moderadora ?? '',
                'copago' => $item->copago ?? '',
                'numero_orden' => $item->numero_orden ?? '',
                'numero_entrega' => $item->numero_entrega ?? '',
                'num_total_entregas' => $item->num_total_entregas ?? '',
                'fecha_ordenamiento' => $item->fecha_ordenamiento ?? '',
                'fecha_suministro' => $item->fecha_suministro ?? '',
                'dx' => $item->dx ?? '',
                // ... otros campos ...
                'nitips' => $lista->slug ?? '',
                'ips_nombre' => $lista->nombre ?? '',

                'autorizacion' => $item->autorizacion ?? '',
                'mipres' => $item->mipres ?? '',
                'reporte_entrega_nopbs' => $item->reporte_entrega_nopbs ?? '',
                'id_medico' => $item->id_medico ?? '',
                'numeroIdentificacion' => $item->numeroIdentificacion ?? '',
                'medico' => $item->medico ?? '',
                'especialidadmedico' => $item->especialidadmedico ?? '',
                'precio_unitario' => $item->precio_unitario ?? '',
                'valor_total' => $item->valor_total ?? '',
                'estado' => $item->estado ?? '',
                'centroprod' => $item->centroprod ?? '',
                'drogueria' => $item->drogueria ?? '',
                'cajero' => $item->cajero ?? '',
                'user_id' => $item->user_id ?? '',
                'nitips' => $lista->slug ?? '',
                'frecuencia' => $item->frecuencia ?? '',
                'dosis' => $item->dosis ?? '',
                'duracion_tratamiento' => $item->duracion_tratamiento ?? '',
                'cobertura' => $item->cobertura ?? '',
                'tipocontrato' => $item->tipocontrato ?? '',
                'tipoentrega' => $item->tipoentrega ?? '',
                'plan' => $item->plan ?? '',
                'via' => $item->via ?? '',
                'ciudad' => $item->ciudad ?? '',

                'action' => '
                    <button type="button" name="show_detail" id="' . $item->id . '" 
                        class="show_detail btn btn-sm btn-secondary tooltipsC" title="Detalle">
                        <i class="fas fa-prescription-bottle-alt"></i> Detalle
                    </button>
                    <button type="button" name="edit_dispensado" id="' . $item->id . '" 
                        class="edit_dispensado btn btn-sm btn-info tooltipsC" title="Editar">
                        <i class="fas fa-pencil-alt"></i> Editar
                    </button>
                '
            ];
        });

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $formattedData
        ]);
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

        // Aumentar tiempo de ejecución
        set_time_limit(600); // 10 minutos

        try {
            // Autenticación
            $response = Http::timeout(30)->post("http://hed08pf9dxt.sn.mynetname.net:8004/api/acceso", [
                'email' => $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"] ?? null;

            if (!$token) {
                return response()->json([[
                    'respuesta' => 'Error: No se pudo obtener el token',
                    'titulo' => 'Error',
                    'icon' => 'error',
                    'position' => 'bottom-left'
                ]]);
            }

            // ✅ Variables para recolectar TODAS las facturas
            $todasLasFacturas = [];
            $todasLasFacturasElectronicas = [];
            $page = 1;
            $hasMore = true;
            $perPage = 1000; // Usar el mismo tamaño que el endpoint

            Log::info('Iniciando sincronización de facturas anuladas paginada', [
                'usuario' => $usuario,
                'per_page' => $perPage
            ]);

            // ✅ Obtener TODAS las páginas
            while ($hasMore) {
                try {
                    $responsefacturas = Http::timeout(60)
                        ->withToken($token)
                        ->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/anuladosapi", [
                            'page' => $page,
                            'per_page' => $perPage
                        ]);

                    if (!$responsefacturas->successful()) {
                        throw new \Exception("Error en página $page: " . $responsefacturas->status());
                    }

                    $data = $responsefacturas->json();

                    // Verificar si hay datos
                    if (!$data['success'] || $data['count'] == 0) {
                        $hasMore = false;
                        break;
                    }

                    $facturas = $data['facturas'] ?? [];
                    $facturasElectronicas = $data['facturas_electronicas'] ?? [];

                    // Agregar a las colecciones totales
                    $todasLasFacturas = array_merge($todasLasFacturas, $facturas);
                    $todasLasFacturasElectronicas = array_merge($todasLasFacturasElectronicas, $facturasElectronicas);

                    Log::info("Página $page procesada", [
                        'facturas_normales' => count($facturas),
                        'facturas_electronicas' => count($facturasElectronicas),
                        'total_acumulado_normales' => count($todasLasFacturas),
                        'total_acumulado_electronicas' => count($todasLasFacturasElectronicas),
                        'has_more' => $data['has_more'] ?? false
                    ]);

                    // Verificar si hay más páginas
                    $hasMore = $data['has_more'] ?? false;
                    $page++;

                    // Pequeña pausa para no saturar el servidor
                    if ($hasMore) {
                        usleep(100000); // 0.1 segundos
                    }
                } catch (\Exception $e) {
                    Log::error("Error obteniendo página $page", [
                        'error' => $e->getMessage()
                    ]);

                    // Si falla una página, continuamos con las demás
                    if ($page > 1) {
                        // Si ya tenemos datos, continuamos
                        break;
                    } else {
                        // Si es la primera página, lanzamos error
                        throw $e;
                    }
                }
            }

            Log::info('Datos completos obtenidos de la API', [
                'total_facturas_normales' => count($todasLasFacturas),
                'total_facturas_electronicas' => count($todasLasFacturasElectronicas),
                'total_paginas' => $page - 1
            ]);

            // ✅ Contadores separados
            $facturasNormalesAnuladas = 0;
            $facturasElectronicasAnuladas = 0;

            // ✅ Procesar TODAS las facturas normales en UNA SOLA consulta
            if (!empty($todasLasFacturas)) {
                $numerosFacturas = array_filter(array_column($todasLasFacturas, 'factura'));

                if (!empty($numerosFacturas)) {
                    // Remover duplicados
                    $numerosFacturas = array_unique($numerosFacturas);

                    $facturasNormalesAnuladas = DispensadoApiMedcol6::whereIn('factura', $numerosFacturas)
                        ->whereIn('estado', ['DISPENSADO', 'REVISADO'])
                        ->update([
                            'estado' => 'ANULADA',
                            'updated_at' => now()
                        ]);

                    Log::info('Facturas normales actualizadas', [
                        'cantidad_actualizada' => $facturasNormalesAnuladas,
                        'total_recibidas' => count($numerosFacturas)
                    ]);
                }
            }

            // ✅ Procesar TODAS las facturas electrónicas con CONCAT en LOTES
            if (!empty($todasLasFacturasElectronicas)) {
                $facturasElectronicasNumeros = array_filter(
                    array_column($todasLasFacturasElectronicas, 'factura')
                );

                if (!empty($facturasElectronicasNumeros)) {
                    // Remover duplicados
                    $facturasElectronicasNumeros = array_unique($facturasElectronicasNumeros);

                    // Dividir en lotes de 50 para evitar queries muy grandes
                    $lotes = array_chunk($facturasElectronicasNumeros, 50);

                    Log::info('Iniciando procesamiento de facturas electrónicas', [
                        'total_facturas' => count($facturasElectronicasNumeros),
                        'total_lotes' => count($lotes)
                    ]);

                    foreach ($lotes as $index => $lote) {
                        try {
                            $actualizados = DispensadoApiMedcol6::where(function ($query) use ($lote) {
                                foreach ($lote as $facturaElec) {
                                    $query->orWhereRaw(
                                        "CONCAT(documento_origen, factura_origen) = ?",
                                        [$facturaElec]
                                    );
                                }
                            })
                                ->whereIn('estado', ['DISPENSADO', 'REVISADO'])
                                ->update([
                                    'estado' => 'ANULADA',
                                    'updated_at' => now()
                                ]);

                            $facturasElectronicasAnuladas += $actualizados;

                            if ($actualizados > 0) {
                                Log::info("Lote de facturas electrónicas procesado", [
                                    'lote' => $index + 1,
                                    'total_lotes' => count($lotes),
                                    'actualizados' => $actualizados,
                                    'facturas_en_lote' => count($lote)
                                ]);
                            }
                        } catch (\Exception $e) {
                            Log::error("Error procesando lote de facturas electrónicas", [
                                'lote' => $index + 1,
                                'error' => $e->getMessage(),
                                'facturas' => $lote
                            ]);
                        }
                    }

                    Log::info('Facturas electrónicas actualizadas', [
                        'total_recibidas' => count($facturasElectronicasNumeros),
                        'lotes_procesados' => count($lotes),
                        'total_anuladas' => $facturasElectronicasAnuladas
                    ]);
                }
            }

            // Cerrar sesión en la API
            try {
                Http::timeout(10)
                    ->withToken($token)
                    ->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/closeallacceso");
            } catch (\Exception $e) {
                Log::warning('Error al cerrar sesión API: ' . $e->getMessage());
            }

            // ✅ Total de facturas anuladas
            $totalAnuladas = $facturasNormalesAnuladas + $facturasElectronicasAnuladas;
            $totalRecibidas = count($todasLasFacturas) + count($todasLasFacturasElectronicas);

            Log::info('Sincronización de facturas anuladas completada', [
                'total_anuladas' => $totalAnuladas,
                'facturas_normales_anuladas' => $facturasNormalesAnuladas,
                'facturas_electronicas_anuladas' => $facturasElectronicasAnuladas,
                'facturas_normales_recibidas' => count($todasLasFacturas),
                'facturas_electronicas_recibidas' => count($todasLasFacturasElectronicas),
                'paginas_procesadas' => $page - 1,
                'usuario' => $usuario
            ]);

            // ✅ Respuesta compacta y legible
            return response()->json([[
                'respuesta' =>
                '<div style="text-align: center; font-family: Arial, sans-serif;">' .
                    '<div style="font-size: 18px; color: #10b981; font-weight: bold; margin-bottom: 12px;">✅ Sincronización Exitosa</div>' .
                    '<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">' .
                    '<div style="font-size: 42px; font-weight: bold; margin-bottom: 8px;">' . $totalAnuladas . '</div>' .
                    '<div style="font-size: 14px; opacity: 0.95; margin-bottom: 15px;">Facturas Anuladas</div>' .
                    '<div style="display: flex; justify-content: space-around; border-top: 1px solid rgba(255,255,255,0.3); padding-top: 12px; margin-top: 12px;">' .
                    '<div style="flex: 1;">' .
                    '<div style="font-size: 24px; font-weight: bold;">' . $facturasNormalesAnuladas . '</div>' .
                    '<div style="font-size: 11px; opacity: 0.9;">Normales</div>' .
                    '</div>' .
                    '<div style="border-left: 1px solid rgba(255,255,255,0.3);"></div>' .
                    '<div style="flex: 1;">' .
                    '<div style="font-size: 24px; font-weight: bold;">' . $facturasElectronicasAnuladas . '</div>' .
                    '<div style="font-size: 11px; opacity: 0.9;">Electrónicas</div>' .
                    '</div>' .
                    '</div>' .
                    '</div>' .
                    '</div>',
                'titulo' => 'Sincronización Completada',
                'icon' => 'success',
                'position' => 'bottom-left',
                'estadisticas' => [
                    'total_anuladas' => $totalAnuladas,
                    'facturas_normales_anuladas' => $facturasNormalesAnuladas,
                    'facturas_electronicas_anuladas' => $facturasElectronicasAnuladas,
                    'total_recibidas' => $totalRecibidas,
                    'paginas_procesadas' => $page - 1
                ]
            ]]);
        } catch (\Exception $e) {
            Log::error('Error en updateanuladosapi', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'usuario' => $usuario ?? 'desconocido'
            ]);

            return response()->json([[
                'respuesta' => 'Error al sincronizar: ' . $e->getMessage(),
                'titulo' => 'Error en Sincronización',
                'icon' => 'error',
                'position' => 'bottom-left'
            ]]);
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
            //->where('estado', 'DISPENSADO')
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
            $dataArray['duracion_tratamiento2'] = '<input type="number" name="duracion_tratamiento2" id="' . $item->id . '" class="show_detail form-control btn bg-info tooltipsC" style="max-width: 85%;" title="Poner la cantidad en días" value="' . $item->duracion_tratamiento . '">';
            $dataArray['autorizacion2'] = '<input type="text" name="autorizacion" id="' . $item->id . '" class="show_detail btn btn-xl bg-warning tooltipsC" style="max-width: 100%;" title="Autorizacion" value="' . $item->autorizacion . '">';
            $dataArray['mipres2'] = '<input type="text" name="mipres" id="' . $item->id . '" class="show_detail btn btn-xl bg-info tooltipsC" style="max-width: 100%;" title="mipres" value="' . $item->mipres . '">';
            $dataArray['reporte_entrega2'] = '<input type="text" name="reporte" id="' . $item->id . '" class="show_detail btn btn-xl bg-info tooltipsC" style="max-width: 100%;" title="Reporte de entrega" value="' . $item->reporte_entrega_nopbs . '">';
            $dataArray['cuota_moderadora2'] = '<input type="text" name="cuota_moderadora" id="' . $item->id . '" class="show_detail form-control btn bg-info tooltipsC" style="max-width: 85%;" title="Cuota Moderadora" value="' . $item->cuota_moderadora_sumada . '">';

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
            'data.*.formula_completa', // Campo formula_completa
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
                        'formula_completa' => trim($idd['formula_completa']),
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

    public function gestionForgif(Request $request)
    {
        $user = Auth::user();
        $i = $user->drogueria ?? "1"; // Valor por defecto

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

        $fechaInicio = $request->filled('fechaini')
            ? Carbon::parse($request->fechaini)->startOfDay()
            : now()->subMonth()->startOfDay();

        $fechaFin = $request->filled('fechafin')
            ? Carbon::parse($request->fechafin)->endOfDay()
            : now()->endOfDay();

        $contrato = $request->input('contrato', null);

        // Subconsulta para traer solo un registro por código
        $subquery = DispensadoApiMedcol6::selectRaw('MIN(id) as id')
            ->where('centroprod', [$contrato])
            ->whereIn('estado', ['DISPENSADO', 'REVISADO'])
            ->whereBetween('fecha_suministro', [$fechaInicio, $fechaFin])
            ->whereNotIn('codigo', ['1010', '1011', '1012'])
            ->groupBy('codigo');

        $queryBase = DispensadoApiMedcol6::select([
            'expediente',
            'codigo',
            'nombre_generico',
            'nombre_comercial',
            'precio_unitario',
            'cums',
            'ambito',
            'cobertura',
            'forma'
        ])
            ->whereIn('id', $subquery)
            ->orderBy('fecha_suministro', 'asc');

        if ($i !== "1" && $drogueria) {
            $queryBase->where('centroprod', $drogueria);
        }

        // Paginación de DataTables
        $totalRegistros = $queryBase->count();
        $data = $queryBase->skip($request->start)->take($request->length)->get();
        //$data = $queryBase->limit($request->length)->offset($request->start)->get();

        // Agregar los valores por defecto
        $resultados = $data->map(function ($item) {
            return array_merge($item->toArray(), [
                'nit_prestador' => 901601000,
                'razon_social_prestador' => 'SALUD MEDCOL SAS',
                'registro_sanitario_invima' => 'invima-12345',
                'unidad_medicamento' => 1,
                'codigo_generico_eps' => 'NA',
                'opcion' => 1,
                'regulado' => 'NA',
                'categoria_medicamento' => 'M/I',
                'tarifa_tope_regulado' => 0
            ]);
        });

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $totalRegistros,
            "recordsFiltered" => $totalRegistros,
            "data" => $resultados
        ]);
    }

    // funcion para consultar la api de dispendaso unico:
    
    public function createdispensadoapiunico(Request $request)
    {
        
        
        $factura = $request->factura;
        $email = 'castrokofdev@gmail.com'; // Auth::user()->email
        $password = 'colMed2023**';
        $usuario = Auth::user()->email;
        
          set_time_limit(0);
          ini_set('memory_limit', '512M');


        try {


            $response = Http::timeout(3)->post("http://hed08pf9dxt.sn.mynetname.net:8004/api/acceso", [
                'email' =>  $email,
                'password' => $password,
            ]);

            $token = $response->json()["token"];

           

            if ($token) {


                try {

                 
            $responsefacturas = Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/dispensadoapiunico",['factura' => $factura]);
            
          
            $facturassapi = $responsefacturas->json()['data'];
    
            $contadorei = 0;
            $contador = 0;
            
           
            // Obtener las facturas existentes en un solo query
            
             // Crear las claves únicas de cada entrada en $facturassapi
                $clavesFacturasApi = array_map(function ($f) {
                    return trim($f['factura']) . '-' . trim($f['codigo']) . '-' . trim($f['ID_REGISTRO']);
                }, $facturassapi);
            
                $facturasExistentes = collect();
            
                // Consultar la base de datos en chunks para evitar demasiados placeholders
                foreach (array_chunk($facturassapi, 50) as $chunk) {
                    $facturas = array_map(fn($f) => trim($f['factura']), $chunk);
                    $codigos = array_map(fn($f) => trim($f['codigo']), $chunk);
                    $ids = array_map(fn($f) => trim($f['ID_REGISTRO']), $chunk);
            
                    $resultados = DispensadoApiMedcol6::select('factura', 'codigo', 'ID_REGISTRO')
                        ->whereIn('factura', $facturas)
                        ->whereIn('codigo', $codigos)
                        ->whereIn('ID_REGISTRO', $ids)
                        ->get();
            
                    $facturasExistentes = $facturasExistentes->merge($resultados);
                }
            
                // Crear claves únicas de los existentes en base de datos
                $facturasExistentesFlip = array_flip(
                    $facturasExistentes->map(function ($item) {
                        return trim($item->factura) . '-' . trim($item->codigo) . '-' . trim($item->ID_REGISTRO);
                    })->toArray()
                );
            
                $dispensados = [];
             
               foreach ($facturassapi as $factura) {
                
                // Verificar si la factura ya existe en el array obtenido antes
                $clave = trim($factura['factura']) . '-' . trim($factura['codigo']) . '-' . trim($factura['ID_REGISTRO']);
               
               
                
                 if (isset($facturasExistentesFlip[$clave])) {
                        // Registrar en el log como "NO" (porque ya existe)
                        Log::info("{$clave} => NO (ya existe) Unico");
                        continue;
                    }
                
                    // Registrar en el log como "SI" (porque se va a insertar)
                    Log::info("{$clave} => SI (se inserta) Unico");
                
                

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
                                'numeroIdentificacion'  => trim($factura['numeroIdentificacion']),
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
                    $chunks = array_chunk($dispensados, 50); // Divide en lotes de 500 registros
                    
                    
                    foreach ($chunks as $chunk) {
                        DispensadoApiMedcol6::insertOrIgnore($chunk);
                    }
                }


                    
                    Http::withToken($token)->get("http://hed08pf9dxt.sn.mynetname.net:8004/api/closeallacceso");

                    Log::info('Desde la web syncapi centralizado unicos' . $contador . ' Lineas dispensadas' . ' Usuario: ' . $usuario);

                    return response()->json([
                        ['respuesta' => $contador . ' Lineas creadas', 'titulo' => 'Mixed lineas', 'icon' => 'success', 'position' => 'bottom-left']
                    ]);
                    
                } catch (\Exception $e) {

                    // Manejo de la excepción
                    Log::error('Error en la sincronización SERVER IP FIJA: '.$e->getMessage()); // Registrar el error en los logs de Laravel

                    return response()->json([
                        ['respuesta' => 'Error: ' . $e->getMessage(), 'titulo' => 'Error', 'icon' => 'error', 'position' => 'bottom-left']
                    ]);
                }
            }
        } catch (\Exception $e) {



            try {

                $factura = $request->factura;
                $response = Http::timeout(3)->post("http://192.168.66.95:8004/api/acceso", [
                    'email' =>  $email,
                    'password' => $password,
                ]);

                $token = $response->json()["token"];

                if ($token) {

                    try {


                       

                        $responsefacturas = Http::withToken($token)->get("http://192.168.66.95:8004/api/dispensadoapiunico", ['factura' => $factura]);

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
                                    'numeroIdentificacion'  => trim($factura['numeroIdentificacion']),
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

                        Http::withToken($token)->get("http://192.168.66.95:8004/api/closeallacceso");


                        Log::info('Desde la web syncapi centralizado local unico' . $contador . ' Lineas dispensadas' . ' Usuario: ' . $usuario);

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
}
