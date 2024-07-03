<?php

namespace App\Http\Controllers\Compras;




use App\Http\Controllers\Controller;
use App\Models\compras\Documentos;
use Illuminate\Http\Request;

class ControllerDocumentos extends Controller
{
   public function index1()
    {
        return view('menu.listas.documentos.index');
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $datas = Documentos::orderBy('id', 'asc')->get();

            return  DataTables()->of($datas)
                ->addColumn('action', function($datas){
                    $button ='<button type="button" name="Detalle" id="'.$datas->id.'" class="listasDetalleAll btn btn-app bg-success tooltipsC" title="listas Detalle"  ><span class="badge bg-teal">Listas</span><i class="fas fa-list-ul"></i>Detalle</button>';
    
                return $button;
    
            })
            ->rawColumns(['action'])
            ->make(true);

            
        }
        
        return view('menu.listas.documentos.index');
      
    }

    public function store(Request $request)
    {
        Documentos::create($request->all());

        return response()->json(['success' => 'ok']);

    }
    
    
     public function select(Request $request)
    {


        $array=[];


        if($request->has('q'))
        {
            $term = $request->get('q');


            array_push($array, Documentos::orderBy('documento')->where('nombre', 'LIKE', '%' . $term . '%')
            ->get());

            return response()->json(['array'=>$array]);

        }
        else {



                array_push($array, Documentos::orderBy('documento')
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
