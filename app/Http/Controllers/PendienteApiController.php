<?php

namespace App\Http\Controllers;

use App\PendientesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PendienteApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->createapendientespi($request);

        if ($request->ajax()) {
                 $pendientesapi = PendientesApi::all();
                 return  DataTables()->of($pendientesapi)
                 ->make(true);
             }


                 return view('menu.usuario.indexAnalista');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createapendientespi()
    {
        $email = 'sistemas.saludtempus@gmail.com'; // Auth::user()->email
        $password = '12345678';

        $response = Http::post("http://190.145.32.226:8000/api/acceso",
        [ 'email' =>  $email,
          'password' => $password,
        ]);



      $prueba = $response->json();
      $token=$prueba["token"];

      $responsefacturas = Http::withToken($token)->get("http://190.145.32.226:8000/api/factura");

     $facturassapi = $responsefacturas->json();

     //dd($facturassapi);
        foreach($facturassapi['data'] as $factura){


         $existe =  PendientesApi::where('email',$factura['email'])->count();

           if ($existe == 0 || $existe == '') {
            PendientesApi::create(
                   [   'idapi' => $factura['id'],
                       'name' => $factura['name'],
                       'email' => $factura['email'],
                       'created_api' => $factura['created_at'],
                       'updated_api' => $factura['updated_at'],
                   ]);

           }



        }

        Http::withToken($token)->get("http://190.145.32.226:8000/api/closeallacceso");
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
