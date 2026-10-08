<?php

namespace App\Http\Controllers\Admin;

use App\Models\Actualite;
use App\Models\Commande;
use App\Models\CommandeLigne;
use App\Models\DemandeService;
use App\Models\Ferme;
use App\Models\MessageContact;
use App\Models\Produit;
use App\Models\Projet;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class TableauDeBordController extends Controller
{
    private const MOIS = 12;

    public function index(): View
    {
        $debut = now()->startOfMonth()->subMonths(self::MOIS - 1);
        $mois = collect(range(0, self::MOIS - 1))->map(fn ($i) => $debut->copy()->addMonths($i));
        $cle = fn (Carbon $date) => $date->format('Y-m');

        $commandes = Commande::where('created_at', '>=', $debut)->get(['statut', 'total', 'mode_paiement', 'created_at']);
        $valides = $commandes->where('statut', '!=', 'annulee');
        $demandes = DemandeService::where('created_at', '>=', $debut)->get(['service', 'created_at']);
        $messages = MessageContact::where('created_at', '>=', $debut)->get(['created_at']);

        $parMois = fn (Collection $liste, ?callable $valeur = null) => $mois->map(
            fn (Carbon $m) => $liste->filter(fn ($e) => $cle($e->created_at) === $cle($m))
                ->sum($valeur ?? fn () => 1)
        )->values();

        $topProduits = CommandeLigne::query()
            ->whereHas('commande', fn ($q) => $q->valide())
            ->selectRaw('nom_produit, SUM(quantite) as quantite, SUM(total) as montant')
            ->groupBy('nom_produit')
            ->orderByDesc('quantite')
            ->limit(6)
            ->get();

        $ceMois = now()->startOfMonth();

        return view('admin.tableau', [
            'chiffres' => [
                'ca_total' => Commande::valide()->sum('total'),
                'ca_mois' => Commande::valide()->where('created_at', '>=', $ceMois)->sum('total'),
                'commandes_mois' => Commande::where('created_at', '>=', $ceMois)->count(),
                'commandes_attente' => Commande::where('statut', 'en_attente')->count(),
                'demandes_nouvelles' => DemandeService::where('statut', 'nouvelle')->count(),
                'demandes_total' => DemandeService::count(),
                'messages_non_lus' => MessageContact::nonLu()->count(),
                'messages_total' => MessageContact::count(),
                'produits' => Produit::where('actif', true)->count(),
                'ruptures' => Produit::where('actif', true)->whereNotNull('stock')->where('stock', '<=', 0)->count(),
                'actualites' => Actualite::where('publie', true)->count(),
                'fermes' => Ferme::where('publie', true)->count(),
                'projets' => Projet::where('publie', true)->count(),
            ],
            'graphiques' => [
                'mois' => $mois->map(fn (Carbon $m) => ucfirst($m->locale('fr')->translatedFormat('M Y')))->values(),
                'ventes' => $parMois($valides, fn ($c) => $c->total),
                'commandes' => $parMois($valides),
                'demandes' => $parMois($demandes),
                'messages' => $parMois($messages),
                'statuts' => collect(Commande::STATUTS)->map(fn ($s, $k) => [
                    'libelle' => $s[0],
                    'couleur' => $s[1],
                    'total' => Commande::where('statut', $k)->count(),
                ])->values(),
                'paiements' => collect(config('boutique.paiements'))->map(fn ($p, $k) => [
                    'libelle' => $p['label'],
                    'total' => $valides->where('mode_paiement', $k)->count(),
                ])->values(),
                'services' => DemandeService::selectRaw('service, COUNT(*) as total')->groupBy('service')->orderByDesc('total')->get(),
                'demandes_statuts' => collect(DemandeService::STATUTS)->map(fn ($s, $k) => [
                    'libelle' => $s[0],
                    'couleur' => $s[1],
                    'total' => DemandeService::where('statut', $k)->count(),
                ])->values(),
                'top_produits' => $topProduits,
                'articles_lus' => Actualite::orderByDesc('vues')->limit(6)->get(['title', 'vues']),
            ],
            'dernieresCommandes' => Commande::latest()->limit(6)->get(),
            'dernieresDemandes' => DemandeService::latest()->limit(6)->get(),
        ]);
    }
}
