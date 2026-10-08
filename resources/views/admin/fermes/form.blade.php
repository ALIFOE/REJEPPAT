@extends('admin.layout')

@section('title', $ferme->exists ? 'Ferme école ' . $ferme->nom : 'Nouvelle ferme école')
@section('sous_titre', 'Les Fermes Écoles')

@section('actions')
    <a href="{{ route('admin.fermes.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Fermes écoles</a>
@endsection

@section('content')

    <form action="{{ $ferme->exists ? route('admin.fermes.update', $ferme) : route('admin.fermes.store') }}" method="post" enctype="multipart/form-data" class="a-carte">
        @csrf
        @if ($ferme->exists) @method('put') @endif

        <div class="a-carte__corps a-grille a-grille--large">
            <div class="a-form">
                @include('admin.partials.champ', ['nom' => 'nom', 'label' => 'Nom de la ferme école', 'valeur' => $ferme->nom, 'requis' => true])
                <div class="a-grille a-grille--2" style="gap: 14px">
                    @include('admin.partials.champ', ['nom' => 'specialite', 'label' => 'Spécialité', 'valeur' => $ferme->specialite, 'requis' => true, 'aide' => 'Ex. : Élevage & apiculture'])
                    @include('admin.partials.champ', ['nom' => 'localisation', 'label' => 'Localisation', 'valeur' => $ferme->localisation, 'aide' => 'Ex. : Kpété-Kpété, Région Centrale'])
                </div>
                @include('admin.partials.champ', ['nom' => 'modules', 'label' => 'Domaines de formation', 'type' => 'textarea', 'lignes' => 9, 'valeur' => implode("\n", $ferme->modules ?? []), 'requis' => true, 'aide' => 'Un module par ligne.'])

                <div class="a-carte" style="box-shadow: none">
                    <div class="a-carte__entete"><div><h3>Position sur la carte des fermes</h3><p>Facultatif : coordonnées GPS (ex. relevées sur Google Maps).</p></div></div>
                    <div class="a-carte__corps a-form">
                        <div class="a-grille a-grille--2" style="gap: 14px">
                            @include('admin.partials.champ', ['nom' => 'latitude', 'label' => 'Latitude', 'valeur' => $ferme->carte['lat'] ?? '', 'attributs' => 'inputmode="decimal" placeholder="8.50413"'])
                            @include('admin.partials.champ', ['nom' => 'longitude', 'label' => 'Longitude', 'valeur' => $ferme->carte['lng'] ?? '', 'attributs' => 'inputmode="decimal" placeholder="0.97139"'])
                        </div>
                        @include('admin.partials.champ', ['nom' => 'adresse', 'label' => 'Adresse affichée sur la carte', 'valeur' => $ferme->carte['adresse'] ?? '', 'attributs' => 'placeholder="Sise à …, canton de …, préfecture de …"'])
                    </div>
                </div>
            </div>
            <div class="a-form">
                @include('admin.partials.photo', ['modele' => $ferme, 'label' => 'Photo de la ferme'])
                <div class="a-grille a-grille--2" style="gap: 14px">
                    @include('admin.partials.champ', ['nom' => 'ordre', 'label' => 'Ordre d’affichage', 'type' => 'number', 'valeur' => $ferme->ordre, 'attributs' => 'min="0"'])
                    @include('admin.partials.champ', ['nom' => 'slug', 'label' => 'Adresse (slug)', 'valeur' => $ferme->slug])
                </div>
                @include('admin.partials.interrupteur', ['nom' => 'accueil', 'label' => 'Mettre en avant sur la page d’accueil', 'valeur' => $ferme->accueil, 'aide' => 'Les 4 premières fermes mises en avant sont affichées.'])
                @include('admin.partials.interrupteur', ['nom' => 'publie', 'label' => 'Publiée sur le site', 'valeur' => $ferme->publie])
            </div>
        </div>

        <div class="a-form__pied">
            <a href="{{ route('admin.fermes.index') }}" class="a-btn a-btn--clair">Annuler</a>
            <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </form>

@endsection
