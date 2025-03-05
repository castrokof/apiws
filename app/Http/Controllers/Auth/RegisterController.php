<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
   /*public function __construct()
    {
        $this->middleware('guest');
    }*/

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'drogueria'=> ['required', 'string'],
            'rol'=> ['required', 'string', 'min:1', 'max:1'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'drogueria' => $data['drogueria'],
            'rol' => $data['rol'],
        ]);
    }
    
    
      public function usuariosApiws(Request $request){
        
        
          if ($request->ajax()) {
              $usuarioapiws = User::all();
                return  DataTables()->of($usuarioapiws) ->addColumn('action', function ($editar) {
                        return '<button class="edit_user btn btn-small btn-warning tooltipsC" type="button" title="Editar" id="' . $editar->id . '" value="' . $editar->id . '"><i class="fas fa-user-edit"></i></button>';
                    })->rawColumns([
                    'action'
                ])
                ->make(true);
            }


                return view('auth.list');
    }
    
    public function editar(Request $request, $id)
   
    {
         // Verifica si la solicitud es AJAX
    if ($request->ajax()) {
       // Valida que el ID sea un entero
            if (!is_numeric($id)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ID inválido'
                ], 400);
            }

        try {
            // Intenta encontrar el usuario
            $usuarioedit = User::findOrFail($request->id);

            // Retorna el usuario en formato JSON
            return response()->json([
                'status' => 'success',
                'data' => $usuarioedit
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Si el usuario no se encuentra, retorna un mensaje de error
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario no encontrado'
            ], 404);
        }
    } else {
        // Si la solicitud no es AJAX, retorna un mensaje de error
        return response()->json([
            'status' => 'error',
            'message' => 'Solicitud no válida'
        ], 400);
    }
        
    }
    
  public function actualizar(Request $request, $id)
    {
        
       
        $request->validate([
            'name' => 'required|string|max:255',
            'drogueria' => 'sometimes|string|max:255',
            'rol' => 'sometimes|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
    
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->drogueria = $request->drogueria;
        $user->rol = $request->rol;
        $user->email_verified_at = $request->email_verified_at;
        
    
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        $user->save();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Usuario actualizado correctamente'
        ]);
    }
      
  
        
    
}
