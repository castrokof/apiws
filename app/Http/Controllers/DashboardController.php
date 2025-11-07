<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medcol6\DispensadoApiMedcol6;
use App\Models\Medcol6\PendienteApiMedcol6;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Aumentar el tiempo límite de ejecución para consultas pesadas
        set_time_limit(180); // 3 minutos
        ini_set('memory_limit', '512M'); // Aumentar límite de memoria
    }

    public function index(Request $request)
    {
        // Cache contratos por 1 hora
        $contratos = Cache::remember('dashboard_contratos', 3600, function () {
            return DispensadoApiMedcol6::select('centroprod')
                ->distinct()
                ->orderBy('centroprod')
                ->pluck('centroprod');
        });

        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', Carbon::now()->format('Y-m-d'));
        $contrato = $request->get('contrato', 'all');

        // No cargar datos inicialmente - solo la estructura del dashboard
        return view('dashboard.index', compact('contratos', 'fechaInicio', 'fechaFin', 'contrato'));
    }

    /**
     * Cargar solo estadísticas básicas para la vista inicial
     */
    private function getEstadisticasBasicas($fechaInicio, $fechaFin, $contrato)
    {
        $cacheKey = "estadisticas_basicas_{$fechaInicio}_{$fechaFin}_{$contrato}";

        return Cache::remember($cacheKey, 1800, function () use ($fechaInicio, $fechaFin, $contrato) {
            $baseQuery = DispensadoApiMedcol6::whereBetween('fecha_suministro', [$fechaInicio, $fechaFin])
                ->whereIn('estado', ['DISPENSADO', 'REVISADO']);

            if ($contrato !== 'all') {
                $baseQuery->where('centroprod', $contrato);
            }

            // Solo estadísticas críticas para carga rápida
            $totalPacientes = $baseQuery->distinct('historia')->count('historia');
            $valorTotal = $baseQuery->sum(DB::raw('CAST(REPLACE(valor_total, ",", "") as DECIMAL(15,2))'));

            return [
                'total_pacientes' => $totalPacientes,
                'valor_total_facturado' => $valorTotal,
                'loading' => true // Indica que hay más datos por cargar
            ];
        });
    }

    public function getEstadisticas($fechaInicio, $fechaFin, $contrato)
    {
        // Crear clave única para caché basada en parámetros
        $cacheKey = "dispensados_estadisticas_{$fechaInicio}_{$fechaFin}_{$contrato}";

        // Intentar obtener del caché (válido por 10 minutos)
        return Cache::remember($cacheKey, 600, function () use ($fechaInicio, $fechaFin, $contrato) {
            $baseQuery = function() use ($fechaInicio, $fechaFin, $contrato) {
                $query = DispensadoApiMedcol6::whereBetween('fecha_suministro', [$fechaInicio, $fechaFin])
                ->whereIn('estado', ['DISPENSADO', 'REVISADO']);
                if ($contrato !== 'all') {
                    $query->where('centroprod', $contrato);
                }
                return $query;
            };

        $totalPacientes = $baseQuery()->distinct('historia')->count('historia');

        $valorTotalFacturado = $baseQuery()->sum(DB::raw('CAST(REPLACE(valor_total, ",", "") as DECIMAL(15,2))'));

        $pacienteMayorValor = $baseQuery()
            ->select('paciente', 'historia', DB::raw('SUM(CAST(REPLACE(valor_total, ",", "") as DECIMAL(15,2))) as total_paciente'))
            ->groupBy('historia', 'paciente')
            ->orderBy('total_paciente', 'desc')
            ->first();

        $topMedicamentos = $baseQuery()
            ->select('nombre_generico', DB::raw('SUM(CAST(REPLACE(valor_total, ",", "") as DECIMAL(15,2))) as total_medicamento'), DB::raw('SUM(CAST(REPLACE(numero_unidades, ",", "") as DECIMAL(15,2))) as total_unidades'))
            ->groupBy('nombre_generico')
            ->orderBy('total_medicamento', 'desc')
            ->limit(10)
            ->get();

        $facturasPorMes = $baseQuery()
            ->select(DB::raw('MONTH(fecha_suministro) as mes'), DB::raw('YEAR(fecha_suministro) as año'), DB::raw('SUM(CAST(REPLACE(valor_total, ",", "") as DECIMAL(15,2))) as total_mes'))
            ->groupBy(DB::raw('YEAR(fecha_suministro)'), DB::raw('MONTH(fecha_suministro)'))
            ->orderBy(DB::raw('YEAR(fecha_suministro)'))
            ->orderBy(DB::raw('MONTH(fecha_suministro)'))
            ->get();

        $pacientesPorContrato = DispensadoApiMedcol6::select('centroprod', DB::raw('COUNT(DISTINCT historia) as total_pacientes'))
            ->whereBetween('fecha_suministro', [$fechaInicio, $fechaFin])
            ->when($contrato !== 'all', function($q) use ($contrato) {
                return $q->where('centroprod', $contrato);
            })
            ->groupBy('centroprod')
            ->get();

            return [
                'total_pacientes' => $totalPacientes,
                'valor_total_facturado' => $valorTotalFacturado,
                'paciente_mayor_valor' => $pacienteMayorValor,
                'top_medicamentos' => $topMedicamentos,
                'facturas_por_mes' => $facturasPorMes,
                'pacientes_por_contrato' => $pacientesPorContrato
            ];
        });
    }

    public function getEstadisticasMedcol6($fechaInicio, $fechaFin)
    {
        // Crear clave única para caché basada en fechas
        $cacheKey = "medcol6_estadisticas_{$fechaInicio}_{$fechaFin}";

        // Aumentar tiempo de caché a 1 hora (3600 segundos) para reducir carga
        return Cache::remember($cacheKey, 3600, function () use ($fechaInicio, $fechaFin) {
            // Crear tabla temporal con el precio más reciente por código (UN SOLO registro por código)
            DB::statement('DROP TEMPORARY TABLE IF EXISTS temp_precios_ultimo');
            DB::statement('
                CREATE TEMPORARY TABLE temp_precios_ultimo AS
                SELECT
                    t1.codigo,
                    CAST(REPLACE(REPLACE(t1.costo_unitario, ",", ""), "$", "") as DECIMAL(15,2)) as precio_ultimo
                FROM saldos_medcol6 t1
                INNER JOIN (
                    SELECT codigo, MAX(updated_at) as max_updated
                    FROM saldos_medcol6
                    WHERE costo_unitario IS NOT NULL
                    AND costo_unitario != ""
                    AND costo_unitario != "0"
                    AND costo_unitario != "$0"
                    GROUP BY codigo
                ) t2 ON t1.codigo = t2.codigo AND t1.updated_at = t2.max_updated
            ');

            // Crear índice en la tabla temporal para mejorar JOINs
            DB::statement('CREATE INDEX idx_temp_codigo ON temp_precios_ultimo(codigo)');

            // Calcular estadísticas por estado UNA SOLA VEZ y reutilizar
            $estadisticasPorEstado = $this->getEstadisticasPorEstadoOptimizado($fechaInicio, $fechaFin);

            // Extraer valor entregado de las estadísticas ya calculadas (evitar query duplicado)
            $valorTotalEntregado = 0;
            foreach ($estadisticasPorEstado as $stat) {
                if (strtoupper($stat['estado']) === 'ENTREGADO' ||
                    strtoupper($stat['estado']) === 'ENTREGADOS') {
                    $valorTotalEntregado = floatval($stat['valor_total']);
                    break;
                }
            }

            // Valor total pendiente por facturar
            $valorTotalPendiente = $this->getValorPendientePorFacturarOptimizado($fechaInicio, $fechaFin);

            // Top medicamentos pendientes por valor
            $topMedicamentosPendientes = $this->getTopMedicamentosPendientesOptimizado($fechaInicio, $fechaFin, 10);

            // Tendencias por mes (simplificado, sin calcular valores monetarios)
            $tendenciasPorMes = $this->getTendenciasPorMesSimplificado($fechaInicio, $fechaFin);

            // Limpiar tabla temporal
            DB::statement('DROP TEMPORARY TABLE IF EXISTS temp_precios_ultimo');

            return [
                'estadisticas_por_estado' => $estadisticasPorEstado,
                'valor_total_pendiente' => $valorTotalPendiente,
                'valor_total_entregado' => $valorTotalEntregado,
                'top_medicamentos_pendientes' => $topMedicamentosPendientes,
                'tendencias_por_mes' => $tendenciasPorMes
            ];
        });
    }

    private function getEstadisticasPorEstadoOptimizado($fechaInicio, $fechaFin)
    {
        // Optimización: Usar tabla temporal con el último precio
        $estadisticas = DB::table('pendiente_api_medcol6 as p')
            ->leftJoin('temp_precios_ultimo as d', 'p.codigo', '=', 'd.codigo')
            ->whereBetween('p.fecha', [$fechaInicio, $fechaFin])
            ->select(
                'p.estado',
                DB::raw('COUNT(*) as total_pendientes'),
                DB::raw('SUM(CAST(p.cantord as DECIMAL(10,2))) as total_cantidad'),
                DB::raw('SUM(CAST(p.cantord as DECIMAL(10,2)) * CAST(COALESCE(d.precio_ultimo, 0) as DECIMAL(10,2))) as valor_total')
            )
            ->groupBy('p.estado')
            ->get()
            ->toArray();

        return array_map(function($stat) {
            return [
                'estado' => $stat->estado,
                'total_pendientes' => $stat->total_pendientes,
                'total_cantidad' => $stat->total_cantidad,
                'valor_total' => $stat->valor_total ?? 0
            ];
        }, $estadisticas);
    }

    private function getEstadisticasPorEstado($fechaInicio, $fechaFin)
    {
        // Optimización: Usar subconsulta para obtener precio promedio por código
        // Esto es más eficiente que hacer LEFT JOIN en cada registro
        $estadisticas = DB::table('pendiente_api_medcol6 as p')
            ->leftJoin(
                DB::raw('(SELECT codigo, AVG(CAST(REPLACE(precio_unitario, ",", "") as DECIMAL(15,2))) as precio_promedio
                         FROM dispensado_medcol6
                         GROUP BY codigo) as d'),
                'p.codigo',
                '=',
                'd.codigo'
            )
            ->whereBetween('p.fecha', [$fechaInicio, $fechaFin])
            ->select(
                'p.estado',
                DB::raw('COUNT(*) as total_pendientes'),
                DB::raw('SUM(CAST(p.cantord as DECIMAL(10,2))) as total_cantidad'),
                DB::raw('SUM(CAST(p.cantord as DECIMAL(10,2)) * CAST(COALESCE(d.precio_promedio, 0) as DECIMAL(10,2))) as valor_total')
            )
            ->groupBy('p.estado')
            ->get()
            ->toArray();

        return array_map(function($stat) {
            return [
                'estado' => $stat->estado,
                'total_pendientes' => $stat->total_pendientes,
                'total_cantidad' => $stat->total_cantidad,
                'valor_total' => $stat->valor_total ?? 0
            ];
        }, $estadisticas);
    }


    private function getValorPendientePorFacturarOptimizado($fechaInicio, $fechaFin)
    {
        // Optimización: Usar tabla temporal con el último precio
        $resultado = DB::table('pendiente_api_medcol6 as p')
            ->leftJoin('temp_precios_ultimo as d', 'p.codigo', '=', 'd.codigo')
            ->whereIn('p.estado', ['PENDIENTE', 'DESABASTECIDO', 'SIN CONTACTO', 'TRAMITADO', 'VENCIDO'])
            ->whereBetween('p.fecha', [$fechaInicio, $fechaFin])
            ->select(DB::raw('SUM(CAST(p.cantord as DECIMAL(10,2)) * CAST(COALESCE(d.precio_ultimo, 0) as DECIMAL(10,2))) as valor_total'))
            ->value('valor_total');

        return $resultado ?? 0;
    }

    private function getValorPendientePorFacturar($fechaInicio, $fechaFin)
    {
        // Optimización: Usar subconsulta para obtener precio promedio por código
        $resultado = DB::table('pendiente_api_medcol6 as p')
            ->leftJoin(
                DB::raw('(SELECT codigo, AVG(CAST(REPLACE(precio_unitario, ",", "") as DECIMAL(15,2))) as precio_promedio
                         FROM dispensado_medcol6
                         GROUP BY codigo) as d'),
                'p.codigo',
                '=',
                'd.codigo'
            )
            ->whereIn('p.estado', ['PENDIENTE', 'DESABASTECIDO', 'SIN CONTACTO', 'TRAMITADO', 'VENCIDO'])
            ->whereBetween('p.fecha', [$fechaInicio, $fechaFin])
            ->select(DB::raw('SUM(CAST(p.cantord as DECIMAL(10,2)) * CAST(COALESCE(d.precio_promedio, 0) as DECIMAL(10,2))) as valor_total'))
            ->value('valor_total');

        return $resultado ?? 0;
    }

    private function getValorEntregadoOptimizado($fechaInicio, $fechaFin)
    {
        // DEPRECATED: Esta función ya no se usa.
        // El valor entregado se extrae directamente de las estadísticas por estado
        // para evitar duplicar queries. Ver getEstadisticasMedcol6()

        // Buscar el valor total entregado desde las estadísticas por estado
        // ya calculadas, buscando el estado ENTREGADO
        $estadisticas = $this->getEstadisticasPorEstadoOptimizado($fechaInicio, $fechaFin);

        // Buscar el estado que represente entregados
        $valorEntregado = 0;
        foreach ($estadisticas as $stat) {
            if (strtoupper($stat['estado']) === 'ENTREGADO' ||
                strtoupper($stat['estado']) === 'ENTREGADOS') {
                $valorEntregado = floatval($stat['valor_total']);
                break;
            }
        }

        return $valorEntregado;
    }

    private function getValorEntregado($fechaInicio, $fechaFin)
    {
        // Optimización: Usar subconsulta para obtener precio promedio por código
        $resultado = DB::table('pendiente_api_medcol6 as p')
            ->leftJoin(
                DB::raw('(SELECT codigo, AVG(CAST(REPLACE(precio_unitario, ",", "") as DECIMAL(15,2))) as precio_promedio
                         FROM dispensado_medcol6
                         GROUP BY codigo) as d'),
                'p.codigo',
                '=',
                'd.codigo'
            )
            ->where('p.estado', 'ENTREGADO')
            ->whereBetween('p.fecha', [$fechaInicio, $fechaFin])
            ->select(DB::raw('SUM(CAST(p.cantord as DECIMAL(10,2)) * CAST(COALESCE(d.precio_promedio, 0) as DECIMAL(10,2))) as valor_total'))
            ->value('valor_total');

        return $resultado ?? 0;
    }

    private function getTopMedicamentosPendientesOptimizado($fechaInicio, $fechaFin, $limit = 10)
    {
        // Optimización: Usar tabla temporal con el último precio
        $query = DB::table('pendiente_api_medcol6 as p')
            ->leftJoin('temp_precios_ultimo as d', 'p.codigo', '=', 'd.codigo')
            ->whereBetween('p.fecha', [$fechaInicio, $fechaFin])
            ->select(
                'p.codigo',
                'p.nombre',
                DB::raw('SUM(CAST(p.cantord as DECIMAL(10,2))) as total_cantidad'),
                DB::raw('COUNT(*) as total_pendientes'),
                DB::raw('SUM(CAST(p.cantord as DECIMAL(10,2)) * CAST(COALESCE(d.precio_ultimo, 0) as DECIMAL(10,2))) as valor_total')
            )
            ->groupBy('p.codigo', 'p.nombre')
            ->orderBy('valor_total', 'desc');

        // Solo aplicar límite si se especifica
        if ($limit !== null) {
            $query->limit($limit);
        }

        $medicamentos = $query->get()->toArray();

        return array_map(function($med) {
            return [
                'codigo' => $med->codigo,
                'nombre' => $med->nombre,
                'total_cantidad' => $med->total_cantidad,
                'total_pendientes' => $med->total_pendientes,
                'valor_total' => $med->valor_total ?? 0
            ];
        }, $medicamentos);
    }

    private function getTopMedicamentosPendientes($fechaInicio, $fechaFin, $limit = 10)
    {
        // Optimización: Usar subconsulta para obtener precio promedio por código
        $medicamentos = DB::table('pendiente_api_medcol6 as p')
            ->leftJoin(
                DB::raw('(SELECT codigo, AVG(CAST(REPLACE(precio_unitario, ",", "") as DECIMAL(15,2))) as precio_promedio
                         FROM dispensado_medcol6
                         GROUP BY codigo) as d'),
                'p.codigo',
                '=',
                'd.codigo'
            )
            ->whereBetween('p.fecha', [$fechaInicio, $fechaFin])
            ->select(
                'p.codigo',
                'p.nombre',
                DB::raw('SUM(CAST(p.cantord as DECIMAL(10,2))) as total_cantidad'),
                DB::raw('COUNT(*) as total_pendientes'),
                DB::raw('SUM(CAST(p.cantord as DECIMAL(10,2)) * CAST(COALESCE(d.precio_promedio, 0) as DECIMAL(10,2))) as valor_total')
            )
            ->groupBy('p.codigo', 'p.nombre')
            ->orderBy('valor_total', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();

        return array_map(function($med) {
            return [
                'codigo' => $med->codigo,
                'nombre' => $med->nombre,
                'total_cantidad' => $med->total_cantidad,
                'total_pendientes' => $med->total_pendientes,
                'valor_total' => $med->valor_total ?? 0
            ];
        }, $medicamentos);
    }

    private function getTendenciasPorMes($fechaInicio, $fechaFin)
    {
        // Optimización: Usar índice compuesto (fecha, estado) si existe
        $tendencias = PendienteApiMedcol6::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->select(
                DB::raw('YEAR(fecha) as año'),
                DB::raw('MONTH(fecha) as mes'),
                'estado',
                DB::raw('COUNT(*) as total_pendientes')
            )
            ->groupBy(DB::raw('YEAR(fecha)'), DB::raw('MONTH(fecha)'), 'estado')
            ->orderBy(DB::raw('YEAR(fecha)'))
            ->orderBy(DB::raw('MONTH(fecha)'))
            ->get();

        // Si no hay datos, retornar array vacío
        if ($tendencias->isEmpty()) {
            return [];
        }

        $tendenciasAgrupadas = [];

        foreach ($tendencias as $tendencia) {
            $key = $tendencia->año . '-' . str_pad($tendencia->mes, 2, '0', STR_PAD_LEFT);

            if (!isset($tendenciasAgrupadas[$key])) {
                $tendenciasAgrupadas[$key] = [
                    'año' => (int)$tendencia->año,
                    'mes' => (int)$tendencia->mes,
                    'estados' => []
                ];
            }

            $tendenciasAgrupadas[$key]['estados'][$tendencia->estado] = (int)$tendencia->total_pendientes;
        }

        return array_values($tendenciasAgrupadas);
    }

    /**
     * Versión simplificada de tendencias por mes (solo conteos, sin valores monetarios)
     * Mejora significativa de rendimiento al evitar JOINs con tabla de precios
     */
    private function getTendenciasPorMesSimplificado($fechaInicio, $fechaFin)
    {
        // Query altamente optimizada: solo conteo de registros agrupados
        $tendencias = DB::table('pendiente_api_medcol6')
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->select(
                DB::raw('YEAR(fecha) as año'),
                DB::raw('MONTH(fecha) as mes'),
                'estado',
                DB::raw('COUNT(*) as total_pendientes')
            )
            ->groupBy(DB::raw('YEAR(fecha)'), DB::raw('MONTH(fecha)'), 'estado')
            ->orderBy(DB::raw('YEAR(fecha)'))
            ->orderBy(DB::raw('MONTH(fecha)'))
            ->get();

        // Si no hay datos, retornar array vacío
        if ($tendencias->isEmpty()) {
            return [];
        }

        $tendenciasAgrupadas = [];

        foreach ($tendencias as $tendencia) {
            $key = $tendencia->año . '-' . str_pad($tendencia->mes, 2, '0', STR_PAD_LEFT);

            if (!isset($tendenciasAgrupadas[$key])) {
                $tendenciasAgrupadas[$key] = [
                    'año' => (int)$tendencia->año,
                    'mes' => (int)$tendencia->mes,
                    'estados' => []
                ];
            }

            $tendenciasAgrupadas[$key]['estados'][$tendencia->estado] = (int)$tendencia->total_pendientes;
        }

        return array_values($tendenciasAgrupadas);
    }

    public function getEstadisticasAjax(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $contrato = $request->get('contrato', 'all');

        $estadisticas = $this->getEstadisticas($fechaInicio, $fechaFin, $contrato);
        $estadisticasMedcol6 = $this->getEstadisticasMedcol6($fechaInicio, $fechaFin);

        return response()->json([
            'dispensados' => $estadisticas,
            'medcol6' => $estadisticasMedcol6
        ]);
    }

    public function getTopMedicamentosDataTable(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $contrato = $request->get('contrato', 'all');

        $query = DispensadoApiMedcol6::whereBetween('fecha_suministro', [$fechaInicio, $fechaFin]);

        if ($contrato !== 'all') {
            $query->where('centroprod', $contrato);
        }

        $medicamentos = $query
            ->select('nombre_generico',
                    DB::raw('SUM(CAST(REPLACE(valor_total, ",", "") as DECIMAL(15,2))) as total_medicamento'),
                    DB::raw('SUM(CAST(REPLACE(numero_unidades, ",", "") as DECIMAL(15,2))) as total_unidades'),
                    DB::raw('COUNT(*) as total_dispensaciones'))
            ->groupBy('nombre_generico')
            ->orderBy('total_medicamento', 'desc')
            ->get();

        return datatables($medicamentos)
            ->editColumn('total_medicamento', function($row) {
                return '$' . number_format($row->total_medicamento, 2);
            })
            ->editColumn('total_unidades', function($row) {
                return number_format($row->total_unidades, 0);
            })
            ->editColumn('total_dispensaciones', function($row) {
                return number_format($row->total_dispensaciones, 0);
            })
            ->make(true);
    }

    /**
     * Limpiar caché de estadísticas (útil cuando se actualizan datos)
     */
    public function limpiarCache()
    {
        // Limpiar todos los cachés relacionados con estadísticas
        Cache::flush(); // Limpia todo el caché (usar con cuidado en producción)

        // Alternativamente, se pueden limpiar cachés específicos:
        // Cache::forget('medcol6_estadisticas_*');
        // Cache::forget('dispensados_estadisticas_*');

        return response()->json(['message' => 'Caché limpiado exitosamente']);
    }

    /**
     * Obtener estadísticas optimizadas con manejo de errores
     */
    public function getEstadisticasSeguras(Request $request)
    {
        try {
            $fechaInicio = $request->get('fecha_inicio');
            $fechaFin = $request->get('fecha_fin');
            $contrato = $request->get('contrato', 'all');

            // Validar fechas
            if (!$fechaInicio || !$fechaFin) {
                return response()->json(['error' => 'Fechas requeridas'], 400);
            }

            // Limitar el rango de fechas para evitar consultas muy pesadas
            $inicio = Carbon::parse($fechaInicio);
            $fin = Carbon::parse($fechaFin);

            if ($fin->diffInDays($inicio) > 365) {
                return response()->json(['error' => 'Rango de fechas muy amplio. Máximo 365 días.'], 400);
            }

            $estadisticas = $this->getEstadisticas($fechaInicio, $fechaFin, $contrato);
            $estadisticasMedcol6 = $this->getEstadisticasMedcol6($fechaInicio, $fechaFin);

            return response()->json([
                'dispensados' => $estadisticas,
                'medcol6' => $estadisticasMedcol6
            ]);

        } catch (\Exception $e) {
            // Log del error para debugging
            \Log::error('Error en estadísticas dashboard: ' . $e->getMessage());

            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => 'Por favor, intente con un rango de fechas menor o contacte al administrador'
            ], 500);
        }
    }

    /**
     * Endpoint específico para gráfica de facturación por mes
     */
    public function getFacturacionMensual(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $contrato = $request->get('contrato', 'all');

        $cacheKey = "facturacion_mensual_{$fechaInicio}_{$fechaFin}_{$contrato}";

        $data = Cache::remember($cacheKey, 1800, function () use ($fechaInicio, $fechaFin, $contrato) {
            $query = DispensadoApiMedcol6::whereBetween('fecha_suministro', [$fechaInicio, $fechaFin])
                ->whereIn('estado', ['DISPENSADO', 'REVISADO']);

            if ($contrato !== 'all') {
                $query->where('centroprod', $contrato);
            }

            return $query->select(
                    DB::raw('MONTH(fecha_suministro) as mes'),
                    DB::raw('YEAR(fecha_suministro) as año'),
                    DB::raw('SUM(CAST(REPLACE(valor_total, ",", "") as DECIMAL(15,2))) as total_mes')
                )
                ->groupBy(DB::raw('YEAR(fecha_suministro)'), DB::raw('MONTH(fecha_suministro)'))
                ->orderBy(DB::raw('YEAR(fecha_suministro)'))
                ->orderBy(DB::raw('MONTH(fecha_suministro)'))
                ->get();
        });

        return response()->json($data);
    }

    /**
     * Endpoint específico para distribución por contrato
     */
    public function getPacientesPorContrato(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $contrato = $request->get('contrato', 'all');

        $cacheKey = "pacientes_contrato_{$fechaInicio}_{$fechaFin}_{$contrato}";

        $data = Cache::remember($cacheKey, 1800, function () use ($fechaInicio, $fechaFin, $contrato) {
            $query = DispensadoApiMedcol6::whereBetween('fecha_suministro', [$fechaInicio, $fechaFin])
                ->whereIn('estado', ['DISPENSADO', 'REVISADO']);

            if ($contrato !== 'all') {
                $query->where('centroprod', $contrato);
            }

            return $query->select('centroprod', DB::raw('COUNT(DISTINCT historia) as total_pacientes'))
                ->groupBy('centroprod')
                ->get();
        });

        return response()->json($data);
    }

    /**
     * Endpoint específico para top medicamentos
     */
    public function getTopMedicamentos(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $contrato = $request->get('contrato', 'all');
        $limit = $request->get('limit', 10);

        $cacheKey = "top_medicamentos_{$fechaInicio}_{$fechaFin}_{$contrato}_{$limit}";

        $data = Cache::remember($cacheKey, 1800, function () use ($fechaInicio, $fechaFin, $contrato, $limit) {
            $query = DispensadoApiMedcol6::whereBetween('fecha_suministro', [$fechaInicio, $fechaFin])
                ->whereIn('estado', ['DISPENSADO', 'REVISADO']);

            if ($contrato !== 'all') {
                $query->where('centroprod', $contrato);
            }

            return $query->select(
                    'nombre_generico',
                    DB::raw('SUM(CAST(REPLACE(valor_total, ",", "") as DECIMAL(15,2))) as total_medicamento'),
                    DB::raw('SUM(CAST(REPLACE(numero_unidades, ",", "") as DECIMAL(15,2))) as total_unidades')
                )
                ->groupBy('nombre_generico')
                ->orderBy('total_medicamento', 'desc')
                ->limit($limit)
                ->get();
        });

        return response()->json($data);
    }

    /**
     * Endpoint específico para estadísticas de Medcol6
     */
    public function getEstadisticasMedcol6Ajax(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $data = $this->getEstadisticasMedcol6($fechaInicio, $fechaFin);

        return response()->json($data);
    }

    /**
     * Endpoint específico para resumen general del dashboard
     */
    public function getResumenGeneral(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $contrato = $request->get('contrato', 'all');

        $cacheKey = "resumen_general_{$fechaInicio}_{$fechaFin}_{$contrato}";

        $data = Cache::remember($cacheKey, 1800, function () use ($fechaInicio, $fechaFin, $contrato) {
            // Función que crea una nueva query cada vez que se llama
            $baseQuery = function() use ($fechaInicio, $fechaFin, $contrato) {
                $query = DispensadoApiMedcol6::whereBetween('fecha_suministro', [$fechaInicio, $fechaFin])
                    ->whereIn('estado', ['DISPENSADO', 'REVISADO']);

                if ($contrato !== 'all') {
                    $query->where('centroprod', $contrato);
                }

                return $query;
            };

            // Cada llamada crea una nueva instancia de la query
            $totalPacientes = $baseQuery()->distinct('historia')->count('historia');

            // Cálculo del valor total usando analisis_nt cuando aplique
            $valorTotal = DB::table('dispensado_medcol6 as d')
                ->leftJoin('analisis_nt as a', function($join) {
                    $join->on('d.codigo', '=', 'a.codigo_medcol')
                         ->on('d.centroprod', '=', 'a.contrato');
                })
                ->whereBetween('d.fecha_suministro', [$fechaInicio, $fechaFin])
                ->whereIn('d.estado', ['DISPENSADO', 'REVISADO'])
                ->when($contrato !== 'all', function($query) use ($contrato) {
                    return $query->where('d.centroprod', $contrato);
                })
                ->sum(DB::raw('
                    CASE
                        WHEN a.valor_unitario IS NOT NULL AND a.valor_unitario > 0 THEN a.valor_unitario * d.numero_unidades
                        WHEN d.precio_unitario IS NOT NULL AND d.precio_unitario > 0 THEN d.precio_unitario * d.numero_unidades
                        ELSE CAST(REPLACE(d.valor_total, ",", "") as DECIMAL(15,2))
                    END
                '));

            $totalMedicamentos = $baseQuery()->distinct('nombre_generico')->count('nombre_generico');

            // Paciente con mayor valor usando analisis_nt cuando aplique
            $pacienteMayorValor = DB::table('dispensado_medcol6 as d')
                ->leftJoin('analisis_nt as a', function($join) {
                    $join->on('d.codigo', '=', 'a.codigo_medcol')
                         ->on('d.centroprod', '=', 'a.contrato');
                })
                ->whereBetween('d.fecha_suministro', [$fechaInicio, $fechaFin])
                ->whereIn('d.estado', ['DISPENSADO', 'REVISADO'])
                ->when($contrato !== 'all', function($query) use ($contrato) {
                    return $query->where('d.centroprod', $contrato);
                })
                ->select(
                    'd.paciente',
                    'd.historia',
                    DB::raw('SUM(
                        CASE
                            WHEN a.valor_unitario IS NOT NULL AND a.valor_unitario > 0
                            THEN a.valor_unitario * d.numero_unidades
                            WHEN d.precio_unitario IS NOT NULL AND d.precio_unitario > 0
                            THEN d.precio_unitario * d.numero_unidades
                            ELSE CAST(REPLACE(d.valor_total, ",", "") as DECIMAL(15,2))
                        END
                    ) as total_paciente')
                )
                ->groupBy('d.historia', 'd.paciente')
                ->orderBy('total_paciente', 'desc')
                ->first();

            return [
                'total_pacientes' => $totalPacientes,
                'valor_total_facturado' => $valorTotal,
                'total_medicamentos' => $totalMedicamentos,
                'paciente_mayor_valor' => $pacienteMayorValor
            ];
        });

        return response()->json($data);
    }

    /**
     * Endpoint específico para estadísticas de pendientes Medcol6
     */
    public function getResumenPendientes(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $data = $this->getEstadisticasMedcol6($fechaInicio, $fechaFin);

        $resumen = [
            'valor_total_pendiente' => $data['valor_total_pendiente'],
            'valor_total_entregado' => $data['valor_total_entregado'],
            'estadisticas_por_estado' => $data['estadisticas_por_estado']
        ];

        return response()->json($resumen);
    }

    /**
     * Endpoint específico para paciente con mayor valor
     */
    public function getPacienteMayorValor(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $contrato = $request->get('contrato', 'all');

        $cacheKey = "paciente_mayor_valor_{$fechaInicio}_{$fechaFin}_{$contrato}";

        $data = Cache::remember($cacheKey, 1800, function () use ($fechaInicio, $fechaFin, $contrato) {
            $query = DispensadoApiMedcol6::whereBetween('fecha_suministro', [$fechaInicio, $fechaFin])
                ->whereIn('estado', ['DISPENSADO', 'REVISADO']);

            if ($contrato !== 'all') {
                $query->where('centroprod', $contrato);
            }

            return $query->select(
                    'paciente',
                    'historia',
                    DB::raw('SUM(CAST(REPLACE(valor_total, ",", "") as DECIMAL(15,2))) as total_paciente')
                )
                ->groupBy('historia', 'paciente')
                ->orderBy('total_paciente', 'desc')
                ->first();
        });

        return response()->json($data);
    }

    /**
     * Endpoint para análisis de distribución
     */
    public function getAnalisisDistribucion(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $contrato = $request->get('contrato', 'all');

        $cacheKey = "analisis_distribucion_{$fechaInicio}_{$fechaFin}_{$contrato}";

        $data = Cache::remember($cacheKey, 1800, function () use ($fechaInicio, $fechaFin, $contrato) {
            // Función que crea una nueva query cada vez que se llama
            $baseQuery = function() use ($fechaInicio, $fechaFin, $contrato) {
                $query = DispensadoApiMedcol6::whereBetween('fecha_suministro', [$fechaInicio, $fechaFin])
                    ->whereIn('estado', ['DISPENSADO', 'REVISADO']);

                if ($contrato !== 'all') {
                    $query->where('centroprod', $contrato);
                }

                return $query;
            };

            // Facturación por mes
            $facturasPorMes = $baseQuery()
                ->select(
                    DB::raw('MONTH(fecha_suministro) as mes'),
                    DB::raw('YEAR(fecha_suministro) as año'),
                    DB::raw('SUM(CAST(REPLACE(valor_total, ",", "") as DECIMAL(15,2))) as total_mes')
                )
                ->groupBy(DB::raw('YEAR(fecha_suministro)'), DB::raw('MONTH(fecha_suministro)'))
                ->orderBy(DB::raw('YEAR(fecha_suministro)'))
                ->orderBy(DB::raw('MONTH(fecha_suministro)'))
                ->get();

            // Pacientes por contrato
            $pacientesPorContrato = $baseQuery()
                ->select('centroprod', DB::raw('COUNT(DISTINCT historia) as total_pacientes'))
                ->groupBy('centroprod')
                ->get();

            // Calcular mes con mayor y menor facturación
            $mesMayorFacturacion = null;
            $mesMenorFacturacion = null;

            if ($facturasPorMes->isNotEmpty()) {
                $mesMayorFacturacion = $facturasPorMes->sortByDesc('total_mes')->first();
                $mesMenorFacturacion = $facturasPorMes->sortBy('total_mes')->first();
            }

            // Facturación por día
            // IMPORTANTE: Usamos fecha_suministro que representa cuando se dispensó el medicamento
            // No confundir con fecha_ordenamiento (cuando se ordenó)
            $facturasPorDia = $baseQuery()
                ->select(
                    DB::raw('fecha_suministro as fecha'),
                    DB::raw('SUM(CAST(REPLACE(valor_total, ",", "") as DECIMAL(15,2))) as total_dia'),
                    DB::raw('COUNT(DISTINCT historia) as pacientes_dia'),
                    DB::raw('COUNT(*) as total_registros'),
                    DB::raw('DAYOFWEEK(fecha_suministro) as dia_semana')
                )
                ->groupBy('fecha_suministro')
                ->orderBy('fecha_suministro')
                ->get();

            // Log para depuración: verificar días con alta facturación
            \Log::info('Facturación por día - Top 5:', [
                'top_5' => $facturasPorDia->sortByDesc('total_dia')->take(7)->map(function($item) {
                    $diasSemana = ['', 'Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                    return [
                        'fecha' => $item->fecha,
                        'dia_semana' => $diasSemana[$item->dia_semana] ?? 'Desconocido',
                        'total_dia' => number_format($item->total_dia, 2),
                        'pacientes' => $item->pacientes_dia,
                        'registros' => $item->total_registros
                    ];
                })->values()
            ]);

            // Calcular día con mayor y menor facturación
            $diaMayorFacturacion = null;
            $diaMenorFacturacion = null;
            $diaMayorPacientes = null;

            if ($facturasPorDia->isNotEmpty()) {
                $diaMayorFacturacion = $facturasPorDia->sortByDesc('total_dia')->first();
                $diaMenorFacturacion = $facturasPorDia->sortBy('total_dia')->first();
                $diaMayorPacientes = $facturasPorDia->sortByDesc('pacientes_dia')->first();
            }

            return [
                'facturas_por_mes' => $facturasPorMes,
                'pacientes_por_contrato' => $pacientesPorContrato,
                'mes_mayor_facturacion' => $mesMayorFacturacion,
                'mes_menor_facturacion' => $mesMenorFacturacion,
                'facturas_por_dia' => $facturasPorDia,
                'dia_mayor_facturacion' => $diaMayorFacturacion,
                'dia_menor_facturacion' => $diaMenorFacturacion,
                'dia_mayor_pacientes' => $diaMayorPacientes
            ];
        });

        return response()->json($data);
    }

    /**
     * Endpoint para análisis de tendencias de pendientes
     */
    public function getAnalisisTendenciasPendientes(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $cacheKey = "tendencias_pendientes_{$fechaInicio}_{$fechaFin}";

        $data = Cache::remember($cacheKey, 1800, function () use ($fechaInicio, $fechaFin) {
            // Crear tabla temporal con el precio (costo_unitario) desde saldos_medcol6
            // Esta tabla contendrá UN registro por código con el costo unitario más reciente
            DB::statement('DROP TEMPORARY TABLE IF EXISTS temp_precios_ultimo');
            DB::statement('
                CREATE TEMPORARY TABLE temp_precios_ultimo AS
                SELECT
                    codigo,
                    CAST(REPLACE(REPLACE(costo_unitario, ",", ""), "$", "") as DECIMAL(15,2)) as precio_ultimo,
                    updated_at
                FROM saldos_medcol6
                WHERE costo_unitario IS NOT NULL
                AND costo_unitario != ""
                AND costo_unitario != "0"
                AND costo_unitario != "$0"
                GROUP BY codigo, costo_unitario, updated_at
            ');

            // Usar métodos optimizados que aprovechan la tabla temporal
            $estadisticasPorEstado = $this->getEstadisticasPorEstadoOptimizado($fechaInicio, $fechaFin);
            $tendenciasPorMes = $this->getTendenciasPorMes($fechaInicio, $fechaFin);

            // Top 10 para el gráfico
            $topMedicamentosPendientes = $this->getTopMedicamentosPendientesOptimizado($fechaInicio, $fechaFin, 10);

            // TODOS los medicamentos para el DataTable
            $todosMedicamentosPendientes = $this->getTopMedicamentosPendientesOptimizado($fechaInicio, $fechaFin, null);

            // Limpiar tabla temporal
            DB::statement('DROP TEMPORARY TABLE IF EXISTS temp_precios_ultimo');

            return [
                'estadisticas_por_estado' => $estadisticasPorEstado,
                'tendencias_por_mes' => $tendenciasPorMes,
                'top_medicamentos_pendientes' => $topMedicamentosPendientes,
                'todos_medicamentos_pendientes' => $todosMedicamentosPendientes
            ];
        });

        return response()->json($data);
    }

    /**
     * Endpoint para reportes detallados
     */
    public function getReportesDetallados(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $contrato = $request->get('contrato', 'all');
        $tipo = $request->get('tipo', 'medicamentos'); // medicamentos, pacientes, contratos

        $cacheKey = "reportes_detallados_{$tipo}_{$fechaInicio}_{$fechaFin}_{$contrato}";

        $data = Cache::remember($cacheKey, 1800, function () use ($fechaInicio, $fechaFin, $contrato, $tipo) {
            switch ($tipo) {
                case 'medicamentos':
                    return $this->getReporteMedicamentosDetallado($fechaInicio, $fechaFin, $contrato);
                case 'pacientes':
                    return $this->getReportePacientesDetallado($fechaInicio, $fechaFin, $contrato);
                case 'contratos':
                    return $this->getReporteContratosDetallado($fechaInicio, $fechaFin);
                default:
                    return [];
            }
        });

        return response()->json($data);
    }

    private function getReporteMedicamentosDetallado($fechaInicio, $fechaFin, $contrato)
    {
        $query = DispensadoApiMedcol6::whereBetween('fecha_suministro', [$fechaInicio, $fechaFin])
            ->whereIn('estado', ['DISPENSADO', 'REVISADO']);

        if ($contrato !== 'all') {
            $query->where('centroprod', $contrato);
        }

        return $query->select(
                'nombre_generico',
                DB::raw('SUM(CAST(REPLACE(valor_total, ",", "") as DECIMAL(15,2))) as total_medicamento'),
                DB::raw('SUM(CAST(REPLACE(numero_unidades, ",", "") as DECIMAL(15,2))) as total_unidades'),
                DB::raw('COUNT(*) as total_dispensaciones'),
                DB::raw('COUNT(DISTINCT historia) as pacientes_unicos')
            )
            ->groupBy('nombre_generico')
            ->orderBy('total_medicamento', 'desc')
            ->limit(50)
            ->get();
    }

    private function getReportePacientesDetallado($fechaInicio, $fechaFin, $contrato)
    {
        $query = DispensadoApiMedcol6::whereBetween('fecha_suministro', [$fechaInicio, $fechaFin])
            ->whereIn('estado', ['DISPENSADO', 'REVISADO']);

        if ($contrato !== 'all') {
            $query->where('centroprod', $contrato);
        }

        return $query->select(
                'paciente',
                'historia',
                'centroprod',
                DB::raw('SUM(CAST(REPLACE(valor_total, ",", "") as DECIMAL(15,2))) as total_paciente'),
                DB::raw('COUNT(*) as total_dispensaciones'),
                DB::raw('COUNT(DISTINCT nombre_generico) as medicamentos_diferentes')
            )
            ->groupBy('historia', 'paciente', 'centroprod')
            ->orderBy('total_paciente', 'desc')
            ->limit(50)
            ->get();
    }

    private function getReporteContratosDetallado($fechaInicio, $fechaFin)
    {
        return DispensadoApiMedcol6::whereBetween('fecha_suministro', [$fechaInicio, $fechaFin])
            ->whereIn('estado', ['DISPENSADO', 'REVISADO'])
            ->select(
                'centroprod',
                DB::raw('COUNT(DISTINCT historia) as total_pacientes'),
                DB::raw('SUM(CAST(REPLACE(valor_total, ",", "") as DECIMAL(15,2))) as total_facturado'),
                DB::raw('COUNT(*) as total_dispensaciones'),
                DB::raw('COUNT(DISTINCT nombre_generico) as medicamentos_diferentes')
            )
            ->groupBy('centroprod')
            ->orderBy('total_facturado', 'desc')
            ->get();
    }

    /**
     * Endpoint para obtener valor facturado por contrato
     */
    public function getValorPorContrato(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $contrato = $request->get('contrato', 'all');

        $cacheKey = "valor_por_contrato_{$fechaInicio}_{$fechaFin}_{$contrato}";

        $data = Cache::remember($cacheKey, 1800, function () use ($fechaInicio, $fechaFin, $contrato) {
            $query = DB::table('dispensado_medcol6 as d')
                ->leftJoin('analisis_nt as a', function($join) {
                    $join->on('d.codigo', '=', 'a.codigo_medcol')
                         ->on('d.centroprod', '=', 'a.contrato');
                })
                ->whereBetween('d.fecha_suministro', [$fechaInicio, $fechaFin])
                ->whereIn('d.estado', ['DISPENSADO', 'REVISADO']);

            if ($contrato !== 'all') {
                $query->where('d.centroprod', $contrato);
            }

            return $query->select(
                    'd.centroprod',
                    DB::raw('SUM(
                        CASE
                            WHEN a.valor_unitario IS NOT NULL AND a.valor_unitario > 0
                            THEN a.valor_unitario * d.numero_unidades
                            WHEN d.precio_unitario IS NOT NULL AND d.precio_unitario > 0
                            THEN d.precio_unitario * d.numero_unidades
                            ELSE CAST(REPLACE(d.valor_total, ",", "") as DECIMAL(15,2))
                        END
                    ) as total_facturado')
                )
                ->groupBy('d.centroprod')
                ->orderBy('total_facturado', 'desc')
                ->get();
        });

        return response()->json($data);
    }

    /**
     * Endpoint de diagnóstico para analizar el cálculo de valores entregados
     */
    public function diagnosticoEntregados(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        // 1. Verificar estados únicos en pendientes - OPTIMIZADO
        $estadosUnicos = DB::table('pendiente_api_medcol6')
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->orderBy('total', 'desc')
            ->limit(20)  // Limitar para evitar timeout
            ->get();

        // 2. Obtener códigos únicos de pendientes en el rango de fechas (OPTIMIZADO)
        $codigosPendientes = DB::table('pendiente_api_medcol6')
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->distinct()
            ->pluck('codigo')
            ->take(1000);  // Limitar a 1000 códigos únicos

        // 3. Crear tabla temporal SOLO con los códigos que se usan en pendientes
        DB::statement('DROP TEMPORARY TABLE IF EXISTS temp_precios_diagnostico');

        if ($codigosPendientes->isNotEmpty()) {
            $codigosIn = $codigosPendientes->map(function($codigo) {
                return DB::connection()->getPdo()->quote($codigo);
            })->implode(',');

            DB::statement("
                CREATE TEMPORARY TABLE temp_precios_diagnostico AS
                SELECT
                    codigo,
                    CAST(REPLACE(REPLACE(costo_unitario, ',', ''), '$', '') as DECIMAL(15,2)) as precio_ultimo,
                    updated_at
                FROM saldos_medcol6
                WHERE codigo IN ($codigosIn)
                AND costo_unitario IS NOT NULL
                AND costo_unitario != ''
                AND costo_unitario != '0'
                AND costo_unitario != '\$0'
                GROUP BY codigo, costo_unitario, updated_at
            ");
        } else {
            // Si no hay códigos, crear tabla vacía
            DB::statement("
                CREATE TEMPORARY TABLE temp_precios_diagnostico (
                    codigo VARCHAR(50),
                    precio_ultimo DECIMAL(15,2),
                    updated_at TIMESTAMP
                )
            ");
        }

        // 4. Analizar registros entregados (muestra reducida)
        $entregados = DB::table('pendiente_api_medcol6')
            ->where('estado', 'ENTREGADO')
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->limit(5)
            ->get(['codigo', 'nombre', 'cantord', 'estado']);

        // 5. Verificar cuántos códigos tienen precio en la tabla temporal
        $codigosConPrecio = DB::table('temp_precios_diagnostico')
            ->count();

        // 6. Verificar coincidencias (muestra reducida)
        $entregadosConPrecio = DB::table('pendiente_api_medcol6 as p')
            ->join('temp_precios_diagnostico as d', 'p.codigo', '=', 'd.codigo')
            ->where('p.estado', 'ENTREGADO')
            ->whereBetween('p.fecha', [$fechaInicio, $fechaFin])
            ->select('p.codigo', 'p.nombre', 'p.cantord', 'd.precio_ultimo',
                DB::raw('CAST(p.cantord as DECIMAL(10,2)) * CAST(d.precio_ultimo as DECIMAL(10,2)) as valor_calculado'))
            ->limit(5)
            ->get();

        // 7. Calcular totales SOLO de ENTREGADO
        $totales = DB::table('pendiente_api_medcol6 as p')
            ->leftJoin('temp_precios_diagnostico as d', 'p.codigo', '=', 'd.codigo')
            ->where('p.estado', 'ENTREGADO')
            ->whereBetween('p.fecha', [$fechaInicio, $fechaFin])
            ->select(
                DB::raw('COUNT(*) as total_registros'),
                DB::raw('COUNT(d.codigo) as registros_con_precio'),
                DB::raw('SUM(CAST(p.cantord as DECIMAL(10,2))) as total_cantidades'),
                DB::raw('SUM(CAST(p.cantord as DECIMAL(10,2)) * CAST(COALESCE(d.precio_ultimo, 0) as DECIMAL(10,2))) as valor_total')
            )
            ->first();

        // Limpiar tabla temporal
        DB::statement('DROP TEMPORARY TABLE IF EXISTS temp_precios_diagnostico');

        return response()->json([
            'estados_disponibles' => $estadosUnicos,
            'total_codigos_analizados' => $codigosPendientes->count(),
            'total_codigos_con_precio_valido' => $codigosConPrecio,
            'muestra_entregados_sin_precio' => $entregados,
            'muestra_entregados_con_precio' => $entregadosConPrecio,
            'totales' => $totales,
            'nota' => 'Optimizado: solo analiza códigos usados en pendientes del rango de fechas. Máximo 1000 códigos.'
        ]);
    }
}