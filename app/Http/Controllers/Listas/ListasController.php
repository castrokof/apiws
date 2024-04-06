<?php

namespace App\Http\Controllers\Listas;

use App\Models\Listas\Listas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ListasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {


       $datas = Listas::orderBy('id', 'asc')->get();

        return  DataTables()->of($datas)
            ->addColumn('action', function($datas){
                $button ='<button type="button" name="Detalle" id="'.$datas->id.'" class="listasDetalleAll btn btn-app bg-success tooltipsC" title="listas Detalle"  ><span class="badge bg-teal">Detalle</span><i class="fas fa-list-ul"></i></button>';

            return $button;

        })->addColumn('activo', function($datas){


            if ($datas->activo == "SI") {


            $button ='
            <div class="custom-control custom-switch ">
            <input type="checkbox"  class="check_98 custom-control-input"  id="customSwitch99'.$datas->id.'" value="'.$datas->id.'"  checked>
            <label class="custom-control-label" for="customSwitch99'.$datas->id.'"  valueid="'.$datas->id.'"></label>
            </div>';

        }else{

            $button ='
            <div class="custom-control custom-switch ">
            <input type="checkbox" class="check_98 custom-control-input" id="customSwitch99'.$datas->id.'" value="'.$datas->id.'" >
            <label class="custom-control-label" for="customSwitch99'.$datas->id.'"  valueid="'.$datas->id.'"></label>
            </div>';

        }

        return $button;

    })
        ->rawColumns(['action', 'activo'])
        ->make(true);



    }

    return view('menu.listas.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->ajax()){

            $usuario_id = $request->session()->get('id');

            $rules = array(
                   'slug' => 'required',
                   'nombre' => 'required',
                   'activo' => 'required',
                   'user_id' => 'required'
            );

            $error = Validator::make($request->all(), $rules);

            if($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            Listas::create($request->all());

            return response()->json(['success' => 'ok']);

        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $data = Listas::findOrFail($id);
        return response()->json(['result'=>$data]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data = Listas::findOrFail($id);
        return response()->json(['result'=>$data]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateestado(Request $request)
    {

            if ($request->ajax()) {

                $listaactivo = new Listas();

                $datas = DB::table('listas')->select('activo')->where('id',$request->input('id'))->first();



                foreach($datas as $data){

                  if($data == 'SI'){

                    $listaactivo->findOrFail($request->input('id'))->update([
                        'activo' =>'NO'
                    ]);

                    return response()->json(['respuesta' => 'Lista desactivada', 'titulo' => 'System Fidem', 'icon' => 'warning']);
                    }else if($data == 'NO'){
                        $listaactivo->findOrFail($request->input('id'))->update([
                            'activo' => 'SI'
                        ]);

                        return response()->json(['respuesta' => 'Lista activada correctamente', 'titulo' => 'System Fidem', 'icon' => 'warning']);

                    }

                }

            }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
