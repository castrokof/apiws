<?php

namespace App\Http\Controllers\Compras\Medcol4;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Listas\ListasDetalle;
use App\Models\compras\medcol4\Medcolterceros4;
use App\Models\compras\medcol4\Medcolmedicamentos4;
use App\Models\compras\medcol4\Medcolcompras4;
use App\Models\compras\Documentos;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;

class ControllerMedcol4 extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('menu.Compras.Medcol4.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function articulos(Request $request)
    {
        $array=[];


        if($request->has('q'))
        {
            $term = $request->get('q');


            $result = Medcolmedicamentos4::orderBy('id')
            ->where('estado', 'A')
            ->where(function ($query) use ($term) {
                $query->where('nombre', 'LIKE', '%' . $term . '%')
                      ->orWhere('codigo', 'LIKE', '%' . $term . '%');
            })
            ->get();

            array_push($array, $result);

            return response()->json(['array'=>$array]);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function proveedores(Request $request)
    {
        $array=[];


        if($request->has('q'))
        {
            $term = $request->get('q');


            $result = Medcolterceros4::orderBy('id')
            ->where('estado', 'ACTIVO')
            ->where(function ($query) use ($term) {
                $query->where('nombre_sucursal', 'LIKE', '%' . $term . '%')
                      ->orWhere('codigo_tercero', 'LIKE', '%' . $term . '%');
            })
            ->get();

            array_push($array, $result);

            return response()->json(['array'=>$array]);
        }
        
    }

    public function Ordcompras(Request $request)
    {
        //
        if ($request->ajax()) {
            $ordenes_compra_medcol4 = Medcolcompras4::with('proveedor')
            ->orderBy('created_at')
            ->get();
            
            return DataTables()->of($ordenes_compra_medcol4)
            ->addColumn('proveedor_nombre', function ($orden) {
                return $orden->proveedor ? $orden->proveedor->nombre_sucursal : 'N/A';
            })
            ->addColumn('action', function ($pendiente) {
                    $button = '<button type="button" name="show_detail" id="' . $pendiente->id . '
                    " class="show_detail btn btn-app bg-secondary tooltipsC" title="Detalle"  >
                    <span class="badge bg-teal">Detalle</span><i class="fas fa-prescription-bottle-alt"></i> </button>';
                    $button2 = '<button type="button" name="edit_orden" id="' . $pendiente->id . '
                    " class="edit_orden btn btn-app bg-info tooltipsC" title="Editar"  >
                    <span class="badge bg-teal">Editar</span><i class="fas fa-pencil-alt"></i> </button>';

                    return $button . ' ' . $button2;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('menu.Compras.Medcol4.index');
    }

    public function showproveedor($id)
    {
        if (request()->ajax()) {
            $pendiente = Medcolterceros4::where('id', '=', $id)->first();
            
                return response()->json([
                    'codigo_tercero' => $pendiente->codigo_tercero,
                    'nombre_sucursal' => $pendiente->nombre_sucursal
                ]);
        }
        return view('menu.Compras.Medcol4.index');
    }

   // Funcions de los documentos

 public function documentos(Request $request)
    {


        $array=[];


        if($request->has('q'))
        {
            $term = $request->get('q');


            array_push($array, Documentos::orderBy('documento')->where([['documento', 'LIKE', '%' . $term . '%'],['documento','OCAU']])
            ->get());

            return response()->json(['array'=>$array]);

        }
        else {



                array_push($array, Documentos::orderBy('documento')
                ->where('documento','OCAU')
                ->get());


                return response()->json(['array'=>$array]);



        }

    }
    
    
     public function consecutivo($id)
    {
        if(request()->ajax()){
            $documento = Documentos::where('id', '=', $id)->first();
            return response()->json(['documento'=>$documento]);
        }
    }
    
}
