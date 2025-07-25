<?php

namespace App\Http\Controllers\Medcol6;

use App\Http\Controllers\Controller;
use App\Models\Medcol6\SaldosMedcol6;
use App\Services\SaldosApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class SaldosMedcol6Controller extends Controller
{
    private $saldosApiService;

    public function __construct(SaldosApiService $saldosApiService)
    {
        $this->saldosApiService = $saldosApiService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('menu.Medcol6.saldos.indexSaldos');
    }

    /**
     * Obtener saldos para DataTables
     */
    public function getSaldos(Request $request)
    {
        // Debug logging
        Log::info('getSaldos called', [
            'ajax' => $request->ajax(),
            'has_draw' => $request->has('draw'),
            'all_params' => $request->all()
        ]);

        // DataTables puede usar GET o POST, verificamos ambos
        if ($request->ajax() || $request->has('draw')) {
            try {
                $query = SaldosMedcol6::query();

                // Aplicar filtros según los parámetros recibidos
                if ($request->filled('deposito') && $request->deposito !== 'todos') {
                    $query->where('deposito', $request->deposito);
                }

                if ($request->filled('grupo') && $request->grupo !== 'todos') {
                    $query->where('grupo', $request->grupo);
                }

                if ($request->filled('estado_vencimiento') && $request->estado_vencimiento !== 'todos') {
                    $hoy = Carbon::now();

                    switch ($request->estado_vencimiento) {
                        case 'vigente':
                            $query->where('fecha_vencimiento', '>', $hoy->copy()->addDays(90));
                            break;
                        case 'proximo_vencer':
                            $query->whereBetween('fecha_vencimiento', [
                                $hoy->copy()->addDay(),
                                $hoy->copy()->addDays(30)
                            ]);
                            break;
                        case 'vencido':
                            $query->where('fecha_vencimiento', '<', $hoy);
                            break;
                    }
                }

                if ($request->filled('con_saldo') && $request->con_saldo === '1') {
                    $query->where('saldo', '>', 0);
                }

                if ($request->filled('buscar')) {
                    $buscar = $request->buscar;
                    $query->where(function ($q) use ($buscar) {
                        $q->where('codigo', 'like', "%{$buscar}%")
                            ->orWhere('nombre', 'like', "%{$buscar}%")
                            ->orWhere('cums', 'like', "%{$buscar}%");
                    });
                }

                // Obtener los resultados ordenados
                $saldos = $query->orderBy('fecha_saldo', 'desc')
                    ->orderBy('nombre', 'asc')
                    ->get();

                return DataTables::of($saldos)
                    ->addColumn('estado_vencimiento', function ($saldo) {
                        $estado = $saldo->estado_vencimiento;
                        $class = match ($estado) {
                            'Vencido' => 'badge-danger',
                            'Próximo a vencer' => 'badge-warning',
                            'Vigente (corto plazo)' => 'badge-info',
                            'Vigente' => 'badge-success',
                            default => 'badge-secondary'
                        };
                        return "<span class='badge {$class}'>{$estado}</span>";
                    })
                    ->addColumn('saldo_formatted', function ($saldo) {
                        return number_format($saldo->saldo, 2);
                    })
                    ->addColumn('total_formatted', function ($saldo) {
                        return '$' . number_format($saldo->total, 2);
                    })
                    ->addColumn('fecha_vencimiento_formatted', function ($saldo) {
                        return $saldo->fecha_vencimiento
                            ? Carbon::parse($saldo->fecha_vencimiento)->format('d/m/Y')
                            : 'N/A';
                    })
                    ->addColumn('accion', function ($saldo) {
                        return '<button type="button" class="btn btn-info btn-sm ver-detalle" 
                                data-id="' . $saldo->id . '" 
                                title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </button>';
                    })
                    ->rawColumns(['estado_vencimiento', 'accion'])
                    ->make(true);
            } catch (\Exception $e) {
                Log::error('Error in getSaldos', [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'error' => 'Error al procesar la solicitud',
                    'message' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
                ], 500);
            }
        }

        return response()->json(['error' => 'Solicitud no válida'], 400);
    }

    /**
     * Probar conexión con la API externa (diagnóstico)
     */
    public function probarApi(Request $request)
    {
        $usuario = Auth::user()->email ?? 'system';

        try {
            $resultado = $this->saldosApiService->probarApi($usuario);

            return response()->json($resultado);
        } catch (\Exception $e) {
            Log::error('Error en controlador de prueba de API', [
                'error' => $e->getMessage(),
                'usuario' => $usuario
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno en la prueba de API'
            ], 500);
        }
    }

    /**
     * Sincronizar saldos desde la API externa
     */
    public function sincronizarSaldos(Request $request)
    {
        $usuario = Auth::user()->email ?? 'system';

        try {
            $resultado = $this->saldosApiService->sincronizar($usuario);

            if ($resultado['success']) {
                return response()->json($resultado);
            } else {
                return response()->json($resultado, 500);
            }
        } catch (\Exception $e) {
            Log::error('Error en controlador de sincronización de saldos', [
                'error' => $e->getMessage(),
                'usuario' => $usuario
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno en la sincronización'
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de saldos
     */
    public function getEstadisticas(Request $request)
    {
        try {
            $resultado = $this->saldosApiService->obtenerEstadisticas();

            if ($resultado['success']) {
                return response()->json($resultado);
            } else {
                return response()->json($resultado, 500);
            }
        } catch (\Exception $e) {
            Log::error('Error en controlador de estadísticas de saldos', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno al obtener estadísticas'
            ], 500);
        }
    }

    /**
     * Ver detalle de un saldo específico
     */
    public function show($id)
    {
        try {
            $saldo = SaldosMedcol6::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $saldo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo no encontrado'
            ], 404);
        }
    }

    /**
     * Obtener opciones para filtros
     */
    public function getOpcionesFiltros()
    {
        try {
            $fechaActual = Carbon::now()->format('Y-m-d');

            // Obtener depósitos únicos con sus nombres
            $depositos = SaldosMedcol6::whereDate('fecha_saldo', $fechaActual)
                ->select('deposito', 'nombre_deposito')
                ->distinct()
                ->whereNotNull('deposito')
                ->where('deposito', '!=', '')
                ->orderBy('deposito')
                ->get()
                ->map(function ($item) {
                    return [
                        'value' => $item->deposito,
                        'label' => $item->nombre_deposito ?: $item->deposito,
                        'deposito' => $item->deposito, // Para compatibilidad
                        'nombre_deposito' => $item->nombre_deposito
                    ];
                });

            // Obtener grupos únicos con sus nombres
            $grupos = SaldosMedcol6::whereDate('fecha_saldo', $fechaActual)
                ->select('grupo', 'nombre_grupo')
                ->distinct()
                ->whereNotNull('grupo')
                ->where('grupo', '!=', '')
                ->orderBy('grupo')
                ->get()
                ->map(function ($item) {
                    return [
                        'value' => $item->grupo,
                        'label' => $item->nombre_grupo ?: "Grupo {$item->grupo}",
                        'grupo' => $item->grupo, // Para compatibilidad
                        'nombre_grupo' => $item->nombre_grupo
                    ];
                });

            Log::info('Opciones de filtros obtenidas', [
                'depositos_count' => $depositos->count(),
                'grupos_count' => $grupos->count(),
                'depositos' => $depositos->pluck('value')->toArray(),
                'grupos' => $grupos->pluck('value')->toArray()
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'depositos' => $depositos,
                    'grupos' => $grupos
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener opciones de filtros', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener opciones de filtros: ' . $e->getMessage()
            ], 500);
        }
    }
}
