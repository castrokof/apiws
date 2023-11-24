<?php

namespace App\Http\Controllers;

//namespace App\Http\Controllers\Auth;


use App\User;
use App\UsuarioApi;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


class UsuarioApiContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

       $this->createapi($request);

       if ($request->ajax()) {
                $usuarioapi = UsuarioApi::all();
                return  DataTables()->of($usuarioapi)
                ->make(true);
            }


                return view('menu.usuario.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     function createapi()
    {

         $email = 'sistemas.saludtempus@gmail.com'; // Auth::user()->email
         $password = '12345678';

         $response = Http::post("http://190.145.32.226:8000/api/acceso",
         [ 'email' =>  $email,
           'password' => $password,
         ]);



       $prueba = $response->json();
       $token=$prueba["token"];

       $responseusers = Http::withToken($token)->get("http://190.145.32.226:8000/api/perfiluser");

      $usersapi = $responseusers->json();

         foreach($usersapi['data'] as $user){


          $existe =  UsuarioApi::where('email',$user['email'])->count();

            if ($existe == 0 || $existe == '') {
                UsuarioApi::create(
                    [   'idapi' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'created_api' => $user['created_at'],
                        'updated_api' => $user['updated_at'],
                    ]);

            }



         }

         Http::withToken($token)->get("http://190.145.32.226:8000/api/closeallacceso");
    }


    


}
