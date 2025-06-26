<?php

namespace App\Http\Middleware;

use Closure;

class VerifyUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
     
         if(auth()->user()->rol == '1' || auth()->user()->rol == '2'  || auth()->user()->rol == '3' || auth()->user()->rol == '4' || auth()->user()->rol == '5' || auth()->user()->rol == '6')
            
            return $next($request);
            
            abort(404, "¡No tienes autorización para realizar esta acción.");
           
        
    }
}
