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

         if(auth()->user()->email == 'castrokof@gmail.com' || auth()->user()->email == 'sistemasmedcol@gmail.com' || auth()->user()->email == 'gerente@saludtempus.com' || auth()->user()->email == 'luzcve@hotmail.com' || auth()->user()->email == 'sistemas3.tempus@gmail.com')

            return $next($request);

            abort(404, "¡No tienes autorización para realizar esta acción.");


    }
}
