<?php

namespace App\Http\Controllers\Medcol6;

use App\Http\Controllers\Controller;
use App\Models\Medcol6\BufferProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DdmrpBufferController extends Controller
{
    // ── Vista principal ──────────────────────────────────────────────────────

    public function index()
    {
        $anioActual  = (int) date('Y');
        $anios       = $this->getAniosDisponibles();
        $depositos   = $this->getDepositosDisponibles();
        $agrupadores = $this->getAgrupadores();
        $perfiles    = BufferProfile::active()->orderBy('nombre')->get();

        return view('menu.Medcol6.ddmrp.buffers.index',
            compact('anioActual', 'anios', 'depositos', 'agrupadores', 'perfiles'));
    }

    // ── Endpoint cálculo de buffers (AJAX) ───────────────────────────────────

    public function getData(Request $request)
    {
        $anioRaw  = $request->get('anio');
        $anio     = ($anioRaw !== null && $anioRaw !== '') ? (int) $anioRaw : null;
        $deposito = $request->get('deposito')  ?: null;
        $agrupador = $request->get('agrupador') ?: null;
        $perfilId  = $request->get('perfil_id') ?: null;

        // ── Cargar perfil de buffer ──────────────────────────────────────────
        $perfil = $perfilId
            ? BufferProfile::find($perfilId)
            : BufferProfile::active()->orderBy('id')->first();

        // Perfil por defecto si no existe ninguno
        if (!$perfil) {
            $perfil = new BufferProfile([
                'nombre'             => 'Estándar',
                'lead_time'          => 7,
                'lead_time_factor'   => 1.0,
                'variability_factor' => 0.5,
                'order_cycle'        => 14,
                'moq'                => 1,
            ]);
        }

        // ── Demanda diaria por código (subquery agrupada en MySQL) ───────────
        $stats = DB::table(function ($q) use ($anio, $deposito, $agrupador) {
            $q->from('dispensado_medcol6')
              ->select([
                  'codigo',
                  'nombre_generico',
                  DB::raw('DATE(fecha_suministro) AS fecha'),
                  DB::raw("SUM(CAST(REPLACE(COALESCE(numero_unidades,'0'),',','') AS DECIMAL(15,2))) AS unidades_dia"),
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
            DB::raw('COUNT(*)                 AS dias_con_demanda'),
            DB::raw('SUM(unidades_dia)         AS total_unidades'),
            DB::raw('AVG(unidades_dia)         AS promedio_diario'),
        ])
        ->groupBy('codigo', 'nombre_generico')
        ->orderBy('nombre_generico')
        ->get();

        // ── Saldo actual ─────────────────────────────────────────────────────
        $saldos = $this->getSaldos($deposito);

        // ── Marcas por código ─────────────────────────────────────────────────
        $marcas = $this->getMarcas();

        // ── Calcular zonas DDMRP para cada ítem ──────────────────────────────
        $result = [];

        foreach ($stats as $row) {
            $ddp = (float) $row->promedio_diario;

            $zonas = $perfil->calcularZonas($ddp);

            $saldo    = (float) ($saldos[$row->codigo] ?? 0);
            $tor      = $zonas['tor'];
            $toy      = $zonas['toy'];
            $tog      = $zonas['tog'];

            // Estado del buffer
            if ($ddp <= 0) {
                $estado = 'SIN_DEMANDA';
            } elseif ($saldo <= $tor) {
                $estado = 'ROJO';
            } elseif ($saldo <= $toy) {
                $estado = 'AMARILLO';
            } elseif ($saldo <= $tog) {
                $estado = 'VERDE';
            } else {
                $estado = 'SOBRESTOCK';
            }

            // % de penetración dentro de la zona activa
            $pct = null;
            if ($ddp > 0 && $tog > 0) {
                if ($estado === 'ROJO' && $tor > 0) {
                    $pct = round($saldo / $tor * 100, 1);
                } elseif ($estado === 'AMARILLO' && $zonas['zonaAmarilla'] > 0) {
                    $pct = round(($saldo - $tor) / $zonas['zonaAmarilla'] * 100, 1);
                } elseif ($estado === 'VERDE' && $zonas['zonaVerde'] > 0) {
                    $pct = round(($saldo - $toy) / $zonas['zonaVerde'] * 100, 1);
                } else {
                    $pct = 100;
                }
            }

            // Pedido sugerido (cuando está en rojo o amarillo)
            $pedidoSugerido = in_array($estado, ['ROJO', 'AMARILLO'])
                ? (int) round(max($tog - $saldo, $perfil->moq))
                : 0;

            $diasCob = $ddp > 0 ? round($saldo / $ddp, 1) : null;

            $result[] = [
                'codigo'           => $row->codigo,
                'nombre_generico'  => $row->nombre_generico ?? '',
                'marca'            => $marcas[$row->codigo] ?? '',
                'promedio_diario'  => round($ddp, 2),
                'zona_roja'        => round($zonas['zonaRoja'],     1),
                'zona_roja_base'   => round($zonas['zonaRojaBase'], 1),
                'zona_roja_seg'    => round($zonas['zonaRojaSeg'],  1),
                'zona_amarilla'    => round($zonas['zonaAmarilla'], 1),
                'zona_verde'       => round($zonas['zonaVerde'],    1),
                'tor'              => round($tor, 1),
                'toy'              => round($toy, 1),
                'tog'              => round($tog, 1),
                'saldo_actual'     => (int) round($saldo),
                'dias_cobertura'   => $diasCob,
                'estado'           => $estado,
                'pct_penetracion'  => $pct,
                'pedido_sugerido'  => $pedidoSugerido,
            ];
        }

        // Ordenar: rojos primero, luego amarillos, verdes, sobrestock, sin demanda
        $orden = ['ROJO' => 0, 'AMARILLO' => 1, 'VERDE' => 2, 'SOBRESTOCK' => 3, 'SIN_DEMANDA' => 4];
        usort($result, fn ($a, $b) => ($orden[$a['estado']] ?? 9) <=> ($orden[$b['estado']] ?? 9));

        return response()->json([
            'data'   => $result,
            'perfil' => [
                'id'                 => $perfil->id,
                'nombre'             => $perfil->nombre,
                'lead_time'          => $perfil->lead_time,
                'lead_time_factor'   => $perfil->lead_time_factor,
                'variability_factor' => $perfil->variability_factor,
                'order_cycle'        => $perfil->order_cycle,
                'moq'                => $perfil->moq,
            ],
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
