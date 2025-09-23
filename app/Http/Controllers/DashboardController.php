<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medcol6\DispensadoApiMedcol6;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $contratos = DispensadoApiMedcol6::select('centroprod')
            ->distinct()
            ->orderBy('centroprod')
            ->pluck('centroprod');

        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', Carbon::now()->format('Y-m-d'));
        $contrato = $request->get('contrato', 'all');

        $estadisticas = $this->getEstadisticas($fechaInicio, $fechaFin, $contrato);

        return view('dashboard.index', compact('contratos', 'fechaInicio', 'fechaFin', 'contrato', 'estadisticas'));
    }

    public function getEstadisticas($fechaInicio, $fechaFin, $contrato)
    {
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
    }

    public function getEstadisticasAjax(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $contrato = $request->get('contrato', 'all');

        $estadisticas = $this->getEstadisticas($fechaInicio, $fechaFin, $contrato);

        return response()->json($estadisticas);
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
}