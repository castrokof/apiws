<?php

namespace App\Http\Controllers;

use App\Models\DiagnosticosCie10;
use Illuminate\Http\Request;

class DiagnosticosCie10Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectcie10(Request $request)
    {

        $array = [];


        if ($request->has('q')) {
            $term = $request->get('q');


            $result = DiagnosticosCie10::orderBy('id')
                ->where('estado', 'ACTIVO')
                ->where(function ($query) use ($term) {
                    $query->where('descripcion', 'LIKE', '%' . $term . '%')
                        ->orWhere('codigo', 'LIKE', '%' . $term . '%');
                })
                ->get();

            array_push($array, $result);

            return response()->json(['array' => $array]);
        }

        /*else{



                array_push($array, DiagnosticosCie10::orderBy('codigo')->where([
                    ['estado', 'ACTIVO']])
                ->get());


                return response()->json(['array'=>$array]);



        }*/
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
