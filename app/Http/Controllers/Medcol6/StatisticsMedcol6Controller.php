<?php

namespace App\Http\Controllers\Medcol6;

use App\Http\Controllers\Controller;
use App\Models\Medcol6\PendienteApiMedcol6;
use App\Models\Medcol6\DispensadoApiMedcol6;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsMedcol6Controller extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $fechaInicio = Carbon::now()->startOfMonth()->format('Y-m-d');
        $fechaFin = Carbon::now()->endOfMonth()->format('Y-m-d');

        $estadisticas = $this->getEstadisticas($fechaInicio, $fechaFin);

        return view('medcol6.statistics.index', compact('estadisticas', 'fechaInicio', 'fechaFin'));
    }

    public function getEstadisticas($fechaInicio, $fechaFin)
    {
        // Estadísticas por estado de pendientes
        $estadisticasPorEstado = $this->getEstadisticasPorEstado($fechaInicio, $fechaFin);

        // Tendencias por mes
        $tendenciasPorMes = $this->getTendenciasPorMes($fechaInicio, $fechaFin);

        // Top medicamentos pendientes por valor
        $topMedicamentosPendientes = $this->getTopMedicamentosPendientes($fechaInicio, $fechaFin);

        return [
            'estadisticas_por_estado' => $estadisticasPorEstado,
            'tendencias_por_mes' => $tendenciasPorMes,
            'top_medicamentos_pendientes' => $topMedicamentosPendientes
        ];
    }

    private function getEstadisticasPorEstado($fechaInicio, $fechaFin)
    {
        $query = PendienteApiMedcol6::whereBetween('fecha', [$fechaInicio, $fechaFin]);

        $estadisticas = $query->select('estado',
            DB::raw('COUNT(*) as total_pendientes'),
            DB::raw('SUM(CAST(cantord as DECIMAL(10,2))) as total_cantidad'))
            ->groupBy('estado')
            ->get();

        $estadisticasConValor = [];

        foreach ($estadisticas as $estado) {
            $valorTotal = $this->calcularValorPorEstado($estado->estado, $fechaInicio, $fechaFin);

            $estadisticasConValor[] = [
                'estado' => $estado->estado,
                'total_pendientes' => $estado->total_pendientes,
                'total_cantidad' => $estado->total_cantidad,
                'valor_total' => $valorTotal
            ];
        }

        return $estadisticasConValor;
    }

    private function calcularValorPorEstado($estado, $fechaInicio, $fechaFin)
    {
        $pendientes = PendienteApiMedcol6::where('estado', $estado)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->get();

        $valorTotal = 0;

        foreach ($pendientes as $pendiente) {
            $dispensado = DispensadoApiMedcol6::where('codigo', $pendiente->codigo)->first();

            if ($dispensado && $dispensado->precio_unitario) {
                $precioUnitario = floatval($dispensado->precio_unitario);
                $cantidad = floatval($pendiente->cantord ?? 0);
                $valorTotal += $precioUnitario * $cantidad;
            }
        }

        return $valorTotal;
    }

    private function getTendenciasPorMes($fechaInicio, $fechaFin)
    {
        $tendencias = PendienteApiMedcol6::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->select(
                DB::raw('YEAR(fecha) as año'),
                DB::raw('MONTH(fecha) as mes'),
                DB::raw('estado'),
                DB::raw('COUNT(*) as total_pendientes')
            )
            ->groupBy('año', 'mes', 'estado')
            ->orderBy('año')
            ->orderBy('mes')
            ->get();

        $tendenciasAgrupadas = [];

        foreach ($tendencias as $tendencia) {
            $key = $tendencia->año . '-' . str_pad($tendencia->mes, 2, '0', STR_PAD_LEFT);

            if (!isset($tendenciasAgrupadas[$key])) {
                $tendenciasAgrupadas[$key] = [
                    'año' => $tendencia->año,
                    'mes' => $tendencia->mes,
                    'estados' => []
                ];
            }

            $tendenciasAgrupadas[$key]['estados'][$tendencia->estado] = $tendencia->total_pendientes;
        }

        return array_values($tendenciasAgrupadas);
    }

    private function getTopMedicamentosPendientes($fechaInicio, $fechaFin, $limit = 10)
    {
        $pendientes = PendienteApiMedcol6::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->select('codigo', 'nombre', DB::raw('SUM(CAST(cantord as DECIMAL(10,2))) as total_cantidad'), DB::raw('COUNT(*) as total_pendientes'))
            ->groupBy('codigo', 'nombre')
            ->get();

        $medicamentosConValor = [];

        foreach ($pendientes as $pendiente) {
            $dispensado = DispensadoApiMedcol6::where('codigo', $pendiente->codigo)->first();

            $valorTotal = 0;
            if ($dispensado && $dispensado->precio_unitario) {
                $precioUnitario = floatval($dispensado->precio_unitario);
                $valorTotal = $precioUnitario * floatval($pendiente->total_cantidad);
            }

            $medicamentosConValor[] = [
                'codigo' => $pendiente->codigo,
                'nombre' => $pendiente->nombre,
                'total_cantidad' => $pendiente->total_cantidad,
                'total_pendientes' => $pendiente->total_pendientes,
                'valor_total' => $valorTotal
            ];
        }

        usort($medicamentosConValor, function($a, $b) {
            return $b['valor_total'] <=> $a['valor_total'];
        });

        return array_slice($medicamentosConValor, 0, $limit);
    }

    public function getEstadisticasAjax(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $estadisticas = $this->getEstadisticas($fechaInicio, $fechaFin);

        return response()->json($estadisticas);
    }

    public function getValorPendientePorFacturar($fechaInicio, $fechaFin)
    {
        $pendientesNoEntregados = PendienteApiMedcol6::whereIn('estado', ['PENDIENTE', 'DESABASTECIDO', 'SIN CONTACTO', 'TRAMITADO', 'VENCIDO'])
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->get();

        $valorTotalPendiente = 0;

        foreach ($pendientesNoEntregados as $pendiente) {
            $dispensado = DispensadoApiMedcol6::where('codigo', $pendiente->codigo)->first();

            if ($dispensado && $dispensado->precio_unitario) {
                $precioUnitario = floatval($dispensado->precio_unitario);
                $cantidad = floatval($pendiente->cantord ?? 0);
                $valorTotalPendiente += $precioUnitario * $cantidad;
            }
        }

        return $valorTotalPendiente;
    }

    public function getValorEntregado($fechaInicio, $fechaFin)
    {
        $pendientesEntregados = PendienteApiMedcol6::where('estado', 'ENTREGADO')
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->get();

        $valorTotalEntregado = 0;

        foreach ($pendientesEntregados as $pendiente) {
            $dispensado = DispensadoApiMedcol6::where('codigo', $pendiente->codigo)->first();

            if ($dispensado && $dispensado->precio_unitario) {
                $precioUnitario = floatval($dispensado->precio_unitario);
                $cantidad = floatval($pendiente->cantord ?? 0);
                $valorTotalEntregado += $precioUnitario * $cantidad;
            }
        }

        return $valorTotalEntregado;
    }
}