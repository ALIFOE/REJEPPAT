<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Réserve l'administration aux rôles « super_admin » et « admin ». */
class EstAdministrateur
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->estAdministrateur()) {
            // Un client connecté reste connecté et retourne sur son espace
            return redirect()->route('compte.index')
                ->with('erreur', 'Votre compte n’a pas accès à l’administration.');
        }

        return $next($request);
    }
}
