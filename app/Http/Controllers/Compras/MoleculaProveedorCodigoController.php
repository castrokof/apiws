<?php

namespace App\Http\Controllers\Compras;

use App\Models\compras\Molecula;
use App\Models\compras\MoleculaProveedorCodigo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MoleculaProveedorCodigoController extends Controller
{
    public function index(Molecula $molecula)
    {
        $codigos = $molecula->codigosProveedor()->orderBy('nombre_proveedor')->get();
        return view('moleculas.codigos.index', compact('molecula','codigos'));
    }

    public function create(Molecula $molecula)
    {
        return view('moleculas.codigos.create', compact('molecula'));
    }

    public function store(Request $request, Molecula $molecula)
    {
        $data = $request->validate([
            'nombre_proveedor' => 'nullable|string|max:120',
            'codigo_proveedor' => 'required|string|max:60',
            'activo'           => 'nullable|boolean',
        ]);

        $data['activo'] = (bool)($data['activo'] ?? true);
        $data['molecule_id'] = $molecula->id;

        MoleculaProveedorCodigo::create($data);

        return redirect()
            ->route('moleculas.codigos.index', $molecula)
            ->with('mensaje', 'Código de proveedor agregado.');
    }

    public function edit(MoleculaProveedorCodigo $codigo)
    {
        $molecula = $codigo->molecula;
        return view('moleculas.codigos.edit', compact('molecula','codigo'));
    }

    public function update(Request $request, MoleculaProveedorCodigo $codigo)
    {
        $data = $request->validate([
            'nombre_proveedor' => 'nullable|string|max:120',
            'codigo_proveedor' => 'required|string|max:60',
            'activo'           => 'nullable|boolean',
        ]);

        $data['activo'] = (bool)($data['activo'] ?? true);

        $codigo->update($data);

        return redirect()
            ->route('moleculas.codigos.index', $codigo->molecula)
            ->with('mensaje', 'Código de proveedor actualizado.');
    }

    public function destroy(MoleculaProveedorCodigo $codigo)
    {
        $codigo->delete();
        return back()->with('mensaje', 'Código de proveedor eliminado.');
    }
}
