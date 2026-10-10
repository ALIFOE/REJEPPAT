<?php

namespace App\Http\Controllers;

use App\Http\Middleware\DefinirLangue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Choix de la langue du site (français ou anglais), mémorisé pour la visite. */
class LangueController extends Controller
{
    public function __invoke(Request $request, string $langue): RedirectResponse
    {
        abort_unless(in_array($langue, DefinirLangue::LANGUES, true), 404);

        $request->session()->put(DefinirLangue::SESSION, $langue);

        // Retour à la page consultée, uniquement sur ce site
        $precedente = url()->previous();

        return redirect()->to(str_starts_with($precedente, url('/')) ? $precedente : route('home'));
    }
}
