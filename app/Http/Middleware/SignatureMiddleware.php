<?php

namespace App\Http\Middleware;

use Closure;

class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

     # Este middleware se ejecuta DESPUES de una respuesta.
    public function handle($request, Closure $next, $header = 'X-Name')
    {
        $response = $next($request);

        # Seteamos una nueva cabecera con el nombre de nuestra aplicacion
        $response->headers->set($header, config('app.name'));

        return $response;
    }
}
