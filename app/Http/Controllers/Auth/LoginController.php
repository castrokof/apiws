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
        // Establecer sesión del usuario
        $user->setSession();

        // Redirigir a /admin/home si el usuario tiene roles asignados
        if ($user->roles && $user->roles->count() > 0) {
            return redirect('/admin/home');
        }

        // Compatibilidad con sistema antiguo de roles
        $rol = $user;

        if ($rol->rol == '1' || $rol->rol == '2') {
            return redirect('/admin/home');
        } else if ($rol->rol == '3') {
            return redirect('submenu');
        } else {
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect('/login')->withErrors(['error' => 'Este usuario no está activo y no tiene rol asignado']);
        }
    }
    

    
    
    
}
