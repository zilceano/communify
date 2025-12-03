<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está logado E se é admin
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        // Se não for admin, manda de volta pro dashboard
        return redirect('/dashboard')->with('error', 'Acesso negado.');
    }
}