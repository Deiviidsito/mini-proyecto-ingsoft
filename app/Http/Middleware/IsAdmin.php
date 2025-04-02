<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();
        if($user && $user->role_id == 1){
            return $next($request);
        }
        return response()->json([
            'mensaje' => 'Acceso denegado',
            'detalles' => 'No tiene los permisos necesarios para realizar esta acción. Se requiere rol de administrador'
        ], 403);
    }
}
