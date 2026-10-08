@extends('admin.layout')

@section('title', 'Produits de la boutique')
@section('sous_titre', 'Catalogue, prix et stocks')

@php
    use App\Support\Contenu;
    use Illuminate\Support\Str;

    $suivis = $tous->whereNotNull('stock');
@endphp

@section('actions')
    <a href="{{ route('admin.produits.create') }}" class="a-btn"><i class="fas fa-plus"></i> Nouveau produit</a>
@endsection

@section('content')

    <div class="a-grille a-grille--2 a-section">
        <div class="a-carte">
            <div class="a-carte__entete"><div><h2>Recettes par produit</h2><p>Montant des ventes, hors commandes annulées</p></div></div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'bar',
                    'labels' => $tous->pluck('nom')->map(fn ($n) => Str::limit($n, 22)),
                    'series' => [['label' => 'Recettes', 'data' => $tous->pluck('recette')->map(fn ($v) => (int) $v), 'couleur' => 'vert']],
                    'monnaie' => true,
                    'hauteur' => 240,
                    'vide_texte' => 'Les ventes par produit apparaîtront dès les premières commandes.',
                ])
            </div>
        </div>
        <div class="a-carte">
            <div class="a-carte__entete"><div><h2>État des stocks</h2><p>Produits dont le stock est suivi</p></div></div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'bar',
                    'labels' => $suivis->pluck('nom')->map(fn ($n) => Str::limit($n, 22)),
                    'series' => [['label' => 'En stock', 'data' => $suivis->pluck('stock')]],
                    'couleurs' => $suivis->map(fn ($p) => $p->stock <= 0 ? 'rouge' : ($p->stock < 10 ? 'jaune' : 'vert'))->values(),
                    'hauteur' => 240,
                    'vide_texte' => 'Indiquez un stock sur vos produits pour suivre leur disponibilité.',
                ])
            </div>
        </div>
    </div>

    <div class="a-carte">
        <form class="a-filtres" method="get">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher un produit…">
            <select name="categorie" onchange="this.form.submit()">
                <option value="">Toutes les catégories</option>
                @foreach (config('boutique.categories') as $cle => $nom)
                    <option value="{{ $cle }}" @selected(request('categorie') === $cle)>{{ $nom }}</option>
                @endforeach
            </select>
            <select name="stock" onchange="this.form.submit()">
                <option value="">Tous les stocks</option>
                <option value="rupture" @selected(request('stock') === 'rupture')>En rupture</option>
            </select>
            <button type="submit" class="a-btn a-btn--vert"><i class="fas fa-search"></i> Rechercher</button>
        </form>

        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Produit</th><th>Catégorie</th><th class="num">Prix</th><th class="num">Stock</th><th class="num">Vendus</th><th>En vente</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($produits as $produit)
                        <tr>
                            <td>
                                <div class="a-table__media">
                                    <img src="{{ $produit->visuel() }}" alt="">
                                    <span><strong>{{ $produit->nom }}</strong><small>{{ Str::limit($produit->resume, 60) }}</small></span>
                                </div>
                            </td>
                            <td>{{ $produit->categorieNom() }}</td>
                            <td class="num">
                                <strong>{{ Contenu::prix($produit->prix) }}</strong>
                                @if ($produit->reduction())<br><small><del>{{ Contenu::prix($produit->prix_initial) }}</del></small>@endif
                            </td>
                            <td class="num">
                                @if ($produit->stock === null)
                                    <small>Non suivi</small>
                                @elseif ($produit->stock <= 0)
                                    <span class="a-badge a-badge--rouge">Rupture</span>
                                @else
                                    {{ $produit->stock }}
                                @endif
                            </td>
                            <td class="num">{{ (int) $produit->vendus }}</td>
                            <td>
                                <span class="a-badge a-badge--{{ $produit->actif ? 'vert' : 'gris' }}">{{ $produit->actif ? 'Oui' : 'Masqué' }}</span>
                            </td>
                            <td>
                                @include('admin.partials.actions', [
                                    'voir' => $produit->actif ? route('boutique.show', $produit->slug) : null,
                                    'modifier' => route('admin.produits.edit', $produit),
                                    'supprimer' => route('admin.produits.destroy', $produit),
                                    'confirmation' => 'Supprimer « ' . $produit->nom . ' » ? Les commandes passées sont conservées.',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="a-vide">Aucun produit. <a href="{{ route('admin.produits.create') }}">Ajouter le premier produit</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="a-pagination">{{ $produits->links() }}</div>
    </div>

@endsection
