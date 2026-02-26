<?php

namespace App\Http\Controllers\Medcol6;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Medcol6\SaldosMedcol6;

class RotacionMedicamentosController extends Controller
{
    /** @var array<int,string> Nombres cortos de cada mes */
    private array $meses = [
        1  => 'Ene', 2  => 'Feb', 3  => 'Mar', 4  => 'Abr',
        5  => 'May', 6  => 'Jun', 7  => 'Jul', 8  => 'Ago',
        9  => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic',
    ];

    // ── Vista principal ──────────────────────────────────────────────────────

    public function index()
    {
        $anioActual  = (int) date('Y');
        $anios       = $this->getAniosDisponibles();
        $depositos   = $this->getDepositosDisponibles();
        $agrupadores = $this->getAgrupadores();

        return view('menu.Medcol6.rotacion.index', compact('anioActual', 'anios', 'depositos', 'agrupadores'));
    }

    // ── Endpoint de datos (AJAX) ─────────────────────────────────────────────

    public function getData(Request $request)
    {
        $anioRaw   = $request->get('anio');
        $anio      = ($anioRaw !== null && $anioRaw !== '') ? (int) $anioRaw : null;
        $deposito  = $request->get('deposito')  ?: null;
        $agrupador = $request->get('agrupador') ?: null;

        // ── 1. Dispensado agrupado por código / nombre / mes / farmacia ─────────────────
        $dispensado = DB::table('dispensado_medcol6')
            ->select([
                'codigo',
                'nombre_generico',
                DB::raw('YEAR(fecha_suministro) as anio'),
                DB::raw('MONTH(fecha_suministro) as mes'),
                DB::raw("SUM(CAST(REPLACE(COALESCE(numero_unidades,'0'), ',', '') AS DECIMAL(15,2))) as total"),
            ])
            ->when($anio, fn ($q) => $q->whereYear('fecha_suministro', $anio))
            ->whereNotNull('codigo')
            ->where('codigo', '<>', '')
            ->whereNotIn('codigo', ['1010', '1011', '1012'])
            ->when($deposito,  fn ($q) => $q->where('centroprod', $deposito))
            ->when($agrupador, fn ($q) => $q->whereRaw("SUBSTRING_INDEX(codigo, '-', 1) = ?", [$agrupador]))
            ->whereIn('estado', ['DISPENSADO', 'REVISADO'])
            ->groupBy('codigo', 'nombre_generico', DB::raw('YEAR(fecha_suministro)'), DB::raw('MONTH(fecha_suministro)'))
            ->orderBy('codigo')
            ->orderBy(DB::raw('YEAR(fecha_suministro)'))
            ->orderBy('mes')
            ->get();

        // ── 2. Marcas por código ──────────────────────────────────────────────
        $marcas = $this->getMarcas();

        // ── 3. Último saldo por código (sumado sobre depósitos, o filtrado) ──
        $subLatest = DB::table('saldos_medcol6')
            ->select('codigo', 'deposito', DB::raw('MAX(fecha_saldo) as max_fecha'))
            ->groupBy('codigo', 'deposito');

        $saldosQ = DB::table('saldos_medcol6 as s')
            ->joinSub($subLatest, 'lat', function ($join) {
                $join->on('s.codigo',      '=', 'lat.codigo')
                     ->on('s.deposito',    '=', 'lat.deposito')
                     ->on('s.fecha_saldo', '=', 'lat.max_fecha');
            })
            ->select('s.codigo', DB::raw('SUM(s.saldo) as saldo_total'));

        if ($deposito) {
            $saldosQ->where('s.deposito', $deposito);
        }

        $saldos = $saldosQ->groupBy('s.codigo')->pluck('saldo_total', 'codigo');

        // ── 4. Pivot en PHP: una entrada por código ───────────────────────────
        $pivot = [];

        foreach ($dispensado as $row) {
            $codigo    = $row->codigo;
            $agrupadorVal = strstr($codigo, '-', true) ?: $codigo;   // parte antes del primer '-'

            if (!isset($pivot[$codigo])) {
                $pivot[$codigo] = [
                    'codigo'          => $codigo,
                    'agrupador'       => $agrupadorVal,
                    'nombre_generico' => $row->nombre_generico ?? '',
                    'marca'           => $marcas[$codigo] ?? '',
                    'meses'           => array_fill(1, 12, null),
                ];
            }

            // Acumular: cuando se consultan varios años, el mismo mes aparece varias veces
            $pivot[$codigo]['meses'][(int) $row->mes] =
                ($pivot[$codigo]['meses'][(int) $row->mes] ?? 0) + (float) $row->total;
        }

        // ── 5. Calcular métricas ─────────────────────────────────────────────
        $result = [];

        foreach ($pivot as $codigo => $data) {
            $valores    = array_filter($data['meses'], fn ($v) => $v !== null && $v > 0);
            $count      = count($valores);
            $promedio   = $count > 0 ? array_sum($valores) / $count : 0;
            $minVal     = $count > 0 ? (float) min($valores) : 0;
            $maxVal     = $count > 0 ? (float) max($valores) : 0;
            $promDiario = $count > 0 ? round($promedio / 30, 2) : 0;
            $saldoAct   = (float) ($saldos[$codigo] ?? 0);
            $faltante   = max(0.0, $promedio - $saldoAct);

            $row = [
                'codigo'          => $data['codigo'],
                'agrupador'       => $data['agrupador'],
                'nombre_generico' => $data['nombre_generico'],
                'marca'           => $data['marca'],
                'promedio'        => round($promedio, 1),
                'rango'           => number_format($minVal, 0, '.', '') . ' – ' . number_format($maxVal, 0, '.', ''),
                'promedio_diario' => $promDiario,
                'saldo_actual'    => (int) round($saldoAct),
                'faltante'        => (int) round($faltante),
                'meses_con_datos' => $count,
            ];

            for ($m = 1; $m <= 12; $m++) {
                $v          = $data['meses'][$m];
                $row['mes_' . $m] = $v !== null ? (int) round($v) : null;
            }

            $result[] = $row;
        }

        usort($result, fn ($a, $b) => strcmp((string) $a['nombre_generico'], (string) $b['nombre_generico']));

        return response()->json(['data' => $result]);
    }

    // ── Endpoint de detalle por agrupador (AJAX modal) ──────────────────────

    public function getDetalle(Request $request)
    {
        $agrupador = $request->get('agrupador') ?: null;
        $anioRaw   = $request->get('anio');
        $anio      = ($anioRaw !== null && $anioRaw !== '') ? (int) $anioRaw : null;
        $deposito  = $request->get('deposito')  ?: null;

        if (!$agrupador) {
            return response()->json(['data' => []]);
        }

        $rows = DB::table('dispensado_medcol6')
            ->leftJoin('medicamentos_api_medcol3 as m3', 'dispensado_medcol6.codigo', '=', 'm3.codigo')
            ->select([
                'dispensado_medcol6.codigo',
                'dispensado_medcol6.nombre_generico',
                DB::raw('dispensado_medcol6.centroprod as farmacia'),
                DB::raw("SUM(CAST(REPLACE(COALESCE(dispensado_medcol6.numero_unidades,'0'), ',', '') AS DECIMAL(15,2))) as total_unidades"),
                DB::raw('COUNT(DISTINCT dispensado_medcol6.historia) as total_pacientes'),
                DB::raw("COALESCE(m3.marca, '') as marca"),
            ])
            ->when($anio, fn ($q) => $q->whereYear('dispensado_medcol6.fecha_suministro', $anio))
            ->whereRaw("SUBSTRING_INDEX(dispensado_medcol6.codigo, '-', 1) = ?", [$agrupador])
            ->when($deposito, fn ($q) => $q->where('dispensado_medcol6.centroprod', $deposito))
            ->whereIn('dispensado_medcol6.estado', ['DISPENSADO', 'REVISADO'])
            ->whereNotIn('dispensado_medcol6.codigo', ['1010', '1011', '1012'])
            ->groupBy('dispensado_medcol6.codigo', 'dispensado_medcol6.nombre_generico', 'dispensado_medcol6.centroprod', 'm3.marca')
            ->orderBy('dispensado_medcol6.codigo')
            ->get();

        return response()->json(['data' => $rows]);
    }

    // ── Helpers privados ────────────────────────────────────────────────────

    private function getAniosDisponibles(): array
    {
        return DB::table('dispensado_medcol6')
            ->selectRaw('DISTINCT YEAR(fecha_suministro) as anio')
            ->whereNotNull('fecha_suministro')
            ->orderByDesc('anio')
            ->pluck('anio')
            ->toArray();
    }

    private function getAgrupadores(): array
    {
        return DB::table('dispensado_medcol6')
            ->selectRaw("DISTINCT SUBSTRING_INDEX(codigo, '-', 1) as agrupador")
            ->whereNotNull('codigo')
            ->where('codigo', '<>', '')
            ->orderBy('agrupador')
            ->pluck('agrupador')
            ->toArray();
    }

    private function getDepositosDisponibles(): array
    {
        return DB::table('saldos_medcol6')
            ->select('deposito', 'nombre_deposito')
            ->whereNotNull('deposito')
            ->where('deposito', '<>', '')
            ->groupBy('deposito', 'nombre_deposito')
            ->orderBy('deposito')
            ->get()
            ->toArray();
    }

    private function getMarcas(): array
    {
        return DB::table('medicamentos_api_medcol3')
            ->whereNotNull('codigo')
            ->where('codigo', '<>', '')
            ->pluck('marca', 'codigo')
            ->toArray();
    }
}
