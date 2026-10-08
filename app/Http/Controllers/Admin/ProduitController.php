<?php

namespace App\Http\Controllers\Admin;

use App\Models\Produit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProduitController extends Controller
{
    public function index(Request $request): View
    {
        $produits = Produit::query()
            ->withSum(['lignes as vendus' => fn ($q) => $q->whereHas('commande', fn ($c) => $c->valide())], 'quantite')
            ->when($request->query('q'), fn ($q, $recherche) => $q->where('nom', 'like', "%{$recherche}%"))
            ->when($request->query('categorie'), fn ($q, $categorie) => $q->where('categorie', $categorie))
            ->when($request->query('stock') === 'rupture', fn ($q) => $q->whereNotNull('stock')->where('stock', '<=', 0))
            ->orderBy('ordre')->orderBy('id')
            ->paginate(self::PAR_PAGE)->withQueryString();

        $tous = Produit::withSum(['lignes as vendus' => fn ($q) => $q->whereHas('commande', fn ($c) => $c->valide())], 'quantite')
            ->withSum(['lignes as recette' => fn ($q) => $q->whereHas('commande', fn ($c) => $c->valide())], 'total')
            ->orderBy('ordre')->get();

        return view('admin.produits.index', compact('produits', 'tous'));
    }

    public function create(): View
    {
        return view('admin.produits.form', ['produit' => new Produit([
            'categorie' => array_key_first(config('boutique.categories')),
            'description' => [],
            'points_forts' => [],
            'ordre' => Produit::max('ordre') + 1,
            'actif' => true,
        ])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->enregistrer($request, new Produit());

        return redirect()->route('admin.produits.index')->with('succes', 'Produit ajouté à la boutique.');
    }

    public function edit(Produit $produit): View
    {
        return view('admin.produits.form', compact('produit'));
    }

    public function update(Request $request, Produit $produit): RedirectResponse
    {
        $this->enregistrer($request, $produit);

        return redirect()->route('admin.produits.index')->with('succes', 'Produit mis à jour.');
    }

    public function destroy(Produit $produit): RedirectResponse
    {
        // Les lignes de commande gardent le nom et le prix du produit (produit_id passe à null)
        $this->supprimerPhoto($produit->photo);
        $produit->delete();

        return redirect()->route('admin.produits.index')->with('succes', 'Produit supprimé.');
    }

    private function enregistrer(Request $request, Produit $produit): void
    {
        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:160'],
            'categorie' => ['required', Rule::in(array_keys(config('boutique.categories')))],
            'prix' => ['required', 'integer', 'min:0'],
            'prix_initial' => ['nullable', 'integer', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'resume' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'points_forts' => ['nullable', 'string'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'photo' => self::REGLE_IMAGE,
        ], self::MESSAGES);

        $produit->fill([
            'nom' => $donnees['nom'],
            'slug' => $this->slug(Produit::class, $donnees['slug'] ?? null, $donnees['nom'], $produit->exists ? $produit : null),
            'categorie' => $donnees['categorie'],
            'prix' => $donnees['prix'],
            'prix_initial' => $donnees['prix_initial'] ?? null,
            'stock' => $donnees['stock'] ?? null,
            'resume' => $donnees['resume'],
            'description' => $this->lignes($donnees['description']),
            'points_forts' => $this->lignes($donnees['points_forts'] ?? ''),
            'ordre' => (int) ($donnees['ordre'] ?? 0),
            'photo' => $this->photoDemandee($request, $produit, 'produits'),
            'actif' => $request->boolean('actif'),
        ])->save();
    }
}
