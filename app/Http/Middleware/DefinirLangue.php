<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

/**
 * Langue du site public : le français par défaut, l'anglais si le visiteur l'a choisi
 * (ou ?lang=en dans l'adresse, pratique pour partager un lien en anglais).
 * L'administration reste en français.
 */
class DefinirLangue
{
    public const LANGUES = ['fr', 'en'];

    public const SESSION = 'langue';

    public function handle(Request $request, Closure $next): Response
    {
        if (in_array($request->query('lang'), self::LANGUES, true)) {
            $request->session()->put(self::SESSION, $request->query('lang'));
        }

        $langue = $request->is('admin', 'admin/*') ? 'fr' : $request->session()->get(self::SESSION, 'fr');

        if (in_array($langue, self::LANGUES, true)) {
            app()->setLocale($langue);
            Carbon::setLocale($langue);
        }

        return $next($request);
    }
}
