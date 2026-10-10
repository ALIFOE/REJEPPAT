@extends('admin.layout')

@section('title', $region->exists ? 'Région ' . $region->nom : 'Nouvelle section régionale')
@section('sous_titre', 'Sections régionales')

@section('actions')
    <a href="{{ route('admin.regions.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Sections régionales</a>
@endsection

@section('content')

    <form action="{{ $region->exists ? route('admin.regions.update', $region) : route('admin.regions.store') }}" method="post" enctype="multipart/form-data" class="a-carte">
        @csrf
        @if ($region->exists) @method('put') @endif

        <div class="a-carte__corps a-grille a-grille--large">
            <div class="a-form">
                <div class="a-grille a-grille--2" style="gap: 14px">
                    @include('admin.partials.champ', ['nom' => 'nom', 'label' => 'Nom de la région', 'valeur' => $region->nom, 'requis' => true, 'aide' => 'Ex. : Région Centrale'])
                    @include('admin.partials.champ', ['nom' => 'chef_lieu', 'label' => 'Chef-lieu / siège de la section', 'valeur' => $region->chef_lieu, 'aide' => 'Ex. : Sokodé'])
                </div>
                @include('admin.partials.champ', ['nom' => 'presentation', 'label' => 'Présentation de la section', 'type' => 'textarea', 'lignes' => 8, 'valeur' => implode("\n", $region->presentation ?? []), 'aide' => 'Un paragraphe par ligne : histoire, activités, réalisations… Tant que ce champ est vide, la page invite la région à transmettre ses informations.'])
                @include('admin.partials.champ', ['nom' => 'filieres', 'label' => 'Filières principales', 'type' => 'textarea', 'lignes' => 4, 'valeur' => implode("\n", $region->filieres ?? []), 'aide' => 'Une filière par ligne (ex. « Maraîchage », « Soja »).'])
                <div class="a-grille a-grille--2" style="gap: 14px">
                    @include('admin.partials.champ', ['nom' => 'nb_cooperatives', 'label' => 'Nombre de coopératives', 'type' => 'number', 'valeur' => $region->nb_cooperatives, 'attributs' => 'min="0"'])
                    @include('admin.partials.champ', ['nom' => 'nb_membres', 'label' => 'Nombre de producteurs membres', 'type' => 'number', 'valeur' => $region->nb_membres, 'attributs' => 'min="0"'])
                </div>
            </div>
            <div class="a-form">
                @include('admin.partials.photo', ['modele' => $region, 'label' => 'Photo de la région'])
                @include('admin.partials.champ', ['nom' => 'responsable', 'label' => 'Responsable de la section', 'valeur' => $region->responsable])
                <div class="a-grille a-grille--2" style="gap: 14px">
                    @include('admin.partials.champ', ['nom' => 'telephone', 'label' => 'Téléphone', 'type' => 'tel', 'valeur' => $region->telephone])
                    @include('admin.partials.champ', ['nom' => 'email', 'label' => 'E-mail', 'type' => 'email', 'valeur' => $region->email])
                </div>
                <div class="a-grille a-grille--2" style="gap: 14px">
                    @include('admin.partials.champ', ['nom' => 'ordre', 'label' => 'Ordre d’affichage', 'type' => 'number', 'valeur' => $region->ordre, 'attributs' => 'min="0"'])
                    @include('admin.partials.champ', ['nom' => 'slug', 'label' => 'Adresse (slug)', 'valeur' => $region->slug])
                </div>
                @include('admin.partials.interrupteur', ['nom' => 'publie', 'label' => 'Publiée sur le site', 'valeur' => $region->publie])
                <p class="a-champ__aide">Les fermes écoles se rattachent à une région depuis leur fiche ; les membres de l’annuaire choisissent leur région.</p>
            </div>
        </div>

        @include('admin.partials.traduction', ['modele' => $region])

        <div class="a-form__pied">
            <a href="{{ route('admin.regions.index') }}" class="a-btn a-btn--clair">Annuler</a>
            <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </form>

@endsection
