<?php

namespace App\Http\Controllers\Compras\Medcol3;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Listas\ListasDetalle;
use App\Models\compras\medcol3\MedcolCotizaciones3;
use App\Models\compras\medcol3\MedcolCotiGeneral3;
use App\Imports\CotizacionesImport;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Return_;
use stdClass;
use Maatwebsite\Excel\Facades\Excel;

class ControllerMedcolCotizaciones3 extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCotizaciones(Request $request)
    {

        if ($request->ajax()) {


       $datas = MedcolCotiGeneral3::orderBy('id', 'asc')->get();

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

    return view('menu.Cotizaciones.Medcol3.index');
    }


    public function import(Request $request)
    {
       $user_id = Auth::user()->id;
        
       
        if ($request->ajax()) {
            $file = $request->file('file');

            if ($file == null) {
                return response()->json(['mensaje' => 'vacio']);
            }

            // Guardar metadatos del archivo
            $archivo = new MedcolCotiGeneral3();
            $archivo->archivo = $file->getClientOriginalName();
            $archivo->registros = 0; // Se actualizará después de importar
            $archivo->fecha_inicio = $request->fecha_inicio;
            $archivo->fecha_fin = $request->fecha_fin;
            $archivo->estado = 'activo';
            $archivo->user_id = $user_id;
            $archivo->save();
            
            
            $name=time().$file->getClientOriginalName();  
                              
            //$destinationPath = public_path('xlsxin/');
            //$file->move($destinationPath, $name);
            $path = $file->storeAs('uploads', $name, 'local');
            //$path=$destinationPath.$name;
             

            // Importar datos desde el archivo Excel
            $import = new CotizacionesImport($request->fecha_inicio, $request->fecha_fin, $user_id, $archivo->id );
            Excel::import($import, storage_path('app/' . $path));

            // Actualizar la cantidad de registros importados
            $archivo->registros = $import->getRowCount();
            $archivo->save();

            if($import->getRowCount() == 0){
                return response()->json(['mensaje' => 'ng']);
            }else{
                
                return response()->json(['mensaje' => 'ok']);    
            }
            
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

        $data = MedcolCotiGeneral3::findOrFail($id);
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

        $data = MedcolCotiGeneral3::findOrFail($id);
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

                $listaactivo = new MedcolCotiGeneral3();

                $datas = DB::table('base_coti_general_medcol3')->select('activo')->where('id',$request->input('id'))->first();

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
    
     public function indexDetalleCotizaciones(Request $request)
    {

         $idlist = $request->id;

         if ($request->ajax()) {

            if ($idlist != null) {

                $datas = MedcolCotizaciones3::orderBy('id', 'asc')
                ->where('listas_id', "=", $idlist)->get();

            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="Detalle" id="' . $datas->id . '" class="itemsEditar btn btn-app bg-warning tooltipsC" title="Editar"  ><span class="badge bg-teal">Editar</span><i class="fas fa-edit"></i></button>';

                    return $button;
                })->addColumn('activo', function ($datas) {


                    if ($datas->activo == "SI") {


                        $button = '
             <div class="custom-control custom-switch ">
             <input type="checkbox"  class="check_99 custom-control-input"  id="customSwitch999' . $datas->id . '" value="' . $datas->id . '"  checked>
             <label class="custom-control-label" for="customSwitch999' . $datas->id . '"  valueid="' . $datas->id . '"></label>
             </div>';
                    } else {

                        $button = '
             <div class="custom-control custom-switch ">
             <input type="checkbox" class="check_99 custom-control-input" id="customSwitch999' . $datas->id . '" value="' . $datas->id . '" >
             <label class="custom-control-label" for="customSwitch999' . $datas->id . '"  valueid="' . $datas->id . '"></label>
             </div>';
                    }

                    return $button;
                })
                ->rawColumns(['action', 'activo'])
                ->make(true);


            } 
            
            //else {

              //  $datas = MedcolCotizaciones3::orderBy('id', 'asc')
                //->where('listas_id', '=', null)->get();
                //return  DataTables()->of($datas)
                  //      ->make(true);

            //}
        }


        return view('menu.Cotizaciones.Medcol3.index');
    }
 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDetalle(Request $request)
    {
        if ($request->ajax()) {

            $rules = array(
                'slug' => 'required',
                'nombre' => 'required',
                'activo' => 'required',
                'user_id' => 'required',
                'listas_id' => 'required'
            );

            $error = Validator::make($request->all(), $rules);

            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            MedcolCotizaciones3::create($request->all());

            return response()->json(['success' => 'ok']);
        }
    }
    
}
