<?php

namespace App\Http\Controllers\Medcol6\ExportarExcel;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Exports\DataExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportExcel(Request $request)
    {
        $filters = $request->only(['fechaini', 'fechafin', 'contrato']);

        return Excel::download(new DataExport($filters), 'informe_facturas.xlsx');
    }
}