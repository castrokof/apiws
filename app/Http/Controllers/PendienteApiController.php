<?php

namespace App\Http\Controllers;

use App\PendientesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PendienteApiController extends Controller
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
            $pendientesapi = PendientesApi::orderBy('id')->get();

            return DataTables()->of($pendientesapi)
                ->addColumn('action', function ($pendiente) {
                    $button = '<button type="button" name="resumen" id="' . $pendiente->id . '" class="edit_pendiente btn btn-app bg-info tooltipsC" title="Editar pendiente"  ><span class="badge bg-teal">Editar</span><i class="fas fa-pen"></i> Editar </button>';

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
    public function createapendientespi()
    {
        $email = 'sistemas.saludtempus@gmail.com'; // Auth::user()->email
        $password = '12345678';

        $response = Http::post(
            "http://190.145.32.226:8000/api/acceso",
            [
                'email' =>  $email,
                'password' => $password,
            ]
        );



        $prueba = $response->json();
        $token = $prueba["token"];

        $responsefacturas = Http::withToken($token)->get("http://190.145.32.226:8000/api/factura");

        $facturassapi = $responsefacturas->json();

        //dd($facturassapi);
        foreach ($facturassapi['data'] as $factura) {


            $existe =  PendientesApi::where('factura', $factura['factura'])->count();

            if ($existe == 0 || $existe == '') {
                PendientesApi::create([
                    'Tipodocum' => $factura['Tipodocum'],
                    'cantdpx' => $factura['cantdpx'],
                    'cantord' => $factura['cantord'],
                    'fecha_factura' => $factura['fecha_factura'],
                    'fecha' => $factura['fecha'],
                    'historia' => $factura['historia'],
                    'apellido1' => $factura['apellido1'],
                    'apellido2' => $factura['apellido2'],
                    'nombre1' => $factura['nombre1'],
                    'nombre2' => $factura['nombre2'],
                    'cantedad' => $factura['cantedad'],
                    'direcres' => $factura['direcres'],
                    'telefres' => $factura['telefres'],
                    'documento' => $factura['documento'],
                    'factura' => $factura['factura'],
                    'codigo' => $factura['codigo'],
                    'nombre' => $factura['nombre'],
                    'cums' => $factura['cums'],
                    'cantidad' => $factura['cantidad'],
                    'cajero' => $factura['cajero']
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
    public function porentregar(Request $request)
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
