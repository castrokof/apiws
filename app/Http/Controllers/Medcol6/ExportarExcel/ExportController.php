<?php

namespace App\Http\Controllers\Medcol6\ExportarExcel;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Exports\DataExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Medcol6\DispensadoApiMedcol6;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function exportExcel(Request $request)
    {
        // Validar que fechaini y fechafin sean obligatorios
        if (empty($request->fechaini) || empty($request->fechafin)) {
            // Si no se pasan fechas, usar el día actual por defecto
            $fechaIni = Carbon::now()->startOfDay()->toDateTimeString();
            $fechaFin = Carbon::now()->endOfDay()->toDateTimeString();
        } else {
            $fechaIni = Carbon::parse($request->fechaini)->startOfDay()->toDateTimeString();
            $fechaFin = Carbon::parse($request->fechafin)->endOfDay()->toDateTimeString();
        }

        // Construcción de la consulta
        $query = DispensadoApiMedcol6::whereBetween('fecha_suministro', [$fechaIni, $fechaFin]);

        // Aplicar filtro de estado si se envía, sino por defecto "REVISADO"
        $query->where('estado', $request->estado ?? 'REVISADO');

        // Aplicar contrato si está presente
        if (!empty($request->contrato)) {
            $query->where('centroprod', $request->contrato);
        }

        // Aplicar cobertura si está presente
        if (!empty($request->cobertura)) {
            $query->where('tipo_medicamento', $request->cobertura);
        }

        // Obtener los datos filtrados
        $filteredData = $query->get();

        // Exportar a Excel
        return Excel::download(new DataExport($filteredData), 'informe_facturas.xlsx');
    }
}

/* class ExportController extends Controller
{
    public function exportExcel(Request $request)
    {
        $filters = $request->only(['fechaini', 'fechafin', 'contrato', 'cobertura']);

        return Excel::download(new DataExport($filters), 'informe_facturas.xlsx');
    }
} */