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
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Un tableau de bord par rôle :
 * - super admin : supervision (chiffre d'affaires, comptes, équipe, clients) ;
 * - administrateur : activité au quotidien (éléments à traiter, ventes, demandes, contenus).
 */
class TableauDeBordController extends Controller
{
    private const MOIS = 12;

    public function index(Request $request): View
    {
        return $request->user()->estSuperAdmin() ? $this->superAdmin() : $this->administrateur();
    }

    private function superAdmin(): View
    {
        $mois = $this->douzeMois();
        $ceMois = now()->startOfMonth();
        $valides = Commande::valide()->where('created_at', '>=', $mois->first())->get(['total', 'created_at']);
        $inscriptions = User::where('role', User::CLIENT)->where('created_at', '>=', $mois->first())->get(['created_at']);

        return view('admin.tableaux.super-admin', [
            'chiffres' => [
                'ca_total' => Commande::valide()->sum('total'),
                'ca_mois' => Commande::valide()->where('created_at', '>=', $ceMois)->sum('total'),
                'clients' => User::where('role', User::CLIENT)->count(),
                'clients_mois' => User::where('role', User::CLIENT)->where('created_at', '>=', $ceMois)->count(),
                'equipe' => User::whereIn('role', [User::SUPER_ADMIN, User::ADMIN])->count(),
                'a_traiter' => Commande::where('statut', 'en_attente')->count()
                    + DemandeService::where('statut', 'nouvelle')->count()
                    + MessageContact::nonLu()->count(),
                'commandes_avec_compte' => Commande::whereNotNull('user_id')->count(),
                'commandes_sans_compte' => Commande::whereNull('user_id')->count(),
            ] + $this->chiffresContenus(),
            'graphiques' => [
                'mois' => $this->libellesMois($mois),
                'ventes' => $this->parMois($mois, $valides, fn ($c) => $c->total),
                'commandes' => $this->parMois($mois, $valides),
                'inscriptions' => $this->parMois($mois, $inscriptions),
                'roles' => collect(User::ROLES)->map(fn ($role, $cle) => [
                    'libelle' => $role[0],
                    'couleur' => $role[1],
                    'total' => User::where('role', $cle)->count(),
                ])->values(),
            ],
            'derniersClients' => User::where('role', User::CLIENT)->withCount('commandes')->latest()->limit(6)->get(),
            'equipe' => User::whereIn('role', [User::SUPER_ADMIN, User::ADMIN])->orderBy('role', 'desc')->orderBy('name')->get(),
            'meilleursClients' => User::where('role', User::CLIENT)
                ->whereHas('commandes', fn ($q) => $q->valide())
                ->withSum(['commandes' => fn ($q) => $q->valide()], 'total')
                ->withCount('commandes')
                ->orderByDesc('commandes_sum_total')
                ->limit(6)
                ->get(),
        ]);
    }

    private function administrateur(): View
    {
        $mois = $this->douzeMois();
        $debut = $mois->first();

        $commandes = Commande::where('created_at', '>=', $debut)->get(['statut', 'total', 'mode_paiement', 'created_at']);
        $valides = $commandes->where('statut', '!=', 'annulee');
        $demandes = DemandeService::where('created_at', '>=', $debut)->get(['service', 'created_at']);
        $messages = MessageContact::where('created_at', '>=', $debut)->get(['created_at']);

        $topProduits = CommandeLigne::query()
            ->whereHas('commande', fn ($q) => $q->valide())
            ->selectRaw('nom_produit, SUM(quantite) as quantite, SUM(total) as montant')
            ->groupBy('nom_produit')
            ->orderByDesc('quantite')
            ->limit(6)
            ->get();

        $ceMois = now()->startOfMonth();

        return view('admin.tableaux.administrateur', [
            'chiffres' => [
                'ca_mois' => Commande::valide()->where('created_at', '>=', $ceMois)->sum('total'),
                'commandes_mois' => Commande::where('created_at', '>=', $ceMois)->count(),
                'commandes_attente' => Commande::where('statut', 'en_attente')->count(),
                'demandes_nouvelles' => DemandeService::where('statut', 'nouvelle')->count(),
                'messages_non_lus' => MessageContact::nonLu()->count(),
            ] + $this->chiffresContenus(),
            'graphiques' => [
                'mois' => $this->libellesMois($mois),
                'ventes' => $this->parMois($mois, $valides, fn ($c) => $c->total),
                'commandes' => $this->parMois($mois, $valides),
                'demandes' => $this->parMois($mois, $demandes),
                'messages' => $this->parMois($mois, $messages),
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
                'top_produits' => $topProduits,
                'articles_lus' => Actualite::orderByDesc('vues')->limit(6)->get(['title', 'vues']),
            ],
            // Files d'attente : les plus anciennes d'abord
            'commandesEnAttente' => Commande::where('statut', 'en_attente')->oldest()->limit(6)->get(),
            'demandesNouvelles' => DemandeService::where('statut', 'nouvelle')->oldest()->limit(6)->get(),
            'messagesNonLus' => MessageContact::nonLu()->oldest()->limit(6)->get(),
            'ruptures' => Produit::where('actif', true)->whereNotNull('stock')->where('stock', '<=', 5)->orderBy('stock')->limit(6)->get(),
        ]);
    }

    /** Chiffres des contenus du site, communs aux deux tableaux de bord. */
    private function chiffresContenus(): array
    {
        return [
            'produits' => Produit::where('actif', true)->count(),
            'ruptures' => Produit::where('actif', true)->whereNotNull('stock')->where('stock', '<=', 0)->count(),
            'actualites' => Actualite::where('publie', true)->count(),
            'fermes' => Ferme::where('publie', true)->count(),
            'projets' => Projet::where('publie', true)->count(),
        ];
    }

    /** @return Collection<int, Carbon> premier jour de chacun des 12 derniers mois */
    private function douzeMois(): Collection
    {
        $debut = now()->startOfMonth()->subMonths(self::MOIS - 1);

        return collect(range(0, self::MOIS - 1))->map(fn ($i) => $debut->copy()->addMonths($i));
    }

    private function libellesMois(Collection $mois): Collection
    {
        return $mois->map(fn (Carbon $m) => ucfirst($m->locale('fr')->translatedFormat('M Y')))->values();
    }

    /** Nombre (ou somme de $valeur) des éléments créés chaque mois. */
    private function parMois(Collection $mois, Collection $liste, ?callable $valeur = null): Collection
    {
        return $mois->map(fn (Carbon $m) => $liste
            ->filter(fn ($e) => $e->created_at->format('Y-m') === $m->format('Y-m'))
            ->sum($valeur ?? fn () => 1)
        )->values();
    }
}
