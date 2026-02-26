<?php

namespace App\Http\Controllers\Medcol6;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DemandDrivenController extends Controller
{
    // ── Constantes Demand Driven ─────────────────────────────────────────────
    private const LT  = 7;       // Lead Time en días (fijo)
    private const Z   = 1.65;    // Factor Z de seguridad (95 %)
    private const S   = 8400;    // Costo de hacer un pedido ($)
    private const H   = 1500;    // Costo de almacenamiento por unidad ($)

    // ── Vista principal ──────────────────────────────────────────────────────

    public function index()
    {
        $anioActual  = (int) date('Y');
        $anios       = $this->getAniosDisponibles();
        $depositos   = $this->getDepositosDisponibles();
        $agrupadores = $this->getAgrupadores();

        $params = [
            'lt' => self::LT,
            'z'  => self::Z,
            's'  => number_format(self::S,  0, ',', '.'),
            'h'  => number_format(self::H,  0, ',', '.'),
        ];

        return view('menu.Medcol6.demanddriven.index',
            compact('anioActual', 'anios', 'depositos', 'agrupadores', 'params'));
    }

    // ── Endpoint tabla (AJAX) ────────────────────────────────────────────────

    public function getData(Request $request)
    {
        $anioRaw   = $request->get('anio');
        $anio      = ($anioRaw !== null && $anioRaw !== '') ? (int) $anioRaw : null;
        $deposito  = $request->get('deposito')  ?: null;
        $agrupador = $request->get('agrupador') ?: null;

        // ── 1. Subquery: totales diarios por (codigo, nombre) ────────────────
        //    STDDEV_SAMP y AVG calculados directamente en MySQL para eficiencia.
        $stats = DB::table(function ($q) use ($anio, $deposito, $agrupador) {
            $q->from('dispensado_medcol6')
              ->select([
                  'codigo',
                  'nombre_generico',
                  DB::raw('DATE(fecha_suministro) as fecha'),
                  DB::raw("SUM(CAST(REPLACE(COALESCE(numero_unidades,'0'),',','') AS DECIMAL(15,2))) as unidades_dia"),
              ])
              ->whereNotNull('codigo')
              ->where('codigo', '<>', '')
              ->whereNotIn('codigo', ['1010', '1011', '1012'])
              ->whereIn('estado', ['DISPENSADO', 'REVISADO'])
              ->when($anio,      fn ($q2) => $q2->whereYear('fecha_suministro', $anio))
              ->when($deposito,  fn ($q2) => $q2->where('centroprod', $deposito))
              ->when($agrupador, fn ($q2) => $q2->whereRaw("SUBSTRING_INDEX(codigo,'-',1) = ?", [$agrupador]))
              ->groupBy('codigo', 'nombre_generico', DB::raw('DATE(fecha_suministro)'));
        }, 'diarios')
        ->select([
            'codigo',
            'nombre_generico',
            DB::raw('COUNT(*)                  AS dias_con_demanda'),
            DB::raw('SUM(unidades_dia)          AS total_unidades'),
            DB::raw('AVG(unidades_dia)          AS promedio_diario'),
            DB::raw('STDDEV_SAMP(unidades_dia)  AS std_dev'),
        ])
        ->groupBy('codigo', 'nombre_generico')
        ->orderBy('nombre_generico')
        ->get();

        // ── 2. Saldo actual por código ────────────────────────────────────────
        $saldos = $this->getSaldos($deposito);

        // ── 3. Marcas por código ──────────────────────────────────────────────
        $marcas = $this->getMarcas();

        // ── 4. Calcular métricas Demand Driven ───────────────────────────────
        $result = [];

        foreach ($stats as $row) {
            $d     = (float) $row->promedio_diario;
            $sigma = (float) ($row->std_dev ?? 0);
            $total = (float) $row->total_unidades;
            $n     = (int)   $row->dias_con_demanda;

            // Safety Stock:  SS = Z × σ × √LT
            $SS = self::Z * $sigma * sqrt(self::LT);

            // Reorder Point: ROP = d̄ × LT + SS
            $ROP = ($d * self::LT) + $SS;

            // Demanda anualizada para EOQ
            $D_anual = $d * 365;

            // Economic Order Quantity: EOQ = √(2DS/H)
            $EOQ = $D_anual > 0
                ? sqrt((2 * $D_anual * self::S) / self::H)
                : 0;

            $saldo    = (float) ($saldos[$row->codigo] ?? 0);
            $diasCob  = $d > 0 ? round($saldo / $d, 1) : null;

            // Estado según nivel de stock
            if ($d <= 0) {
                $estado = 'SIN_DEMANDA';
            } elseif ($saldo <= $SS) {
                $estado = 'CRITICO';
            } elseif ($saldo <= $ROP) {
                $estado = 'REORDENAR';
            } elseif ($saldo <= ($ROP + $EOQ)) {
                $estado = 'NORMAL';
            } else {
                $estado = 'SOBRESTOCK';
            }

            $result[] = [
                'codigo'           => $row->codigo,
                'nombre_generico'  => $row->nombre_generico ?? '',
                'marca'            => $marcas[$row->codigo] ?? '',
                'total_unidades'   => (int) round($total),
                'dias_con_demanda' => $n,
                'promedio_diario'  => round($d,     2),
                'std_dev'          => round($sigma, 2),
                'stock_seguridad'  => round($SS,    1),
                'punto_reorden'    => round($ROP,   1),
                'eoq'              => (int) round($EOQ),
                'saldo_actual'     => (int) round($saldo),
                'dias_cobertura'   => $diasCob,
                'estado'           => $estado,
            ];
        }

        return response()->json(['data' => $result]);
    }

    // ── Endpoint histórico para gráfico (AJAX) ───────────────────────────────

    public function getHistorico(Request $request)
    {
        $codigo   = $request->get('codigo');
        $anioRaw  = $request->get('anio');
        $anio     = ($anioRaw !== null && $anioRaw !== '') ? (int) $anioRaw : null;
        $deposito = $request->get('deposito') ?: null;

        if (!$codigo) {
            return response()->json(['data' => [], 'nombre' => '']);
        }

        $nombre = DB::table('dispensado_medcol6')
            ->where('codigo', $codigo)
            ->value('nombre_generico') ?? $codigo;

        $mensual = DB::table('dispensado_medcol6')
            ->select([
                DB::raw("DATE_FORMAT(fecha_suministro,'%Y-%m') AS periodo"),
                DB::raw("SUM(CAST(REPLACE(COALESCE(numero_unidades,'0'),',','') AS DECIMAL(15,2))) AS total"),
                DB::raw('COUNT(DISTINCT DATE(fecha_suministro)) AS dias_activos'),
                DB::raw('COUNT(DISTINCT historia)               AS pacientes'),
            ])
            ->where('codigo', $codigo)
            ->whereNotIn('codigo', ['1010', '1011', '1012'])
            ->when($anio,     fn ($q) => $q->whereYear('fecha_suministro', $anio))
            ->when($deposito, fn ($q) => $q->where('centroprod', $deposito))
            ->whereIn('estado', ['DISPENSADO', 'REVISADO'])
            ->groupBy(DB::raw("DATE_FORMAT(fecha_suministro,'%Y-%m')"))
            ->orderBy('periodo')
            ->get();

        return response()->json([
            'nombre' => $nombre,
            'data'   => $mensual,
        ]);
    }

    // ── Helpers privados ─────────────────────────────────────────────────────

    private function getSaldos(?string $deposito)
    {
        $subLatest = DB::table('saldos_medcol6')
            ->select('codigo', 'deposito', DB::raw('MAX(fecha_saldo) AS max_fecha'))
            ->groupBy('codigo', 'deposito');

        $q = DB::table('saldos_medcol6 AS s')
            ->joinSub($subLatest, 'lat', function ($join) {
                $join->on('s.codigo',      '=', 'lat.codigo')
                     ->on('s.deposito',    '=', 'lat.deposito')
                     ->on('s.fecha_saldo', '=', 'lat.max_fecha');
            })
            ->select('s.codigo', DB::raw('SUM(s.saldo) AS saldo_total'));

        if ($deposito) {
            $q->where('s.deposito', $deposito);
        }

        return $q->groupBy('s.codigo')->pluck('saldo_total', 'codigo');
    }

    private function getAniosDisponibles(): array
    {
        return DB::table('dispensado_medcol6')
            ->selectRaw('DISTINCT YEAR(fecha_suministro) AS anio')
            ->whereNotNull('fecha_suministro')
            ->orderByDesc('anio')
            ->pluck('anio')
            ->toArray();
    }

    private function getAgrupadores(): array
    {
        return DB::table('dispensado_medcol6')
            ->selectRaw("DISTINCT SUBSTRING_INDEX(codigo,'-',1) AS agrupador")
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
