<?php

namespace App\Http\Controllers\Compte;

use Illuminate\Http\Request;
use Illuminate\View\View;

/** Tableau de bord du client et historique de ses commandes. */
class CompteController extends Controller
{
    /** Commandes encore en cours de traitement */
    private const STATUTS_EN_COURS = ['en_attente', 'confirmee', 'en_livraison'];

    public function index(Request $request): View
    {
        $commandes = $request->user()->commandes();

        return view('compte.index', [
            'chiffres' => [
                'commandes' => (clone $commandes)->count(),
                'en_cours' => (clone $commandes)->whereIn('statut', self::STATUTS_EN_COURS)->count(),
                'depense' => (clone $commandes)->valide()->sum('total'),
                'livrees' => (clone $commandes)->where('statut', 'livree')->count(),
            ],
            'commandeEnCours' => (clone $commandes)->whereIn('statut', self::STATUTS_EN_COURS)->latest()->first(),
            'dernieresCommandes' => (clone $commandes)->latest()->limit(5)->get(),
        ]);
    }

    public function commandes(Request $request): View
    {
        return view('compte.commandes', [
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
