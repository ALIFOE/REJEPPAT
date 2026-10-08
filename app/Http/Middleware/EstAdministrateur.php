<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/** Réserve l'administration aux comptes marqués « is_admin ». */
class EstAdministrateur
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->is_admin) {
            Auth::logout();
            $request->session()->invalidate();

            return redirect()->route('admin.connexion')
                ->withErrors(['email' => 'Ce compte n’a pas accès à l’administration.']);
        }

        return $next($request);
    }
}
