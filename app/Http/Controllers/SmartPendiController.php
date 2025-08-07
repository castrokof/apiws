<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Medcol6\PendienteApiMedcol6;
use App\Models\Medcol6\SaldosMedcol6;
use App\Helpers\DeliveryMetricsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class SmartPendiController extends Controller
{
    /**
     * Display the Smart Pendi dashboard with predictive analysis
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('smart-pendi.dashboard');
    }

    /**
     * Get pending medications within 0-48 hours for DataTable
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPendientesAnalysis(Request $request)
    {
        // Get droguería based on authenticated user
        $drogueria = $this->getUserDrogueria();

        // Build base query for pendientes within 0-48 hours
        $query = PendienteApiMedcol6::query()
            ->select([
                'id',
                'documento',
                'nombre1',
                'nombre2', 
                'apellido1',
                'apellido2',
                'nombre',
                'cantidad',
                'fecha_factura',
                'estado',
                'factura',
                'orden_externa',
                'telefres',
                'municipio',
                'centroproduccion',
                'observaciones'
            ])
            ->where(function ($q) {
                $q->where('estado', 'PENDIENTE')
                  ->orWhere('estado', NULL);
            });

        // Apply droguería filter if needed
        if (!empty($drogueria)) {
            $query->where('centroproduccion', $drogueria);
        }

        // Filter for records within 0-48 hours (not older than 48 hours)
        $limitDate = Carbon::now()->subHours(48);
        $query->where('fecha_factura', '>=', $limitDate);

        // DataTable server-side processing
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 25);
        $searchValue = $request->get('search')['value'] ?? '';

        // Clone query for total count
        $totalQuery = clone $query;
        $totalRecords = $totalQuery->count();

        // Apply search if provided
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('documento', 'LIKE', "%{$searchValue}%")
                  ->orWhere('nombre1', 'LIKE', "%{$searchValue}%")
                  ->orWhere('nombre2', 'LIKE', "%{$searchValue}%")
                  ->orWhere('apellido1', 'LIKE', "%{$searchValue}%")
                  ->orWhere('apellido2', 'LIKE', "%{$searchValue}%")
                  ->orWhere('nombre', 'LIKE', "%{$searchValue}%")
                  ->orWhere('municipio', 'LIKE', "%{$searchValue}%")
                  ->orWhere('telefres', 'LIKE', "%{$searchValue}%");
            });
        }

        // Get filtered count
        $filteredQuery = clone $query;
        $filteredRecords = $filteredQuery->count();

        // Apply ordering
        $orderColumnIndex = $request->get('order')[0]['column'] ?? 4; // Default to days column
        $orderDirection = $request->get('order')[0]['dir'] ?? 'desc';
        
        $columns = ['estado', 'paciente', 'documento', 'nombre', 'fecha_factura', 'fecha_factura', 'telefres', 'municipio', 'acciones'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'fecha_factura';
        
        if ($orderColumn === 'paciente') {
            $query->orderByRaw("CONCAT(nombre1, ' ', nombre2, ' ', apellido1, ' ', apellido2) {$orderDirection}");
        } else {
            $query->orderBy($orderColumn, $orderDirection);
        }

        // Apply pagination
        $pendientes = $query->skip($start)->take($length)->get();

        // Process each pendiente with delivery metrics
        $data = $pendientes->map(function ($pendiente) {
            $metrics = DeliveryMetricsHelper::obtenerTodasLasMetricas($pendiente->fecha_factura);
            
            return [
                'id' => $pendiente->id,
                'paciente' => trim($pendiente->nombre1 . ' ' . $pendiente->nombre2 . ' ' . $pendiente->apellido1 . ' ' . $pendiente->apellido2),
                'documento' => $pendiente->documento,
                'historia' => $pendiente->historia ?? $pendiente->documento,
                'medicamento' => $pendiente->nombre,
                'cantidad' => $pendiente->cantidad,
                'fecha_factura' => $pendiente->fecha_factura,
                'telefono' => $pendiente->telefres,
                'municipio' => $pendiente->municipio,
                'centro_produccion' => $pendiente->centroproduccion,
                'estado' => $pendiente->estado ?: 'PENDIENTE',
                'factura' => $pendiente->factura,
                'orden_externa' => $pendiente->orden_externa,
                'observaciones' => $pendiente->observaciones,
                'dias_transcurridos' => $metrics['dias_transcurridos'],
                'horas_transcurridas' => $metrics['horas_transcurridas'] ?? 0,
                'fecha_estimada_entrega' => $metrics['fecha_estimada_entrega'],
                'estado_prioridad' => $metrics['estado_prioridad']
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Get predictive suggestions prioritizing patients with multiple pending medications (2+) 
     * within the 0-48 hour opportunity window
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPredictiveSuggestions(Request $request)
    {
        $drogueria = $this->getUserDrogueria();
        
        // Build query to get pendientes within 0-48 hours grouped by patient with medication details
        $query = PendienteApiMedcol6::query()
            ->select([
                'documento',
                'historia',
                'nombre1', 'nombre2', 'apellido1', 'apellido2',
                'telefres', 'municipio',
                DB::raw('COUNT(*) as total_medicamentos'),
                DB::raw('GROUP_CONCAT(DISTINCT nombre SEPARATOR " | ") as medicamentos_list'),
                DB::raw('GROUP_CONCAT(DISTINCT codigo SEPARATOR ",") as codigos_list'),
                DB::raw('GROUP_CONCAT(DISTINCT centroproduccion SEPARATOR ",") as farmacias_list'),
                DB::raw('GROUP_CONCAT(id SEPARATOR ",") as pendientes_ids'),
                DB::raw('MIN(fecha_factura) as fecha_mas_antigua'),
                DB::raw('MAX(fecha_factura) as fecha_mas_reciente'),
                DB::raw('AVG(TIMESTAMPDIFF(HOUR, fecha_factura, NOW())) as promedio_horas_transcurridas')
            ])
            ->where(function ($q) {
                $q->where('estado', 'PENDIENTE')
                  ->orWhere('estado', NULL);
            })
            ->where('fecha_factura', '>=', Carbon::now()->subHours(48))
            ->groupBy(['documento', 'historia', 'nombre1', 'nombre2', 'apellido1', 'apellido2', 'telefres', 'municipio'])
            ->having('total_medicamentos', '>=', 2);

        // Apply droguería filter if needed
        if (!empty($drogueria)) {
            $query->where('centroproduccion', $drogueria);
        }

        // Order by total medications descending, then by oldest prescription
        $pacientesMultiples = $query->orderBy('total_medicamentos', 'DESC')
                                   ->orderBy('fecha_mas_antigua', 'ASC')
                                   ->get();

        // Mapeo de farmacias a depósitos para consultar saldos
        $farmaciaToDeposito = [
            'BIO1' => 'BIO1',
            'DLR1' => 'DLR1',
            'DPA1' => 'DPA1',
            'EM01' => 'EM01',
            'EHU1' => 'EHU1',
            'FRJA' => 'FRJA',
            'FRIO' => 'FRIO',
            'INY' => 'INY',
            'PAC' => 'PAC',
            'SM01' => 'SM01',
            'BPDT' => 'BPDT',
            'EVEN' => 'EVEN',
            'EVSM' => 'EVSM'
        ];

        $suggestions = [];

        foreach ($pacientesMultiples as $paciente) {
            $nombreCompleto = trim($paciente->nombre1 . ' ' . $paciente->nombre2 . ' ' . $paciente->apellido1 . ' ' . $paciente->apellido2);
            $promedioHoras = round($paciente->promedio_horas_transcurridas, 1);
            
            // Analizar estado de saldos por medicamento
            $codigos = explode(',', $paciente->codigos_list);
            $farmacias = explode(',', $paciente->farmacias_list);
            $medicamentosConSaldo = 0;
            $medicamentosSinSaldo = 0;
            $estadosSaldos = [];

            // Revisar saldo para cada código de medicamento
            for ($i = 0; $i < count($codigos); $i++) {
                $codigo = trim($codigos[$i]);
                $farmacia = isset($farmacias[$i]) ? trim($farmacias[$i]) : (isset($farmacias[0]) ? trim($farmacias[0]) : '');
                
                // Determinar depósito para consultar saldo
                $deposito = $farmaciaToDeposito[$farmacia] ?? $farmacia;
                
                // Consultar saldo más reciente para este medicamento
                $saldo = SaldosMedcol6::where('codigo', $codigo)
                    ->where('deposito', $deposito)
                    ->orderBy('fecha_saldo', 'desc')
                    ->first();
                
                $saldoDisponible = $saldo ? (float)$saldo->saldo : 0;
                
                if ($saldoDisponible > 0) {
                    $medicamentosConSaldo++;
                    $estadosSaldos[$codigo] = 'CON_SALDO';
                } else {
                    $medicamentosSinSaldo++;
                    $estadosSaldos[$codigo] = 'SIN_SALDO';
                }
            }

            // Determinar estado general de saldos del paciente
            $tieneAlgunSaldo = $medicamentosConSaldo > 0;
            $tieneTodosSaldos = $medicamentosSinSaldo == 0;
            
            // Determine priority based on number of medications, average hours, and inventory status
            $prioridad = 'MEDIA';
            $accion = '';
            $plazo = '';
            
            // Ajustar prioridad considerando disponibilidad de saldos
            if ($tieneTodosSaldos && ($paciente->total_medicamentos >= 4 || $promedioHoras >= 40)) {
                $prioridad = 'ALTA';
                $accion = 'URGENTE: Paciente con ' . $paciente->total_medicamentos . ' medicamentos pendientes TODOS CON SALDO. Contactar inmediatamente para entrega consolidada.';
                $plazo = 'INMEDIATO';
            } elseif ($tieneAlgunSaldo && ($paciente->total_medicamentos >= 3 || $promedioHoras >= 30)) {
                $prioridad = 'MEDIA-ALTA';
                $accion = 'PRIORITARIO: ' . $medicamentosConSaldo . ' de ' . $paciente->total_medicamentos . ' medicamentos con saldo disponible. Priorizar entrega de disponibles y gestionar faltantes.';
                $plazo = '12 HORAS';
            } elseif ($tieneAlgunSaldo) {
                $prioridad = 'MEDIA';
                $accion = 'PLANIFICAR: ' . $medicamentosConSaldo . ' medicamentos disponibles de ' . $paciente->total_medicamentos . '. Coordinar entrega parcial y reposición.';
                $plazo = '24 HORAS';
            } else {
                $prioridad = 'BAJA';
                $accion = 'GESTIONAR INVENTARIO: Ningún medicamento tiene saldo disponible. Requiere gestión de compras antes de entrega.';
                $plazo = '48-72 HORAS';
            }

            $suggestions[] = [
                'pendiente_ids' => explode(',', $paciente->pendientes_ids),
                'documento' => $paciente->documento,
                'historia' => $paciente->historia ?? $paciente->documento,
                'paciente' => $nombreCompleto,
                'total_medicamentos' => $paciente->total_medicamentos,
                'medicamentos' => $paciente->medicamentos_list,
                'medicamentos_con_saldo' => $medicamentosConSaldo,
                'medicamentos_sin_saldo' => $medicamentosSinSaldo,
                'tiene_saldo' => $tieneAlgunSaldo,
                'todos_con_saldo' => $tieneTodosSaldos,
                'estados_saldos' => $estadosSaldos,
                'prioridad' => $prioridad,
                'accion' => $accion,
                'telefono' => $paciente->telefres,
                'municipio' => $paciente->municipio,
                'plazo' => $plazo,
                'fecha_mas_antigua' => $paciente->fecha_mas_antigua,
                'fecha_mas_reciente' => $paciente->fecha_mas_reciente,
                'promedio_horas_transcurridas' => $promedioHoras,
                'ventaja_consolidacion' => $tieneTodosSaldos ? 
                    'Entrega consolidada inmediata: ' . $paciente->total_medicamentos . ' medicamentos listos' :
                    ($tieneAlgunSaldo ? 
                        'Entrega parcial: ' . $medicamentosConSaldo . ' listos, ' . $medicamentosSinSaldo . ' por gestionar' :
                        'Requiere gestión de inventario completa antes de entrega'
                    )
            ];
        }

        // Ordenar sugerencias: primero por estado de saldo, luego por prioridad
        usort($suggestions, function($a, $b) {
            // Priorizar pacientes con todos los saldos disponibles
            if ($a['todos_con_saldo'] && !$b['todos_con_saldo']) return -1;
            if (!$a['todos_con_saldo'] && $b['todos_con_saldo']) return 1;
            
            // Luego por pacientes con algunos saldos
            if ($a['tiene_saldo'] && !$b['tiene_saldo']) return -1;
            if (!$a['tiene_saldo'] && $b['tiene_saldo']) return 1;
            
            // Finalmente por número de medicamentos
            return $b['total_medicamentos'] <=> $a['total_medicamentos'];
        });

        // Calcular estadísticas de saldos
        $conTodosSaldos = array_filter($suggestions, fn($s) => $s['todos_con_saldo']);
        $conAlgunSaldo = array_filter($suggestions, fn($s) => $s['tiene_saldo'] && !$s['todos_con_saldo']);
        $sinSaldo = array_filter($suggestions, fn($s) => !$s['tiene_saldo']);

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions,
            'total_suggestions' => count($suggestions),
            'estadisticas_saldos' => [
                'con_todos_saldos' => count($conTodosSaldos),
                'con_algunos_saldos' => count($conAlgunSaldo),
                'sin_saldos' => count($sinSaldo),
                'porcentaje_disponibles' => count($suggestions) > 0 ? round((count($conTodosSaldos) + count($conAlgunSaldo)) * 100 / count($suggestions), 1) : 0
            ],
            'enfoque' => 'Pacientes con múltiples medicamentos pendientes (2+) priorizados por disponibilidad de inventario',
            'beneficios' => [
                'Optimización basada en disponibilidad real de medicamentos',
                'Priorización de entregas inmediatas para medicamentos con saldo',
                'Gestión proactiva de medicamentos sin disponibilidad',
                'Reducción de costos operativos y mejor experiencia del paciente',
                'Cumplimiento de ventana de oportunidad 0-48h'
            ]
        ]);
    }

    /**
     * Get dashboard statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatistics()
    {
        $drogueria = $this->getUserDrogueria();
        
        // Cache key for statistics
        $cacheKey = 'smart_pendi_stats_' . md5($drogueria);
        
        $stats = Cache::remember($cacheKey, 300, function () use ($drogueria) {
            $query = PendienteApiMedcol6::query()
                ->where(function ($q) {
                    $q->where('estado', 'PENDIENTE')
                      ->orWhere('estado', NULL);
                });

            if (!empty($drogueria)) {
                $query->where('centroproduccion', $drogueria);
            }

            $now = Carbon::now();
            
            return [
                'total_pendientes' => $query->count(),
                'dentro_48h' => (clone $query)->where('fecha_factura', '>=', $now->copy()->subHours(48))->count(),
                'criticos_24_48h' => (clone $query)->whereBetween('fecha_factura', [
                    $now->copy()->subHours(48),
                    $now->copy()->subHours(24)
                ])->count(),
                'nuevos_24h' => (clone $query)->where('fecha_factura', '>=', $now->copy()->subHours(24))->count(),
                'proximos_vencer' => (clone $query)->whereBetween('fecha_factura', [
                    $now->copy()->subHours(40),
                    $now->copy()->subHours(24)
                ])->count()
            ];
        });

        return response()->json([
            'success' => true,
            'statistics' => $stats
        ]);
    }

    /**
     * Get summary statistics for 0-48 hour pendientes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSummary()
    {
        $drogueria = $this->getUserDrogueria();
        
        $query = PendienteApiMedcol6::query()
            ->where(function ($q) {
                $q->where('estado', 'PENDIENTE')
                  ->orWhere('estado', NULL);
            });

        if (!empty($drogueria)) {
            $query->where('centroproduccion', $drogueria);
        }

        // Filter for records within 0-48 hours
        $limitDate = Carbon::now()->subHours(48);
        $query->where('fecha_factura', '>=', $limitDate);

        $pendientes = $query->get();
        $now = Carbon::now();
        
        $summary = [
            'total' => $pendientes->count(),
            'urgente' => 0,
            'critico' => 0,
            'prioridad' => 0,
            'en_tiempo' => 0
        ];

        foreach ($pendientes as $pendiente) {
            $metrics = DeliveryMetricsHelper::obtenerTodasLasMetricas($pendiente->fecha_factura);
            $estado = $metrics['estado_prioridad']['estado'];
            
            switch ($estado) {
                case 'URGENTE':
                    $summary['urgente']++;
                    break;
                case 'CRITICO':
                    $summary['critico']++;
                    break;
                case 'PRIORIDAD':
                    $summary['prioridad']++;
                    break;
                case 'EN_TIEMPO':
                    $summary['en_tiempo']++;
                    break;
            }
        }

        return response()->json([
            'success' => true,
            'summary' => $summary
        ]);
    }

    /**
     * Get user's droguería based on authentication
     *
     * @return string
     */
    private function getUserDrogueria()
    {
        if (!Auth::check()) {
            return '';
        }

        switch (Auth::user()->drogueria) {
            case "1": return ''; // Todos
            case "2": return 'SM01';
            case "3": return 'DLR1';
            case "4": return 'PAC';
            case "5": return 'EHU1';
            case "6": return 'BIO1';
            case "8": return 'EM01';
            case "9": return 'BPDT';
            case "10": return 'DPA1';
            case "11": return 'EVSM';
            case "12": return 'EVEN';
            case "13": return 'FRJA';
            default: return '';
        }
    }
}