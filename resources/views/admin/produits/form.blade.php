@extends('admin.layout')

@section('title', $produit->exists ? 'Modifier « ' . $produit->nom . ' »' : 'Nouveau produit')
@section('sous_titre', 'Boutique en ligne')

@section('actions')
    <a href="{{ route('admin.produits.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Produits</a>
@endsection

@section('content')

    <form action="{{ $produit->exists ? route('admin.produits.update', $produit) : route('admin.produits.store') }}" method="post" enctype="multipart/form-data" class="a-carte">
        @csrf
        @if ($produit->exists) @method('put') @endif

        <div class="a-carte__corps a-grille a-grille--large">
            <div class="a-form">
                @include('admin.partials.champ', ['nom' => 'nom', 'label' => 'Nom du produit', 'valeur' => $produit->nom, 'requis' => true])
                @include('admin.partials.champ', ['nom' => 'resume', 'label' => 'Résumé', 'type' => 'textarea', 'lignes' => 3, 'valeur' => $produit->resume, 'requis' => true, 'aide' => 'Affiché sous le prix sur la fiche produit.'])
                @include('admin.partials.champ', ['nom' => 'description', 'label' => 'Description', 'type' => 'textarea', 'lignes' => 7, 'valeur' => implode("\n", $produit->description ?? []), 'requis' => true, 'aide' => 'Un paragraphe par ligne.'])
                @include('admin.partials.champ', ['nom' => 'points_forts', 'label' => 'Points forts', 'type' => 'textarea', 'lignes' => 5, 'valeur' => implode("\n", $produit->points_forts ?? []), 'aide' => 'Un point fort par ligne.'])
            </div>
            <div class="a-form">
                @include('admin.partials.photo', ['modele' => $produit, 'label' => 'Photo du produit'])
                @include('admin.partials.champ', ['nom' => 'categorie', 'label' => 'Catégorie', 'type' => 'select', 'valeur' => $produit->categorie, 'options' => config('boutique.categories'), 'requis' => true])
                <div class="a-grille a-grille--2" style="gap: 14px">
                    @include('admin.partials.champ', ['nom' => 'prix', 'label' => 'Prix (CFA)', 'type' => 'number', 'valeur' => $produit->prix, 'requis' => true, 'attributs' => 'min="0" step="1"'])
                    @include('admin.partials.champ', ['nom' => 'prix_initial', 'label' => 'Prix barré (CFA)', 'type' => 'number', 'valeur' => $produit->prix_initial, 'attributs' => 'min="0" step="1"', 'aide' => 'Facultatif, pour afficher une réduction.'])
                </div>
                @include('admin.partials.champ', ['nom' => 'stock', 'label' => 'Stock disponible', 'type' => 'number', 'valeur' => $produit->stock, 'attributs' => 'min="0" step="1"', 'aide' => 'Laisser vide pour ne pas suivre le stock. Les commandes le diminuent automatiquement.'])
                <div class="a-grille a-grille--2" style="gap: 14px">
                    @include('admin.partials.champ', ['nom' => 'ordre', 'label' => 'Ordre d’affichage', 'type' => 'number', 'valeur' => $produit->ordre, 'attributs' => 'min="0"'])
                    @include('admin.partials.champ', ['nom' => 'slug', 'label' => 'Adresse (slug)', 'valeur' => $produit->slug, 'aide' => 'Générée à partir du nom si vide.'])
                </div>
                @include('admin.partials.interrupteur', ['nom' => 'actif', 'label' => 'En vente sur la boutique', 'valeur' => $produit->actif])
            </div>
        </div>

        <div class="a-form__pied">
            <a href="{{ route('admin.produits.index') }}" class="a-btn a-btn--clair">Annuler</a>
            <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </form>

@endsection
