<?php

namespace App\Http\Controllers\Compras;
use App\Models\compras\MoleculaProveedorCodigo;

use App\Http\Controllers\Controller;
use App\Models\compras\Molecula;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProveedoresImport;

class MoleculaController extends Controller
{
    public function index(Request $r)
    {
        $q = Molecula::query();

        // --- filtros ---
        if ($r->filled('q')) {
            $term = trim($r->q);
            $q->where(function($x) use ($term){
                $x->where('codigo', 'like', "%{$term}%")
                ->orWhere('nombre', 'like', "%{$term}%")
                ->orWhere('forma',  'like', "%{$term}%")
                ->orWhere('marca',  'like', "%{$term}%");
            });
        }
        if ($r->filled('codigo'))       $q->where('codigo', 'like', '%'.trim($r->codigo).'%');
        if ($r->filled('descripcion'))  $q->where('nombre', 'like', '%'.trim($r->descripcion).'%');
        if ($r->filled('marca'))        $q->where('marca',  'like', '%'.trim($r->marca).'%');
        if ($r->filled('presentacion')) $q->where('forma',  'like', '%'.trim($r->presentacion).'%');
        
        // --- orden ---
        $sort = $r->get('sort', 'codigo');
        $dir  = strtolower($r->get('dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $map  = [
            'codigo_rfast' => 'codigo',
            'descripcion'  => 'nombre',
            'presentacion' => 'forma',
            'activo'       => 'estado',
            'codigo'       => 'codigo',
            'nombre'       => 'nombre',
            'forma'        => 'forma',
            'marca'        => 'marca',
            'estado'       => 'estado',
            'id'           => 'id',
        ];
        $sortCol = $map[$sort] ?? 'codigo';

        $moleculas = $q->orderBy($sortCol, $dir)
                    ->paginate(20)
                    ->appends($r->query());

        // === AQUÍ EL CAMBIO CLAVE ===
        if ($r->ajax()) {
            return response()->json([
                'table'      => view('moleculas.partials.table', compact('moleculas'))->render(),
                'pagination' => view('moleculas.partials.pagination', compact('moleculas'))->render(),
            ]);
        }

        return view('moleculas.index', compact('moleculas'));
    }

    public function create()
    {
        return view('moleculas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo_rfast' => 'required|string|max:30|unique:moleculas,codigo_rfast',
            'descripcion'  => 'required|string',
            'marca'        => 'nullable|string|max:80',
            'presentacion' => 'nullable|string|max:80',
            'activo'       => 'nullable|boolean',
        ]);

        $data['activo'] = (bool)($data['activo'] ?? true);

        $mol = Molecula::create($data);
        return redirect()->route('moleculas.index')->with('mensaje', "Molécula {$mol->codigo_rfast} creada.");
    }

    public function edit(Molecula $molecula)
    {
        return view('moleculas.edit', compact('molecula'));
    }

    public function update(Request $request, Molecula $molecula)
    {
        $data = $request->validate([
            'descripcion'  => 'required|string',
            'marca'        => 'nullable|string|max:80',
            'presentacion' => 'nullable|string|max:80',
            'activo'       => 'nullable|boolean',
        ]);

        $data['activo'] = (bool)($data['activo'] ?? true);

        $molecula->update($data);
        return redirect()->route('moleculas.index')->with('mensaje', "Molécula actualizada.");
    }

    public function destroy(Molecula $molecula)
    {
        $molecula->delete();
        return back()->with('mensaje', 'Molécula eliminada.');
    }

     public function form()
    {
        return view('moleculas.import');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => ['required','file','mimes:xlsx,xls,csv','max:10240'],
        ]);

        $file = $request->file('file');

        try {
            // Leer el archivo temporalmente solo para validar la estructura
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $headers = $sheet->rangeToArray('A1:C1', null, true, true, true)[1];

            $esperados = ['CodigoRfast', 'NombreProveedor', 'CodigoProveedor'];

            // Convertir ambos a minúsculas para no fallar por mayúsculas/minúsculas
            $headers = array_map('strtolower', array_values($headers));
            $esperados = array_map('strtolower', $esperados);

            if ($headers !== $esperados) {
                return back()->with('errors_import', [
                    'El archivo no tiene la estructura correcta. 
                    Debe contener las columnas: CodigoRfast, NombreProveedor, CodigoProveedor (en ese orden).'
                ]);
            }

            // Si todo bien, proceder al import
            Excel::import(new \App\Imports\ProveedoresImport, $file);

            return back()->with('success', 'Archivo importado correctamente.');
        } catch (\Throwable $e) {
            return back()->with('errors_import', [
                'Error al importar: '.$e->getMessage()
            ]);
        }
    }
}
