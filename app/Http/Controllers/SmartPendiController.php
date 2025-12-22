<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Medcol6\PendienteApiMedcol6;
use App\Models\Medcol6\SaldosMedcol6;
use App\Models\Medcol6\GestionHistoricoMedcol6;
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
            'FRIP' => 'FRIP',
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

    /**
     * FASE 4: Get patient history with all events
     *
     * @param string $historia Patient history number
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPatientHistory($historia)
    {
        try {
            // Cache key for patient history
            $cacheKey = "patient_history_{$historia}";

            // Cache for 5 minutes (300 seconds)
            $data = Cache::remember($cacheKey, 300, function () use ($historia) {
                // Get all historical events for this patient
                $eventos = GestionHistoricoMedcol6::where('historia', $historia)
                    ->with(['usuario:id,name', 'pendiente:id,factura,codigo,nombre'])
                    ->orderBy('created_at', 'desc')
                    ->get();

                // Get current pending medications for this patient
                $pendientesActivos = PendienteApiMedcol6::where('historia', $historia)
                    ->whereIn('estado', ['PENDIENTE', 'DIRECCIONADO', 'PROGRAMADO'])
                    ->orderBy('fecha', 'desc')
                    ->get();

                // Get patient info from the first pendiente found
                $paciente = PendienteApiMedcol6::where('historia', $historia)
                    ->orderBy('id', 'desc')
                    ->first();

                return [
                    'eventos' => $eventos,
                    'pendientes_activos' => $pendientesActivos,
                    'paciente' => $paciente ? [
                        'historia' => $paciente->historia,
                        'documento' => $paciente->documento,
                        'nombre_completo' => trim(sprintf(
                            '%s %s %s %s',
                            $paciente->nombre1 ?? '',
                            $paciente->nombre2 ?? '',
                            $paciente->apellido1 ?? '',
                            $paciente->apellido2 ?? ''
                        )),
                        'telefono' => $paciente->telefres,
                        'direccion' => $paciente->direcres,
                        'municipio' => $paciente->municipio,
                    ] : null
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'total_eventos' => $data['eventos']->count(),
                'pendientes_activos' => $data['pendientes_activos']->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al obtener histórico del paciente', [
                'historia' => $historia,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el histórico del paciente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * FASE 4: Search patients by historia, documento or name
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchPatients(Request $request)
    {
        try {
            $query = $request->input('query', '');

            // Validate minimum query length
            if (strlen($query) < 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'La búsqueda debe tener al menos 3 caracteres',
                    'data' => []
                ], 422);
            }

            // Search in pendiente_api_medcol6 table
            $pacientes = PendienteApiMedcol6::select(
                'historia',
                'documento',
                'nombre1',
                'nombre2',
                'apellido1',
                'apellido2',
                'telefres',
                'municipio',
                DB::raw('MAX(created_at) as ultima_actualizacion'),
                DB::raw('COUNT(*) as total_pendientes')
            )
                ->where(function ($q) use ($query) {
                    $q->where('historia', 'LIKE', "%{$query}%")
                        ->orWhere('documento', 'LIKE', "%{$query}%")
                        ->orWhere('nombre1', 'LIKE', "%{$query}%")
                        ->orWhere('apellido1', 'LIKE', "%{$query}%")
                        ->orWhere(DB::raw("CONCAT(nombre1, ' ', apellido1)"), 'LIKE', "%{$query}%")
                        ->orWhere(DB::raw("CONCAT(nombre1, ' ', nombre2, ' ', apellido1, ' ', apellido2)"), 'LIKE', "%{$query}%");
                })
                ->groupBy('historia', 'documento', 'nombre1', 'nombre2', 'apellido1', 'apellido2', 'telefres', 'municipio')
                ->orderBy('ultima_actualizacion', 'desc')
                ->limit(20)
                ->get();

            // Add event count for each patient
            $pacientes->each(function ($paciente) {
                $paciente->total_eventos = GestionHistoricoMedcol6::where('historia', $paciente->historia)
                    ->count();
                $paciente->nombre_completo = trim(sprintf(
                    '%s %s %s %s',
                    $paciente->nombre1 ?? '',
                    $paciente->nombre2 ?? '',
                    $paciente->apellido1 ?? '',
                    $paciente->apellido2 ?? ''
                ));
            });

            return response()->json([
                'success' => true,
                'data' => $pacientes,
                'total' => $pacientes->count(),
                'query' => $query
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al buscar pacientes', [
                'query' => $request->input('query'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al buscar pacientes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * FASE 5: Register manual management event (contact, observation, etc.)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerManualGestion(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'historia' => 'required|string|max:50',
                'pendiente_id' => 'nullable|exists:pendiente_api_medcol6,id',
                'tipo_evento' => 'required|in:CONTACTO_LLAMADA,CONTACTO_MENSAJE,CONTACTO_VISITA,OBSERVACION_GESTION,REPROGRAMACION',
                'titulo' => 'required|string|max:255',
                'descripcion' => 'required|string|max:2000',
                'resultado_contacto' => 'nullable|in:EXITOSO,NO_CONTESTA,TELEFONO_INVALIDO,REAGENDAR,RECHAZADO,OTRO',
                'requiere_seguimiento' => 'nullable|boolean',
                'fecha_seguimiento' => 'nullable|date|after:today',
                'metadata' => 'nullable|array'
            ]);

            // Verify patient exists
            $paciente = PendienteApiMedcol6::where('historia', $validated['historia'])
                ->orderBy('id', 'desc')
                ->first();

            if (!$paciente) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró información del paciente con la historia especificada'
                ], 404);
            }

            // Create historical event
            $evento = GestionHistoricoMedcol6::create([
                'pendiente_id' => $validated['pendiente_id'] ?? null,
                'historia' => $validated['historia'],
                'usuario_id' => Auth::id(),
                'tipo_evento' => $validated['tipo_evento'],
                'titulo' => $validated['titulo'],
                'descripcion' => $validated['descripcion'],
                'estado_anterior' => null,
                'estado_nuevo' => null,
                'metadata' => array_merge($validated['metadata'] ?? [], [
                    'paciente_nombre' => trim(sprintf(
                        '%s %s %s %s',
                        $paciente->nombre1 ?? '',
                        $paciente->nombre2 ?? '',
                        $paciente->apellido1 ?? '',
                        $paciente->apellido2 ?? ''
                    )),
                    'telefono' => $paciente->telefres,
                    'usuario_registro' => Auth::user()->name ?? 'Sistema'
                ]),
                'resultado_contacto' => $validated['resultado_contacto'] ?? null,
                'requiere_seguimiento' => $validated['requiere_seguimiento'] ?? false,
                'fecha_seguimiento' => $validated['fecha_seguimiento'] ?? null
            ]);

            // Clear patient history cache
            Cache::forget("patient_history_{$validated['historia']}");

            // Log successful registration
            \Log::info('Gestión manual registrada exitosamente', [
                'evento_id' => $evento->id,
                'tipo_evento' => $validated['tipo_evento'],
                'historia' => $validated['historia'],
                'usuario_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gestión registrada exitosamente',
                'data' => [
                    'evento_id' => $evento->id,
                    'created_at' => $evento->created_at->format('Y-m-d H:i:s'),
                    'tipo_evento' => $evento->tipo_evento,
                    'titulo' => $evento->titulo
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al registrar gestión manual', [
                'request_data' => $request->all(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la gestión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * FASE 6: Get patient metrics and statistics
     *
     * @param string $historia Patient history number
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPatientMetrics($historia)
    {
        try {
            // Cache key for patient metrics
            $cacheKey = "patient_metrics_{$historia}";

            // Cache for 10 minutes (600 seconds)
            $metrics = Cache::remember($cacheKey, 600, function () use ($historia) {
                // Get all pendientes for this patient
                $pendientes = PendienteApiMedcol6::where('historia', $historia)->get();

                if ($pendientes->isEmpty()) {
                    return null;
                }

                // Total pendientes
                $totalPendientes = $pendientes->count();

                // Pendientes by status
                $porEstado = $pendientes->groupBy('estado')->map(function ($group) {
                    return $group->count();
                })->toArray();

                // Calculate average delivery time (days from creation to delivery)
                $entregados = $pendientes->filter(function ($p) {
                    return $p->estado === 'ENTREGADO' && $p->fecha_entrega;
                });

                $tiempoPromedioEntrega = null;
                if ($entregados->isNotEmpty()) {
                    $tiempos = $entregados->map(function ($p) {
                        $fechaCreacion = Carbon::parse($p->fecha ?? $p->created_at);
                        $fechaEntrega = Carbon::parse($p->fecha_entrega);
                        return $fechaCreacion->diffInDays($fechaEntrega);
                    });

                    $tiempoPromedioEntrega = round($tiempos->average(), 1);
                }

                // Get manual contacts from historical events
                $contactos = GestionHistoricoMedcol6::where('historia', $historia)
                    ->whereIn('tipo_evento', [
                        'CONTACTO_LLAMADA',
                        'CONTACTO_MENSAJE',
                        'CONTACTO_VISITA',
                        'OBSERVACION_GESTION'
                    ])
                    ->get();

                $totalContactos = $contactos->count();

                // Calculate contact success rate
                $contactosExitosos = $contactos->where('resultado_contacto', 'EXITOSO')->count();
                $tasaExito = $totalContactos > 0 ? round(($contactosExitosos / $totalContactos) * 100, 1) : 0;

                // Last contact
                $ultimoContacto = $contactos->sortByDesc('created_at')->first();

                // Next scheduled follow-up
                $proximoSeguimiento = GestionHistoricoMedcol6::where('historia', $historia)
                    ->where('requiere_seguimiento', true)
                    ->where('fecha_seguimiento', '>', now())
                    ->orderBy('fecha_seguimiento', 'asc')
                    ->first();

                // Get all events count by type
                $eventosPorTipo = GestionHistoricoMedcol6::where('historia', $historia)
                    ->select('tipo_evento', DB::raw('COUNT(*) as total'))
                    ->groupBy('tipo_evento')
                    ->get()
                    ->pluck('total', 'tipo_evento')
                    ->toArray();

                // Calculate frequency (pendientes per month)
                $primerPendiente = $pendientes->sortBy('created_at')->first();
                $ultimoPendiente = $pendientes->sortByDesc('created_at')->first();

                $frecuenciaMensual = null;
                if ($primerPendiente && $ultimoPendiente) {
                    $mesesTranscurridos = Carbon::parse($primerPendiente->created_at)
                        ->diffInMonths(Carbon::parse($ultimoPendiente->created_at));

                    if ($mesesTranscurridos > 0) {
                        $frecuenciaMensual = round($totalPendientes / $mesesTranscurridos, 2);
                    }
                }

                return [
                    'total_pendientes' => $totalPendientes,
                    'pendientes_por_estado' => $porEstado,
                    'tiempo_promedio_entrega_dias' => $tiempoPromedioEntrega,
                    'total_contactos_manuales' => $totalContactos,
                    'contactos_exitosos' => $contactosExitosos,
                    'tasa_exito_contacto' => $tasaExito,
                    'ultimo_contacto' => ($ultimoContacto && $ultimoContacto->created_at) ? [
                        'fecha' => $ultimoContacto->created_at->format('Y-m-d H:i:s'),
                        'tipo' => $ultimoContacto->tipo_evento,
                        'titulo' => $ultimoContacto->titulo,
                        'resultado' => $ultimoContacto->resultado_contacto
                    ] : null,
                    'proximo_seguimiento' => $proximoSeguimiento ? [
                        'fecha' => $proximoSeguimiento->fecha_seguimiento,
                        'titulo' => $proximoSeguimiento->titulo,
                        'dias_restantes' => now()->diffInDays($proximoSeguimiento->fecha_seguimiento, false)
                    ] : null,
                    'eventos_por_tipo' => $eventosPorTipo,
                    'frecuencia_mensual' => $frecuenciaMensual,
                    'primer_pendiente' => ($primerPendiente && $primerPendiente->created_at) ? $primerPendiente->created_at->format('Y-m-d') : null,
                    'ultimo_pendiente' => ($ultimoPendiente && $ultimoPendiente->created_at) ? $ultimoPendiente->created_at->format('Y-m-d') : null
                ];
            });

            if (!$metrics) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró información del paciente'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $metrics
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al obtener métricas del paciente', [
                'historia' => $historia,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las métricas del paciente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate printable HTML for patient history (for PDF export)
     * FASE 11: Print/PDF Export with Report Type Selection
     *
     * @param string $historia Patient history number
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function printPatientHistory($historia, Request $request)
    {
        try {
            // Get report type (default: detalle)
            $tipo = $request->query('tipo', 'detalle');

            // Validate tipo parameter
            if (!in_array($tipo, ['resumen', 'detalle'])) {
                $tipo = 'detalle';
            }

            // Get patient history
            $eventos = GestionHistoricoMedcol6::where('historia', $historia)
                ->with(['usuario:id,name', 'pendiente:id,factura,codigo,nombre'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Get patient info
            $paciente = PendienteApiMedcol6::where('historia', $historia)
                ->orderBy('id', 'desc')
                ->first();

            if (!$paciente) {
                abort(404, 'Paciente no encontrado');
            }

            // Get pendientes based on report type
            // Detallado: Only PENDIENTE status (active pending items)
            // Resumen: Only ENTREGADO status (delivered items)
            $pendientes = [];
            $tituloSeccionPendientes = '';

            if ($tipo === 'detalle') {
                // Detailed report: Show only PENDING items
                $pendientes = PendienteApiMedcol6::where('historia', $historia)
                    ->where('estado', 'PENDIENTE')
                    ->orderBy('fecha', 'desc')
                    ->get();
                $tituloSeccionPendientes = 'Pendientes Activos';
            } else {
                // Summary report: Show only DELIVERED items
                $pendientes = PendienteApiMedcol6::where('historia', $historia)
                    ->where('estado', 'ENTREGADO')
                    ->orderBy('fecha', 'desc')
                    ->get();
                $tituloSeccionPendientes = 'Medicamentos Entregados';
            }

            // Calculate metrics
            $totalPendientes = PendienteApiMedcol6::where('historia', $historia)->count();
            $totalEventos = $eventos->count();
            $totalContactos = $eventos->whereIn('tipo_evento', [
                'CONTACTO_LLAMADA', 'CONTACTO_MENSAJE', 'CONTACTO_VISITA', 'OBSERVACION_GESTION'
            ])->count();
            $contactosExitosos = $eventos->where('resultado_contacto', 'EXITOSO')->count();
            $tasaExito = $totalContactos > 0 ? round(($contactosExitosos / $totalContactos) * 100, 1) : 0;

            // Format patient name
            $nombreCompleto = trim(sprintf(
                '%s %s %s %s',
                $paciente->nombre1 ?? '',
                $paciente->nombre2 ?? '',
                $paciente->apellido1 ?? '',
                $paciente->apellido2 ?? ''
            ));

            // Determine report type title
            $tipoInforme = $tipo === 'resumen' ? 'RESUMEN' : 'DETALLADO';

            return view('smart-pendi.patient-history-print', [
                'paciente' => $paciente,
                'nombreCompleto' => $nombreCompleto,
                'eventos' => $eventos,
                'pendientes' => $pendientes,
                'tituloSeccionPendientes' => $tituloSeccionPendientes,
                'totalPendientes' => $totalPendientes,
                'totalEventos' => $totalEventos,
                'totalContactos' => $totalContactos,
                'contactosExitosos' => $contactosExitosos,
                'tasaExito' => $tasaExito,
                'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
                'tipo' => $tipo,
                'tipoInforme' => $tipoInforme
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al generar vista de impresión del histórico', [
                'historia' => $historia,
                'error' => $e->getMessage()
            ]);

            abort(500, 'Error al generar el documento de impresión');
        }
    }

    /**
     * Get medication frequency analysis for a patient
     * FASE 12: Medication Frequency Analysis
     *
     * @param string $historia Patient history number
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMedicationFrequency($historia)
    {
        try {
            // Get all pendientes for this patient (ordered by date)
            $pendientes = PendienteApiMedcol6::where('historia', $historia)
                ->select('codigo', 'nombre', 'fecha', 'estado', 'factura')
                ->orderBy('fecha', 'asc')
                ->get();

            if ($pendientes->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron pendientes para esta historia'
                ], 404);
            }

            // Group by medication (codigo)
            $medicationGroups = $pendientes->groupBy('codigo');

            $frequencyAnalysis = [];

            foreach ($medicationGroups as $codigo => $items) {
                $totalOccurrences = $items->count();

                // Skip if only one occurrence
                if ($totalOccurrences < 2) {
                    $frequencyAnalysis[] = [
                        'codigo' => $codigo,
                        'nombre' => $items->first()->nombre,
                        'total_pendientes' => $totalOccurrences,
                        'frecuencia_dias' => null,
                        'primera_fecha' => $items->first()->fecha,
                        'ultima_fecha' => $items->first()->fecha,
                        'dias_transcurridos' => 0,
                        'pendientes' => $items->map(function($item) {
                            return [
                                'fecha' => $item->fecha,
                                'estado' => $item->estado,
                                'factura' => $item->factura
                            ];
                        })->values()
                    ];
                    continue;
                }

                // Calculate time between occurrences
                $fechas = $items->pluck('fecha')->map(function($fecha) {
                    return \Carbon\Carbon::parse($fecha);
                })->sort()->values();

                $primeraFecha = $fechas->first();
                $ultimaFecha = $fechas->last();
                $diasTranscurridos = $primeraFecha->diffInDays($ultimaFecha);

                // Calculate average frequency (days between pendientes)
                $frecuenciaDias = $totalOccurrences > 1
                    ? round($diasTranscurridos / ($totalOccurrences - 1), 1)
                    : null;

                $frequencyAnalysis[] = [
                    'codigo' => $codigo,
                    'nombre' => $items->first()->nombre,
                    'total_pendientes' => $totalOccurrences,
                    'frecuencia_dias' => $frecuenciaDias,
                    'primera_fecha' => $primeraFecha->format('Y-m-d'),
                    'ultima_fecha' => $ultimaFecha->format('Y-m-d'),
                    'dias_transcurridos' => $diasTranscurridos,
                    'pendientes' => $items->map(function($item) {
                        return [
                            'fecha' => $item->fecha,
                            'estado' => $item->estado,
                            'factura' => $item->factura
                        ];
                    })->values()
                ];
            }

            // Sort by total occurrences descending
            usort($frequencyAnalysis, function($a, $b) {
                return $b['total_pendientes'] - $a['total_pendientes'];
            });

            // Get patient info
            $paciente = $pendientes->first();
            $pacienteInfo = PendienteApiMedcol6::where('historia', $historia)
                ->select('historia', 'documento', 'nombre1', 'nombre2', 'apellido1', 'apellido2')
                ->first();

            $nombreCompleto = trim(sprintf(
                '%s %s %s %s',
                $pacienteInfo->nombre1 ?? '',
                $pacienteInfo->nombre2 ?? '',
                $pacienteInfo->apellido1 ?? '',
                $pacienteInfo->apellido2 ?? ''
            ));

            return response()->json([
                'success' => true,
                'data' => [
                    'paciente' => [
                        'historia' => $historia,
                        'documento' => $pacienteInfo->documento ?? 'N/A',
                        'nombre_completo' => $nombreCompleto
                    ],
                    'medicamentos' => $frequencyAnalysis,
                    'total_medicamentos_diferentes' => count($frequencyAnalysis),
                    'total_pendientes' => $pendientes->count()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al obtener análisis de frecuencia', [
                'historia' => $historia,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el análisis de frecuencia',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}