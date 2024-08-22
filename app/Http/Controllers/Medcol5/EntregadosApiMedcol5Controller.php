<?php

namespace App\Http\Controllers\Medcol5;

use App\Http\Controllers\Controller;
use App\Models\Medcol5\EntregadosApiMedcol5;

use Illuminate\Http\Request;

class EntregadosApiMedcol5Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /* $this->createapendientespi($request); */

        if ($request->ajax()) {
            $entregadosapi = EntregadosApiMedcol5::orderBy('id')->get();

            return DataTables()->of($entregadosapi)
                ->addColumn('action', function ($entregado) {
                    $button = '<button type="button" name="resumen" id="' . $entregado->id . '" class="edit_entregado btn btn-app bg-info tooltipsC" title="Editar entregado"  ><span class="badge bg-teal">Editar</span><i class="fas fa-pen"></i> Editar </button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('menu.Medcol5.indexAnalista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Medcol5\EntregadosApiMedcol5  $entregadosApiMedcol5
     * @return \Illuminate\Http\Response
     */
    public function show(EntregadosApiMedcol5 $entregadosApiMedcol5)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Medcol5\EntregadosApiMedcol5  $entregadosApiMedcol5
     * @return \Illuminate\Http\Response
     */
    public function edit(EntregadosApiMedcol5 $entregadosApiMedcol5)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Medcol5\EntregadosApiMedcol5  $entregadosApiMedcol5
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EntregadosApiMedcol5 $entregadosApiMedcol5)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Medcol5\EntregadosApiMedcol5  $entregadosApiMedcol5
     * @return \Illuminate\Http\Response
     */
    public function destroy(EntregadosApiMedcol5 $entregadosApiMedcol5)
    {
        //
    }
}
