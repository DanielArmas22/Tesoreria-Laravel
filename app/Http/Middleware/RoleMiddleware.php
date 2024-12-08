<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class RoleMiddleware
{
    /**
     * Manejar una solicitud entrante.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  ...$roles // Parámetros variables, se pueden pasar múltiples roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();
        // Verificar si el usuario está autenticado
        if (! $user) {
            // Redirigir o lanzar error si no está logueado
            return redirect('/login');
        }

        // Verificar si el rol del usuario está entre los roles permitidos
        if (! in_array($user->rol, $roles)) {
            // Aquí puedes manejar la respuesta no autorizada
            abort(403, 'La página que buscas,  no existe :(');
        }

        return $next($request);
    }
}
