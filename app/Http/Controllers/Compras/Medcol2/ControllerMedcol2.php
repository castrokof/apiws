<?php

namespace App\Http\Controllers\Compras\Medcol2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Listas\ListasDetalle;
use App\Models\compras\medcol2\Medcolterceros2;
use App\Models\compras\medcol2\Medcolmedicamentos2;
use App\Models\compras\medcol2\Medcolcompras2;
use App\Models\compras\Documentos;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;

class ControllerMedcol2 extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('menu.Compras.Medcol2.index');
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


            $result = Medcolmedicamentos2::orderBy('id')
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
    
    public function proveedores(Request $request)
    {
        $array=[];


        if($request->has('q'))
        {
            $term = $request->get('q');


            $result = Medcolterceros2::orderBy('id')
            ->where('estado', '1')
            ->where(function ($query) use ($term) {
                $query->where('nombre_sucursal', 'LIKE', '%' . $term . '%')
                      ->orWhere('codigo_tercero', 'LIKE', '%' . $term . '%');
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
    public function Ordcompras(Request $request)
    {
        //
        if ($request->ajax()) {
            $ordenes_compra_medcol2 = Medcolcompras2::with('proveedor')
            ->orderBy('created_at')
            ->get();
            
            return DataTables()->of($ordenes_compra_medcol2)
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
        return view('menu.Compras.Medcol2.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showproveedor($id)
    {
        if (request()->ajax()) {
            $pendiente = Medcolterceros2::where('id', '=', $id)->first();
            
                return response()->json([
                    'proveedor' => $pendiente
                ]);
        }
        return view('menu.Compras.Medcol2.index');
    }

 public function documentos(Request $request)
    {


        $array=[];


        if($request->has('q'))
        {
            $term = $request->get('q');


            array_push($array, Documentos::orderBy('documento')->where([['documento', 'LIKE', '%' . $term . '%'],['documento','OCSM']])
            ->get());

            return response()->json(['array'=>$array]);

        }
        else {



                array_push($array, Documentos::orderBy('documento')
                ->where('documento','OCSM')
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
