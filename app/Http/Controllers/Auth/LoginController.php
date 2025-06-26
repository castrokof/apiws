<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
     protected function authenticated(Request $request, $user)
    {
        
        
        
        $rol = $user;
        
        if ($rol->rol == '1' || $rol->rol == '2'  || $rol->rol == '6'  ) {
           
            return redirect(RouteServiceProvider::HOME);

        }else if ($rol->rol == '3' || $rol->rol == '4') {
          
            return redirect('submenu');

        
        }else if ( $rol->rol == '5') {
          
            return redirect(RouteServiceProvider::HOME);

        
        }else{
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect('/login')->withErrors(['error'=>'Este usuario no esta activo y no tiene rol ']);
        }

    }
    

    
    
    
}
