<?php

namespace App\Http\Controllers;

use App\EntregadosApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EntregadosApiController extends Controller
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
            $entregadosapi = EntregadosApi::orderBy('id')->get();

            return DataTables()->of($entregadosapi)
                ->addColumn('action', function ($entregado) {
                    $button = '<button type="button" name="resumen" id="' . $entregado->id . '" class="edit_entregado btn btn-app bg-info tooltipsC" title="Editar entregado"  ><span class="badge bg-teal">Editar</span><i class="fas fa-pen"></i> Editar </button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('menu.usuario.indexAnalista');
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
     * @param  \App\EntregadosApi  $entregadosApi
     * @return \Illuminate\Http\Response
     */
    public function show(EntregadosApi $entregadosApi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EntregadosApi  $entregadosApi
     * @return \Illuminate\Http\Response
     */
    public function edit(EntregadosApi $entregadosApi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EntregadosApi  $entregadosApi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EntregadosApi $entregadosApi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EntregadosApi  $entregadosApi
     * @return \Illuminate\Http\Response
     */
    public function destroy(EntregadosApi $entregadosApi)
    {
        //
    }
}
