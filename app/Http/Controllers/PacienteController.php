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
            $data = Paciente::select([
                'id', 'tipdocum', 'historia', 'paciente', 'direccion',
                'telefono', 'regimen', 'nivel', 'edad', 'sexo',
                'pqrs', 'estado', 'programa', 'alto_costo',
            ])->get();

            return DataTables()->of($data)
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
     * Sincroniza la tabla pacientes consumiendo el endpoint api/pacientesapi del servidor.
     * Solo inserta pacientes nuevos (no sobreescribe valores gestionados manualmente:
     * pqrs, estado, programa, alto_costo).
     */
    public function syncPacientesApi(Request $request)
    {
        $email    = 'castrokofdev@gmail.com';
        $password = 'colMed2023**';
        $usuario  = Auth::user()->email;

        set_time_limit(0);
        ini_set('memory_limit', '512M');

        try {
            // ── Servidor principal ────────────────────────────────────────
            $response = Http::post(
                'http://hed08pf9dxt.sn.mynetname.net:8004/api/acceso',
                ['email' => $email, 'password' => $password]
            );

            $token = $response->json()['token'];

            $responsePacientes = Http::withToken($token)
                ->get('http://hed08pf9dxt.sn.mynetname.net:8004/api/pacientesapi');

            $pacientesApi = $responsePacientes->json()['data'];

            $resultado = $this->procesarPacientes($pacientesApi);

            Http::withToken($token)
                ->get('http://hed08pf9dxt.sn.mynetname.net:8004/api/closeallacceso');

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

            try {
                // ── Fallback servidor local ───────────────────────────────
                $response = Http::post(
                    'http://192.168.66.95:8004/api/acceso',
                    ['email' => $email, 'password' => $password]
                );

                $token = $response->json()['token'];

                $responsePacientes = Http::withToken($token)
                    ->get('http://192.168.66.95:8004/api/pacientesapi');

                $pacientesApi = $responsePacientes->json()['data'];

                $resultado = $this->procesarPacientes($pacientesApi);

                Http::withToken($token)
                    ->get('http://192.168.66.95:8004/api/closeallacceso');

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
                    'respuesta' => 'Error de conexión: ' . $e->getMessage(),
                    'titulo'    => 'Error de Sincronización',
                    'icon'      => 'error',
                    'position'  => 'bottom-left',
                ]]);
            }
        }
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

            // Nombre completo: solo partes no vacías
            $nombreCompleto = collect([
                trim($p['NOMBRE1']   ?? ''),
                trim($p['NOMBRE2']   ?? ''),
                trim($p['APELLIDO1'] ?? ''),
                trim($p['APELLIDO2'] ?? ''),
            ])->filter()->implode(' ');

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
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Registrar en el set local para evitar duplicados dentro del mismo lote
            $existentes[$historia] = true;
            $insertados++;
        }

        // Inserción en chunks de 500 para no saturar la memoria
        foreach (array_chunk($nuevos, 500) as $chunk) {
            Paciente::insertOrIgnore($chunk);
        }

        unset($existentes, $nuevos);

        return ['insertados' => $insertados, 'omitidos' => $omitidos];
    }
}
