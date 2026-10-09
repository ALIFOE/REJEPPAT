<?php

namespace App\Http\Controllers\Compte;

use Illuminate\Http\Request;
use Illuminate\View\View;

/** Tableau de bord du client et historique de ses commandes. */
class CompteController extends Controller
{
    public function index(Request $request): View
    {
        return view('compte.index', [
            'commandes' => $request->user()->commandes()->latest()->paginate(10),
        ]);
    }

    public function commande(Request $request, string $numero): View
    {
        // Seules les commandes du compte connecté sont visibles (404 sinon)
        return view('compte.commande', [
            'commande' => $request->user()->commandes()->with('lignes')->where('numero', $numero)->firstOrFail(),
        ]);
    }
}
