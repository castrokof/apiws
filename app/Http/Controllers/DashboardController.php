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

        // Intentar obtener del caché (válido por 30 minutos)
        return Cache::remember($cacheKey, 1800, function () use ($fechaInicio, $fechaFin) {
            // Crear una tabla temporal con precios promedio para reutilizar en todas las consultas
            // Esto evita recalcular la subconsulta múltiples veces
            DB::statement('DROP TEMPORARY TABLE IF EXISTS temp_precios_promedio');
            DB::statement('
                CREATE TEMPORARY TABLE temp_precios_promedio AS
                SELECT codigo, AVG(CAST(REPLACE(precio_unitario, ",", "") as DECIMAL(15,2))) as precio_promedio
                FROM dispensado_medcol6
                GROUP BY codigo
            ');

            // Estadísticas por estado de pendientes
            $estadisticasPorEstado = $this->getEstadisticasPorEstadoOptimizado($fechaInicio, $fechaFin);

            // Valor total pendiente por facturar
            $valorTotalPendiente = $this->getValorPendientePorFacturarOptimizado($fechaInicio, $fechaFin);

            // Valor total entregado
            $valorTotalEntregado = $this->getValorEntregadoOptimizado($fechaInicio, $fechaFin);

            // Top medicamentos pendientes por valor
            $topMedicamentosPendientes = $this->getTopMedicamentosPendientesOptimizado($fechaInicio, $fechaFin, 10);

            // Tendencias por mes
            $tendenciasPorMes = $this->getTendenciasPorMes($fechaInicio, $fechaFin);

            // Limpiar tabla temporal
            DB::statement('DROP TEMPORARY TABLE IF EXISTS temp_precios_promedio');

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
        // Optimización: Usar tabla temporal con precios promedio
        $estadisticas = DB::table('pendiente_api_medcol6 as p')
            ->leftJoin('temp_precios_promedio as d', 'p.codigo', '=', 'd.codigo')
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
        // Optimización: Usar tabla temporal con precios promedio
        $resultado = DB::table('pendiente_api_medcol6 as p')
            ->leftJoin('temp_precios_promedio as d', 'p.codigo', '=', 'd.codigo')
            ->whereIn('p.estado', ['PENDIENTE', 'DESABASTECIDO', 'SIN CONTACTO', 'TRAMITADO', 'VENCIDO'])
            ->whereBetween('p.fecha', [$fechaInicio, $fechaFin])
            ->select(DB::raw('SUM(CAST(p.cantord as DECIMAL(10,2)) * CAST(COALESCE(d.precio_promedio, 0) as DECIMAL(10,2))) as valor_total'))
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
        // Optimización: Usar tabla temporal con precios promedio
        $resultado = DB::table('pendiente_api_medcol6 as p')
            ->leftJoin('temp_precios_promedio as d', 'p.codigo', '=', 'd.codigo')
            ->where('p.estado', 'ENTREGADO')
            ->whereBetween('p.fecha', [$fechaInicio, $fechaFin])
            ->select(DB::raw('SUM(CAST(p.cantord as DECIMAL(10,2)) * CAST(COALESCE(d.precio_promedio, 0) as DECIMAL(10,2))) as valor_total'))
            ->value('valor_total');

        return $resultado ?? 0;
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
        // Optimización: Usar tabla temporal con precios promedio
        $query = DB::table('pendiente_api_medcol6 as p')
            ->leftJoin('temp_precios_promedio as d', 'p.codigo', '=', 'd.codigo')
            ->whereBetween('p.fecha', [$fechaInicio, $fechaFin])
            ->select(
                'p.codigo',
                'p.nombre',
                DB::raw('SUM(CAST(p.cantord as DECIMAL(10,2))) as total_cantidad'),
                DB::raw('COUNT(*) as total_pendientes'),
                DB::raw('SUM(CAST(p.cantord as DECIMAL(10,2)) * CAST(COALESCE(d.precio_promedio, 0) as DECIMAL(10,2))) as valor_total')
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
            $valorTotal = $baseQuery()->sum(DB::raw('CAST(REPLACE(valor_total, ",", "") as DECIMAL(15,2))'));
            $totalMedicamentos = $baseQuery()->distinct('nombre_generico')->count('nombre_generico');

            // Paciente con mayor valor
            $pacienteMayorValor = $baseQuery()
                ->select(
                    'paciente',
                    'historia',
                    DB::raw('SUM(CAST(REPLACE(valor_total, ",", "") as DECIMAL(15,2))) as total_paciente')
                )
                ->groupBy('historia', 'paciente')
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

            return [
                'facturas_por_mes' => $facturasPorMes,
                'pacientes_por_contrato' => $pacientesPorContrato
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
            // Crear tabla temporal con precios promedio para optimizar las consultas
            DB::statement('DROP TEMPORARY TABLE IF EXISTS temp_precios_promedio');
            DB::statement('
                CREATE TEMPORARY TABLE temp_precios_promedio AS
                SELECT codigo, AVG(CAST(REPLACE(precio_unitario, ",", "") as DECIMAL(15,2))) as precio_promedio
                FROM dispensado_medcol6
                GROUP BY codigo
            ');

            // Usar métodos optimizados que aprovechan la tabla temporal
            $estadisticasPorEstado = $this->getEstadisticasPorEstadoOptimizado($fechaInicio, $fechaFin);
            $tendenciasPorMes = $this->getTendenciasPorMes($fechaInicio, $fechaFin);

            // Top 10 para el gráfico
            $topMedicamentosPendientes = $this->getTopMedicamentosPendientesOptimizado($fechaInicio, $fechaFin, 10);

            // TODOS los medicamentos para el DataTable
            $todosMedicamentosPendientes = $this->getTopMedicamentosPendientesOptimizado($fechaInicio, $fechaFin, null);

            // Limpiar tabla temporal
            DB::statement('DROP TEMPORARY TABLE IF EXISTS temp_precios_promedio');

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
}