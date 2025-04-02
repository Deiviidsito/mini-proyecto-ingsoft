<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth('api')->user()){
            return $next($request); 
        }
        return response()->json([
            'mensaje' => 'No autorizado. Por favor, inicie sesión para acceder',
            'detalles' => 'Se requiere autenticación para acceder a este recurso'
        ], 401);
    }
}
