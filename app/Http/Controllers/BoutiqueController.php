<?php

namespace App\Http\Controllers;

use App\Support\Contenu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BoutiqueController extends Controller
{
    public function index(Request $request): View
    {
        $recherche = trim((string) $request->query('q'));
        $categorie = $request->query('categorie');

        $produits = Contenu::produits()
            ->when($categorie, fn ($liste) => $liste->where('categorie', $categorie))
            ->when($recherche !== '', fn ($liste) => $liste->filter(
                fn ($p) => Str::contains(Str::ascii($p['nom']), Str::ascii($recherche), ignoreCase: true)
            ))
            ->values();

        return view('boutique.index', [
            'produits' => $produits,
            'tous' => Contenu::produits(),
            'recherche' => $recherche,
            'categorie' => $categorie,
        ]);
    }

    public function show(string $slug): View
    {
        $produit = Contenu::produit($slug);

        return view('boutique.show', [
            'produit' => $produit,
            'similaires' => Contenu::produits()->reject(fn ($p) => $p['slug'] === $slug)->values(),
        ]);
    }
}
