<?php

namespace App\Http\Controllers;

use App\AnalisisNt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class AnalisisNtController extends Controller
{
    public function __construct()
    {
        $this->middleware(['verified', 'verifyuser']);
    }

    public function index()
    {
        try {
            $analisisNt = AnalisisNt::paginate(50);
            return view('analisis_nt.index', compact('analisisNt'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar los datos: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('analisis_nt.form');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), AnalisisNt::validationRules(), AnalisisNt::validationMessages());

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            AnalisisNt::create($request->all());
            return redirect()->route('analisis-nt.index')->with('success', 'Registro creado exitosamente');
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'unique_codigo_cums_contrato') !== false) {
                return back()->with('error', 'Ya existe un registro con la misma combinación de código medcol, CUMS y contrato')->withInput();
            }
            return back()->with('error', 'Error al crear el registro: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        try {
            $analisisNt = AnalisisNt::findOrFail($id);
            return view('analisis_nt.show', compact('analisisNt'));
        } catch (\Exception $e) {
            return back()->with('error', 'Registro no encontrado');
        }
    }

    public function edit($id)
    {
        try {
            $analisisNt = AnalisisNt::findOrFail($id);
            return view('analisis_nt.form', compact('analisisNt'));
        } catch (\Exception $e) {
            return back()->with('error', 'Registro no encontrado');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), AnalisisNt::validationRules(), AnalisisNt::validationMessages());

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $analisisNt = AnalisisNt::findOrFail($id);
            $analisisNt->update($request->all());
            return redirect()->route('analisis-nt.index')->with('success', 'Registro actualizado exitosamente');
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'unique_codigo_cums_contrato') !== false) {
                return back()->with('error', 'Ya existe un registro con la misma combinación de código medcol, CUMS y contrato')->withInput();
            }
            return back()->with('error', 'Error al actualizar el registro: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $analisisNt = AnalisisNt::findOrFail($id);
            $analisisNt->delete();
            return redirect()->route('analisis-nt.index')->with('success', 'Registro eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el registro: ' . $e->getMessage());
        }
    }

    public function importExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'archivo_excel' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ], [
            'archivo_excel.required' => 'Debe seleccionar un archivo',
            'archivo_excel.mimes' => 'El archivo debe ser de tipo Excel (.xlsx, .xls) o CSV',
            'archivo_excel.max' => 'El archivo no puede ser mayor a 10MB'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $file = $request->file('archivo_excel');
            $data = Excel::toArray([], $file);
            
            if (empty($data) || empty($data[0])) {
                return back()->with('error', 'El archivo está vacío o no tiene el formato correcto');
            }

            $rows = $data[0];
            $header = array_shift($rows); // Remover la primera fila (cabeceras)
            
            $insertados = 0;
            $actualizados = 0;
            $errores = [];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                try {
                    if (count($row) < 9) {
                        $errores[] = "Fila " . ($index + 2) . ": Datos incompletos";
                        continue;
                    }

                    $datos = [
                        'codigo_cliente' => $row[0] ?? null,
                        'codigo_medcol' => $row[1] ?? '',
                        'agrupador' => $row[2] ?? null,
                        'nombre' => $row[3] ?? '',
                        'cums' => $row[4] ?? '',
                        'expediente' => $row[5] ?? null,
                        'valor_unitario' => is_numeric($row[6]) ? $row[6] : null,
                        'frecuencia_uso' => $row[7] ?? null,
                        'contrato' => $row[8] ?? ''
                    ];

                    // Validar datos obligatorios
                    if (empty($datos['codigo_medcol']) || empty($datos['nombre']) || 
                        empty($datos['cums']) || empty($datos['contrato'])) {
                        $errores[] = "Fila " . ($index + 2) . ": Faltan campos obligatorios";
                        continue;
                    }

                    // Buscar si existe registro con la misma llave única
                    $existente = AnalisisNt::where([
                        'codigo_medcol' => $datos['codigo_medcol'],
                        'cums' => $datos['cums'],
                        'contrato' => $datos['contrato']
                    ])->first();

                    if ($existente) {
                        $existente->update($datos);
                        $actualizados++;
                    } else {
                        AnalisisNt::create($datos);
                        $insertados++;
                    }

                } catch (\Exception $e) {
                    $errores[] = "Fila " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            DB::commit();

            $mensaje = "Importación completada. Insertados: {$insertados}, Actualizados: {$actualizados}";
            
            if (!empty($errores)) {
                $mensaje .= ". Errores encontrados: " . implode('; ', array_slice($errores, 0, 5));
                if (count($errores) > 5) {
                    $mensaje .= " y " . (count($errores) - 5) . " errores más.";
                }
            }

            return back()->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    public function getDataTable(Request $request)
    {
        $query = AnalisisNt::query();

        // Aplicar filtros específicos por columna
        if ($request->has('codigo_cliente') && !empty($request->codigo_cliente)) {
            $query->where('codigo_cliente', 'like', "%{$request->codigo_cliente}%");
        }

        if ($request->has('codigo_medcol') && !empty($request->codigo_medcol)) {
            $query->where('codigo_medcol', 'like', "%{$request->codigo_medcol}%");
        }

        if ($request->has('nombre') && !empty($request->nombre)) {
            $query->where('nombre', 'like', "%{$request->nombre}%");
        }

        // Filtro de contrato: solo aplicar si no es "Todos" y no está vacío
        if ($request->has('contrato') && !empty($request->contrato) && $request->contrato !== 'Todos') {
            $query->where('contrato', 'like', "%{$request->contrato}%");
        }

        // Búsqueda global de DataTables
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('codigo_medcol', 'like', "%{$search}%")
                  ->orWhere('nombre', 'like', "%{$search}%")
                  ->orWhere('cums', 'like', "%{$search}%")
                  ->orWhere('contrato', 'like', "%{$search}%");
            });
        }

        $totalRecords = AnalisisNt::count();
        $filteredRecords = $query->count();

        if ($request->has('order')) {
            $orderColumn = $request->order[0]['column'];
            $orderDirection = $request->order[0]['dir'];
            $columns = ['id', 'codigo_cliente', 'codigo_medcol', 'agrupador', 'nombre', 'cums', 'expediente', 'valor_unitario', 'frecuencia_uso', 'contrato'];

            if (isset($columns[$orderColumn])) {
                $query->orderBy($columns[$orderColumn], $orderDirection);
            }
        }

        $start = $request->start ?? 0;
        $length = $request->length ?? 10;

        $data = $query->skip($start)->take($length)->get();

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }
}
