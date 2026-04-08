<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Paciente;

class SyncPacientesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries   = 1;
    public $timeout = 600;

    protected string $cacheKey;
    protected string $usuario;

    public function __construct(string $cacheKey, string $usuario)
    {
        $this->cacheKey = $cacheKey;
        $this->usuario  = $usuario;
    }

    // ─────────────────────────────────────────────────────────────────────
    public function handle(): void
    {
        set_time_limit(0);
        ini_set('memory_limit', '1G');

        $email    = 'castrokofdev@gmail.com';
        $password = 'colMed2023**';

        try {
            $resultado = $this->sincronizarDesde(
                'http://hed08pf9dxt.sn.mynetname.net:8004',
                $email,
                $password
            );
            $icon   = 'success';
            $titulo = 'Sincronización Exitosa';

        } catch (\Exception $e) {

            Log::warning("⚠️ Servidor principal falló ({$e->getMessage()}). Intentando local…");

            try {
                $resultado = $this->sincronizarDesde(
                    'http://192.168.66.95:8004',
                    $email,
                    $password
                );
                $icon   = 'warning';
                $titulo = 'Usando API Local';

            } catch (\Exception $localEx) {

                Log::error(
                    "❌ Sync fallida — Principal: {$e->getMessage()} | " .
                    "Local: {$localEx->getMessage()} — Usuario: {$this->usuario}"
                );

                Cache::put($this->cacheKey, [
                    'status'    => 'done',
                    'respuesta' => 'Sin conexión a los servidores API.',
                    'titulo'    => 'Error de Sincronización',
                    'icon'      => 'error',
                    'position'  => 'bottom-left',
                ], 600);

                return;
            }
        }

        Log::info(
            "✅ Sync pacientes ({$icon}) — {$resultado['insertados']} nuevos, " .
            "{$resultado['actualizados']} actualizados, " .
            "{$resultado['omitidos']} sin cambios — Usuario: {$this->usuario}"
        );

        Cache::put($this->cacheKey, [
            'status'    => 'done',
            'respuesta' => "{$resultado['insertados']} nuevos | {$resultado['actualizados']} actualizados | {$resultado['omitidos']} sin cambios",
            'titulo'    => $titulo,
            'icon'      => $icon,
            'position'  => 'bottom-left',
        ], 600);
    }

    // ─────────────────────────────────────────────────────────────────────
    private function sincronizarDesde(string $baseUrl, string $email, string $password): array
    {
        // ── Autenticación ────────────────────────────────────────────────
        $authResponse = Http::timeout(30)->post("{$baseUrl}/api/acceso", [
            'email'    => $email,
            'password' => $password,
        ]);

        $authJson = $authResponse->json();

        if (!$authJson || !isset($authJson['token'])) {
            throw new \Exception(
                "No se recibió token desde {$baseUrl}. " .
                "HTTP {$authResponse->status()}. Respuesta: " .
                substr($authResponse->body(), 0, 200)
            );
        }

        $token = $authJson['token'];

        // ── Obtener pacientes ─────────────────────────────────────────────
        $pacientesResponse = Http::timeout(300)->withToken($token)
            ->get("{$baseUrl}/api/pacientes");

        $pacientesJson = $pacientesResponse->json();

        if (!$pacientesJson || !isset($pacientesJson['data'])) {
            throw new \Exception(
                "Respuesta inválida desde {$baseUrl}. " .
                "HTTP {$pacientesResponse->status()}. Respuesta: " .
                substr($pacientesResponse->body(), 0, 200)
            );
        }

        $pacientesApi = $pacientesJson['data'];
        unset($pacientesJson, $pacientesResponse);

        if (!is_array($pacientesApi)) {
            throw new \Exception(
                "El campo 'data' no es un array (tipo: " . gettype($pacientesApi) . ")."
            );
        }

        $resultado = $this->procesarPacientes($pacientesApi);
        unset($pacientesApi);

        // ── Cerrar sesión ──────────────────────────────────────────────────
        Http::timeout(10)->withToken($token)->get("{$baseUrl}/api/closeallacceso");

        return $resultado;
    }

    // ─────────────────────────────────────────────────────────────────────
    /**
     * Procesa el array de pacientes:
     *
     * INSERCIÓN  — batch de 500 en 500 con insertOrIgnore.
     * DETECCIÓN  — compara campo a campo en memoria (O(n), sin queries).
     * ACTUALIZACIÓN — batch CASE/WHEN de 300 en 300: 1 query = hasta 300 UPDATEs.
     *
     * Campos que SE actualizan: tipdocum, paciente, direccion, telefono,
     *                           regimen, nivel, edad, sexo.
     * Campos que NO se tocan:   pqrs, estado, programa, alto_costo.
     */
    private function procesarPacientes(array $pacientesApi): array
    {
        $insertados    = 0;
        $actualizados  = 0;
        $omitidos      = 0;
        $nuevos        = [];
        $porActualizar = [];
        $cambiosLog    = [];
        $now           = now()->toDateTimeString();

        $camposComparables = [
            'tipdocum', 'paciente', 'direccion', 'telefono',
            'regimen',  'nivel',    'edad',  'fechanac',    'sexo',
        ];

        // Cargar existentes en memoria como arrays planos (menos overhead que objetos)
        $existentes = Paciente::select(array_merge(['historia'], $camposComparables))
            ->get()
            ->keyBy(fn($p) => trim((string) $p->historia))
            ->map(fn($p) => array_map(
                fn($v) => trim((string) ($v ?? '')),
                $p->only($camposComparables)
            ))
            ->toArray();

        foreach ($pacientesApi as $p) {
            $historia = trim($p['NUMDOCUM'] ?? '');
            if ($historia === '') { $omitidos++; continue; }

            $nombreCompleto = implode(' ', array_filter([
                trim($p['NOMBRE1']   ?? ''),
                trim($p['NOMBRE2']   ?? ''),
                trim($p['APELLIDO1'] ?? ''),
                trim($p['APELLIDO2'] ?? ''),
            ]));

            $telefono = trim($p['TELEFRES']   ?? '')
                     ?: trim($p['TELEFTRA']   ?? '')
                     ?: trim($p['AVISAR_TEL'] ?? '');

            $datosApi = [
                'tipdocum'  => trim($p['Tipodocum'] ?? ''),
                'paciente'  => $nombreCompleto,
                'direccion' => trim($p['DIRECRES']  ?? ''),
                'telefono'  => $telefono,
                'regimen'   => trim($p['REGIMEN_1'] ?? ''),
                'nivel'     => trim($p['nivel']     ?? ''),
                'edad'      => trim($p['EDAD']      ?? ''),
                'fechanac'  => trim($p['FECHANAC']  ?? ''),
                'sexo'      => trim($p['SEXO']      ?? ''),
            ];

            // ── INSERCIÓN ─────────────────────────────────────────────────
            if (!isset($existentes[$historia])) {
                $nuevos[] = array_merge($datosApi, [
                    'historia'   => $historia,
                    'pqrs'       => 'NO',
                    'estado'     => 'VIVO',
                    'programa'   => null,
                    'alto_costo' => 'NO',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $existentes[$historia] = $datosApi;
                $insertados++;

                if (count($nuevos) >= 500) {
                    Paciente::insertOrIgnore($nuevos);
                    $nuevos = [];
                }
                continue;
            }

            // ── DETECCIÓN DE CAMBIOS ──────────────────────────────────────
            $cambios = [];
            foreach ($camposComparables as $campo) {
                $valorApi = $datosApi[$campo];
                $valorBd  = $existentes[$historia][$campo] ?? '';
                if ($valorApi !== $valorBd) {
                    $cambios[$campo] = $valorApi;
                }
            }

            if (!empty($cambios)) {
                $porActualizar[] = ['historia' => $historia, 'cambios' => $cambios];
                $existentes[$historia] = array_merge($existentes[$historia], $cambios);
                $cambiosLog[] = ['historia' => $historia, 'campos' => array_keys($cambios)];
                $actualizados++;

                // Flush de actualizaciones cada 300 registros
                if (count($porActualizar) >= 300) {
                    $this->ejecutarBatchUpdate($porActualizar, $now);
                    $porActualizar = [];
                }
            } else {
                $omitidos++;
            }
        }

        // ── Remanentes ────────────────────────────────────────────────────
        if (!empty($nuevos)) {
            Paciente::insertOrIgnore($nuevos);
        }
        if (!empty($porActualizar)) {
            $this->ejecutarBatchUpdate($porActualizar, $now);
        }

        if ($actualizados > 0) {
            Log::info("📋 Pacientes actualizados — {$actualizados} registros", [
                'total_actualizados' => $actualizados,
                'muestra_cambios'    => array_slice($cambiosLog, 0, 100),
            ]);
        }

        unset($existentes, $nuevos, $cambiosLog);

        return [
            'insertados'   => $insertados,
            'actualizados' => $actualizados,
            'omitidos'     => $omitidos,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────
    /**
     * Ejecuta un UPDATE masivo con CASE/WHEN.
     *
     * En vez de N queries individuales, genera una sola sentencia SQL:
     *
     *   UPDATE pacientes
     *   SET    campo1 = CASE historia WHEN ? THEN ? … ELSE campo1 END,
     *          campo2 = …,
     *          updated_at = ?
     *   WHERE  historia IN (?, ?, …)
     *
     * El tamaño recomendado del chunk es ≤ 300 para mantenerse dentro de los
     * límites de parámetros de PDO/MySQL y de max_allowed_packet.
     */
    private function ejecutarBatchUpdate(array $porActualizar, string $now): void
    {
        if (empty($porActualizar)) return;

        $campos     = ['tipdocum', 'paciente', 'direccion', 'telefono', 'regimen', 'nivel', 'edad', 'sexo'];
        $historias  = array_column($porActualizar, 'historia');
        $setClauses = [];
        $bindings   = [];

        foreach ($campos as $campo) {
            $caseExpr = "CASE historia";
            $hasCases = false;

            foreach ($porActualizar as $row) {
                if (array_key_exists($campo, $row['cambios'])) {
                    $caseExpr .= " WHEN ? THEN ?";
                    $bindings[] = $row['historia'];
                    $bindings[] = $row['cambios'][$campo];
                    $hasCases   = true;
                }
            }

            if ($hasCases) {
                $setClauses[] = "{$campo} = {$caseExpr} ELSE {$campo} END";
            }
        }

        if (empty($setClauses)) return;

        $setClauses[]  = "updated_at = ?";
        $bindings[]    = $now;

        $placeholders  = implode(',', array_fill(0, count($historias), '?'));
        $bindings      = array_merge($bindings, $historias);

        DB::statement(
            "UPDATE pacientes SET " . implode(', ', $setClauses) .
            " WHERE historia IN ({$placeholders})",
            $bindings
        );
    }
}
