<?php

namespace App\Http\Controllers\Medcol6;

use App\Http\Controllers\Controller;
use App\Models\Medcol6\BufferProfile;
use Illuminate\Http\Request;

class BufferPerfilController extends Controller
{
    // ── Lista ────────────────────────────────────────────────────────────────

    public function index()
    {
        $perfiles = BufferProfile::orderBy('nombre')->get();
        return view('menu.Medcol6.ddmrp.perfiles.index', compact('perfiles'));
    }

    // ── Crear ────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'             => 'required|string|max:100',
            'descripcion'        => 'nullable|string|max:500',
            'lead_time'          => 'required|integer|min:1|max:180',
            'lead_time_factor'   => 'required|numeric|min:0.1|max:3.0',
            'variability_factor' => 'required|numeric|min:0.00|max:1.00',
            'order_cycle'        => 'required|integer|min:1|max:180',
            'moq'                => 'required|integer|min:1',
        ]);

        $perfil = BufferProfile::create($data + ['is_active' => true]);

        return response()->json([
            'ok'     => true,
            'perfil' => $perfil,
            'msg'    => "Perfil \"{$perfil->nombre}\" creado correctamente.",
        ], 201);
    }

    // ── Obtener uno (para edición) ───────────────────────────────────────────

    public function show($id)
    {
        return response()->json(BufferProfile::findOrFail($id));
    }

    // ── Actualizar ───────────────────────────────────────────────────────────

    public function update(Request $request, $id)
    {
        $perfil = BufferProfile::findOrFail($id);

        $data = $request->validate([
            'nombre'             => 'required|string|max:100',
            'descripcion'        => 'nullable|string|max:500',
            'lead_time'          => 'required|integer|min:1|max:180',
            'lead_time_factor'   => 'required|numeric|min:0.1|max:3.0',
            'variability_factor' => 'required|numeric|min:0.00|max:1.00',
            'order_cycle'        => 'required|integer|min:1|max:180',
            'moq'                => 'required|integer|min:1',
        ]);

        $perfil->update($data);

        return response()->json([
            'ok'     => true,
            'perfil' => $perfil,
            'msg'    => "Perfil \"{$perfil->nombre}\" actualizado correctamente.",
        ]);
    }

    // ── Eliminar ─────────────────────────────────────────────────────────────

    public function destroy($id)
    {
        $perfil = BufferProfile::findOrFail($id);
        $nombre = $perfil->nombre;
        $perfil->delete();

        return response()->json([
            'ok'  => true,
            'msg' => "Perfil \"{$nombre}\" eliminado.",
        ]);
    }

    // ── Activar / Desactivar ─────────────────────────────────────────────────

    public function toggle($id)
    {
        $perfil = BufferProfile::findOrFail($id);
        $perfil->update(['is_active' => !$perfil->is_active]);

        return response()->json([
            'ok'        => true,
            'is_active' => $perfil->is_active,
            'msg'       => "Perfil " . ($perfil->is_active ? 'activado' : 'desactivado') . ".",
        ]);
    }
}
