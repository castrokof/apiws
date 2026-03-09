<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Paciente;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            ini_set('memory_limit', '1G');

            // Evitar consultas sin LIMIT: DataTables envía -1 cuando el usuario
            // elige "Mostrar Todo". Capamos a 10 000 para proteger la memoria.
            if ((int) $request->input('length', 25) === -1) {
                $request->merge(['length' => 10000]);
            }

            $query = Paciente::select([
                'id', 'tipdocum', 'historia', 'paciente', 'direccion',
                'telefono', 'regimen', 'nivel', 'edad', 'sexo',
                'pqrs', 'estado', 'programa', 'alto_costo',
            ]);

            return DataTables()->of($query)
                ->addColumn('pqrs_badge', function ($row) {
                    $color = $row->pqrs === 'SI' ? 'danger' : 'secondary';
                    return '<span class="badge badge-' . $color . '">' . $row->pqrs . '</span>';
                })
                ->addColumn('estado_badge', function ($row) {
                    $color = $row->estado === 'VIVO' ? 'success' : 'dark';
                    $icon  = $row->estado === 'VIVO' ? 'fa-heart' : 'fa-cross';
                    return '<span class="badge badge-' . $color . '"><i class="fas ' . $icon . ' mr-1"></i>' . $row->estado . '</span>';
                })
                ->addColumn('alto_costo_badge', function ($row) {
                    $color = $row->alto_costo === 'SI' ? 'warning' : 'secondary';
                    return '<span class="badge badge-' . $color . '">' . $row->alto_costo . '</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-info btn-sm btn-editar"
                                data-id="'          . $row->id          . '"
                                data-tipdocum="'    . $row->tipdocum    . '"
                                data-historia="'    . $row->historia    . '"
                                data-paciente="'    . e($row->paciente) . '"
                                data-direccion="'   . e($row->direccion). '"
                                data-telefono="'    . $row->telefono    . '"
                                data-regimen="'     . $row->regimen     . '"
                                data-nivel="'       . $row->nivel       . '"
                                data-edad="'        . $row->edad        . '"
                                data-sexo="'        . $row->sexo        . '"
                                data-pqrs="'        . $row->pqrs        . '"
                                data-estado="'      . $row->estado      . '"
                                data-programa="'    . $row->programa    . '"
                                data-alto_costo="'  . $row->alto_costo  . '"
                                title="Editar paciente">
                                <i class="fas fa-edit"></i> Editar
                            </button>';
                })
                ->rawColumns(['pqrs_badge', 'estado_badge', 'alto_costo_badge', 'action'])
                ->make(true);
        }

        return view('pacientes.index');
    }

    public function exportExcel(Request $request)
    {
        $search   = $request->input('search');
        $filename = 'gestion_pacientes_' . now()->format('Ymd_His') . '.csv';

        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'X-Accel-Buffering'   => 'no',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Pragma'              => 'no-cache',
        ];

        return response()->stream(function () use ($search) {
            // Desactivar cualquier buffer de salida activo para que el streaming funcione
            while (ob_get_level() > 0) {
                ob_end_flush();
            }

            $handle = fopen('php://output', 'w');

            // BOM UTF-8 para que Excel abra correctamente tildes y caracteres especiales
            fputs($handle, "\xEF\xBB\xBF");

            // Cabeceras de columna
            fputcsv($handle, [
                'Tip. Doc.', 'Historia', 'Paciente', 'Dirección',
                'Teléfono', 'Régimen', 'Nivel', 'Edad', 'Sexo',
                'PQRS', 'Estado', 'Programa', 'Alto Costo',
            ], ';');

            $query = Paciente::select([
                'tipdocum', 'historia', 'paciente', 'direccion',
                'telefono', 'regimen', 'nivel', 'edad', 'sexo',
                'pqrs', 'estado', 'programa', 'alto_costo',
            ]);

            if ($search) {
                $s = $search;
                $query->where(function ($q) use ($s) {
                    $q->where('historia',  'like', "%{$s}%")
                      ->orWhere('paciente', 'like', "%{$s}%")
                      ->orWhere('tipdocum', 'like', "%{$s}%")
                      ->orWhere('regimen',  'like', "%{$s}%")
                      ->orWhere('programa', 'like', "%{$s}%");
                });
            }

            $query->orderBy('paciente')->chunk(1000, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->tipdocum,
                        $row->historia,
                        $row->paciente,
                        $row->direccion,
                        $row->telefono,
                        $row->regimen,
                        $row->nivel,
                        $row->edad,
                        $row->sexo,
                        $row->pqrs,
                        $row->estado,
                        $row->programa ?? '',
                        $row->alto_costo,
                    ], ';');
                }
                flush();
            });

            fclose($handle);
        }, 200, $headers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipdocum'   => 'nullable|string|max:10',
            'historia'   => 'nullable|string|max:50',
            'paciente'   => 'required|string|max:200',
            'direccion'  => 'nullable|string|max:300',
            'telefono'   => 'nullable|string|max:30',
            'regimen'    => 'nullable|string|max:50',
            'nivel'      => 'nullable|string|max:20',
            'edad'       => 'nullable|string|max:10',
            'sexo'       => 'nullable|string|max:10',
            'pqrs'       => 'required|in:SI,NO',
            'estado'     => 'required|in:VIVO,FALLECIDO',
            'programa'   => 'nullable|string|max:50',
            'alto_costo' => 'required|in:SI,NO',
        ]);

        Paciente::create($request->only([
            'tipdocum', 'historia', 'paciente', 'direccion', 'telefono',
            'regimen', 'nivel', 'edad', 'sexo', 'pqrs', 'estado',
            'programa', 'alto_costo',
        ]));

        return response()->json(['success' => 'Paciente registrado correctamente.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tipdocum'   => 'nullable|string|max:10',
            'historia'   => 'nullable|string|max:50',
            'paciente'   => 'required|string|max:200',
            'direccion'  => 'nullable|string|max:300',
            'telefono'   => 'nullable|string|max:30',
            'regimen'    => 'nullable|string|max:50',
            'nivel'      => 'nullable|string|max:20',
            'edad'       => 'nullable|string|max:10',
            'sexo'       => 'nullable|string|max:10',
            'pqrs'       => 'required|in:SI,NO',
            'estado'     => 'required|in:VIVO,FALLECIDO',
            'programa'   => 'nullable|string|max:50',
            'alto_costo' => 'required|in:SI,NO',
        ]);

        $paciente = Paciente::findOrFail($id);
        $paciente->update($request->only([
            'tipdocum', 'historia', 'paciente', 'direccion', 'telefono',
            'regimen', 'nivel', 'edad', 'sexo', 'pqrs', 'estado',
            'programa', 'alto_costo',
        ]));

        return response()->json(['success' => 'Paciente actualizado correctamente.']);
    }

    /**
     * Sincroniza la tabla pacientes consumiendo el endpoint api/pacientes del servidor.
     * Solo inserta pacientes nuevos (no sobreescribe valores gestionados manualmente:
     * pqrs, estado, programa, alto_costo).
     */
    public function syncPacientesApi(Request $request)
    {
        $email    = 'castrokofdev@gmail.com';
        $password = 'colMed2023**';
        $usuario  = Auth::user()->email;

        set_time_limit(0);
        ini_set('memory_limit', '1G');

        try {
            // ── Servidor principal ────────────────────────────────────────
            $resultado = $this->sincronizarDesde(
                'http://hed08pf9dxt.sn.mynetname.net:8004',
                $email,
                $password
            );

            Log::info(
                "✅ Sync pacientes (principal) — {$resultado['insertados']} nuevos, " .
                "{$resultado['omitidos']} existentes — Usuario: {$usuario}"
            );

            return response()->json([[
                'respuesta' => "{$resultado['insertados']} pacientes sincronizados | {$resultado['omitidos']} ya existían",
                'titulo'    => 'Sincronización Exitosa',
                'icon'      => 'success',
                'position'  => 'bottom-left',
            ]]);

        } catch (\Exception $e) {

            Log::warning("⚠️ Servidor principal falló ({$e->getMessage()}). Intentando servidor local…");

            try {
                // ── Fallback servidor local ───────────────────────────────
                $resultado = $this->sincronizarDesde(
                    'http://192.168.66.95:8004',
                    $email,
                    $password
                );

                Log::info(
                    "⚠️ Sync pacientes (local) — {$resultado['insertados']} nuevos, " .
                    "{$resultado['omitidos']} existentes — Usuario: {$usuario}"
                );

                return response()->json([[
                    'respuesta' => "{$resultado['insertados']} pacientes sincronizados | {$resultado['omitidos']} ya existían",
                    'titulo'    => 'Usando API Local',
                    'icon'      => 'warning',
                    'position'  => 'bottom-left',
                ]]);

            } catch (\Exception $localException) {

                Log::error(
                    "❌ Error ambos servidores. Principal: {$e->getMessage()} | " .
                    "Local: {$localException->getMessage()} — Usuario: {$usuario}"
                );

                return response()->json([[
                    'respuesta' => 'Sin conexión a los servidores API. Principal: ' . $e->getMessage()
                                 . ' | Local: ' . $localException->getMessage(),
                    'titulo'    => 'Error de Sincronización',
                    'icon'      => 'error',
                    'position'  => 'bottom-left',
                ]]);
            }
        }
    }

    /**
     * Conecta a un servidor API, obtiene el token, descarga los pacientes,
     * los procesa y cierra la sesión. Lanza excepciones con mensajes descriptivos
     * si la respuesta no es válida o si faltan claves esperadas.
     */
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
                "Respuesta de pacientes inválida desde {$baseUrl}. " .
                "HTTP {$pacientesResponse->status()}. Respuesta: " .
                substr($pacientesResponse->body(), 0, 200)
            );
        }

        $pacientesApi = $pacientesJson['data'];

        // Liberar la respuesta completa para no mantener dos copias en memoria
        unset($pacientesJson, $pacientesResponse);

        if (!is_array($pacientesApi)) {
            throw new \Exception(
                "El campo 'data' de la API no es un array (tipo: " . gettype($pacientesApi) . ")."
            );
        }

        $resultado = $this->procesarPacientes($pacientesApi);

        unset($pacientesApi);

        // ── Cerrar sesión en el servidor ──────────────────────────────────
        Http::timeout(10)->withToken($token)
            ->get("{$baseUrl}/api/closeallacceso");

        return $resultado;
    }

    /**
     * Procesa el array de pacientes recibido del API:
     * - Solo inserta registros cuyo 'historia' (NUMDOCUM) no exista aún en la BD.
     * - Los campos pqrs, estado, programa y alto_costo se inicializan con valores
     *   por defecto para que el usuario los gestione manualmente.
     *
     * Mapeo de campos API → columnas tabla pacientes:
     *   Tipodocum  → tipdocum
     *   NUMDOCUM   → historia
     *   NOMBRE1 + NOMBRE2 + APELLIDO1 + APELLIDO2 → paciente
     *   DIRECRES   → direccion
     *   TELEFRES / TELEFTRA / AVISAR_TEL → telefono (primer valor no vacío)
     *   REGIMEN_1  → regimen
     *   nivel      → nivel
     *   EDAD       → edad
     *   SEXO       → sexo
     */
    private function procesarPacientes(array $pacientesApi): array
    {
        $insertados = 0;
        $omitidos   = 0;
        $nuevos     = [];
        $now        = now()->toDateTimeString();

        // Un solo query para traer todas las historias existentes
        $existentes = Paciente::pluck('historia')
            ->map(fn($h) => trim((string) $h))
            ->flip()
            ->toArray();

        foreach ($pacientesApi as $p) {
            $historia = trim($p['NUMDOCUM'] ?? '');

            if ($historia === '' || isset($existentes[$historia])) {
                $omitidos++;
                continue;
            }

            // Nombre completo: solo partes no vacías (sin crear objetos Collection)
            $nombreCompleto = implode(' ', array_filter([
                trim($p['NOMBRE1']   ?? ''),
                trim($p['NOMBRE2']   ?? ''),
                trim($p['APELLIDO1'] ?? ''),
                trim($p['APELLIDO2'] ?? ''),
            ]));

            // Primer teléfono disponible
            $telefono = trim($p['TELEFRES']   ?? '')
                     ?: trim($p['TELEFTRA']   ?? '')
                     ?: trim($p['AVISAR_TEL'] ?? '');

            $nuevos[] = [
                'tipdocum'   => trim($p['Tipodocum'] ?? ''),
                'historia'   => $historia,
                'paciente'   => $nombreCompleto,
                'direccion'  => trim($p['DIRECRES']  ?? ''),
                'telefono'   => $telefono,
                'regimen'    => trim($p['REGIMEN_1'] ?? ''),
                'nivel'      => trim($p['nivel']     ?? ''),
                'edad'       => trim($p['EDAD']      ?? ''),
                'sexo'       => trim($p['SEXO']      ?? ''),
                // Valores por defecto — el usuario los gestiona manualmente
                'pqrs'       => 'NO',
                'estado'     => 'VIVO',
                'programa'   => null,
                'alto_costo' => 'NO',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Registrar en el set local para evitar duplicados dentro del mismo lote
            $existentes[$historia] = true;
            $insertados++;

            // Insertar y vaciar cada 500 registros para no acumular todo en memoria
            if (count($nuevos) >= 500) {
                Paciente::insertOrIgnore($nuevos);
                $nuevos = [];
            }
        }

        // Insertar el remanente
        if (!empty($nuevos)) {
            Paciente::insertOrIgnore($nuevos);
        }

        unset($existentes, $nuevos, $pacientesApi);

        return ['insertados' => $insertados, 'omitidos' => $omitidos];
    }
}
