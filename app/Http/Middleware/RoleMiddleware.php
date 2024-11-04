<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        // Define the allowed roles
        $roles = ['admin', 'director', 'padre', 'tesorero', 'cajero'];
        
        // Verifica si el rol del usuario está en el array de roles permitidos
        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'No tienes acceso a esta página');
        }
        return $next($request);
    }
}
