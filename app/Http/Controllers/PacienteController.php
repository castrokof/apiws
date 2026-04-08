<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Paciente;
use App\Jobs\SyncPacientesJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
                'telefono', 'regimen', 'nivel', 'edad', 'fechanac', 'sexo',
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
                'Teléfono', 'Régimen', 'Nivel', 'Edad', 'Fechanac', 'Sexo',
                'PQRS', 'Estado', 'Programa', 'Alto Costo',
            ], ';');

            $query = Paciente::select([
                'tipdocum', 'historia', 'paciente', 'direccion',
                'telefono', 'regimen', 'nivel', 'edad', 'fechanac', 'sexo',
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
                        $row->fechanac,
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
     * Despacha el job de sincronización y retorna inmediatamente (202 Accepted).
     * El job corre en background y guarda el resultado en Cache.
     * El frontend hace polling a /pacientes/sync-status para obtener el resultado.
     */
    public function syncPacientesApi(Request $request)
    {
        $cacheKey = 'sync_pacientes_' . Auth::id();

        // Si ya hay un proceso corriendo, no lanzar otro
        $estadoActual = Cache::get($cacheKey);
        if ($estadoActual && ($estadoActual['status'] ?? '') === 'processing') {
            return response()->json([[
                'respuesta' => 'Ya hay una sincronización en progreso. Por favor espere.',
                'titulo'    => 'En proceso...',
                'icon'      => 'info',
                'position'  => 'bottom-left',
                'polling'   => true,
            ]]);
        }

        Cache::put($cacheKey, ['status' => 'processing'], 600);

        SyncPacientesJob::dispatch($cacheKey, Auth::user()->email);

        return response()->json([[
            'respuesta' => 'Sincronización iniciada. Espere el resultado...',
            'titulo'    => 'Procesando',
            'icon'      => 'info',
            'position'  => 'bottom-left',
            'polling'   => true,
        ]], 202);
    }

    /**
     * Endpoint de polling: retorna el estado del job de sincronización.
     * status: 'processing' | 'done' | 'idle'
     */
    public function syncStatus(Request $request)
    {
        $cacheKey = 'sync_pacientes_' . Auth::id();
        $result   = Cache::get($cacheKey, ['status' => 'idle']);

        return response()->json($result);
    }
}
