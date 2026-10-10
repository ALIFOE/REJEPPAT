@extends('admin.layout')

@section('title', $temoignage->exists ? 'Témoignage de ' . $temoignage->nom : 'Nouveau témoignage')
@section('sous_titre', 'Témoignages & success stories')

@php use App\Models\Temoignage; @endphp

@section('actions')
    <a href="{{ route('admin.temoignages.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Témoignages</a>
@endsection

@section('content')

    <form action="{{ $temoignage->exists ? route('admin.temoignages.update', $temoignage) : route('admin.temoignages.store') }}" method="post" enctype="multipart/form-data" class="a-carte">
        @csrf
        @if ($temoignage->exists) @method('put') @endif

        <div class="a-carte__corps a-grille a-grille--large">
            <div class="a-form">
                @include('admin.partials.champ', ['nom' => 'type', 'label' => 'Type', 'type' => 'select', 'valeur' => $temoignage->type, 'options' => Temoignage::TYPES, 'requis' => true, 'aide' => 'Success story : récit plus complet avec un titre. Apprenant : affiché sur la fiche de sa ferme école.'])
                <div class="a-grille a-grille--2" style="gap: 14px">
                    @include('admin.partials.champ', ['nom' => 'nom', 'label' => 'Nom', 'valeur' => $temoignage->nom, 'requis' => true])
                    @include('admin.partials.champ', ['nom' => 'role', 'label' => 'Rôle / activité', 'valeur' => $temoignage->role, 'aide' => 'Ex. : Productrice maraîchère à Blitta'])
                </div>
                @include('admin.partials.champ', ['nom' => 'titre', 'label' => 'Titre', 'valeur' => $temoignage->titre, 'aide' => 'Obligatoire pour une success story. Ex. : « De la formation à ma propre exploitation »'])
                @include('admin.partials.champ', ['nom' => 'texte', 'label' => 'Témoignage', 'type' => 'textarea', 'lignes' => 8, 'valeur' => $temoignage->texte, 'requis' => true, 'aide' => 'Pour une success story, sautez une ligne entre les paragraphes.'])
                @include('admin.partials.champ', ['nom' => 'ferme_id', 'label' => 'Ferme école (apprenant)', 'type' => 'select', 'valeur' => $temoignage->ferme_id, 'options' => ['' => '– Aucune –'] + \App\Models\Ferme::orderBy('ordre')->pluck('nom', 'id')->all()])
            </div>
            <div class="a-form">
                @include('admin.partials.photo', ['modele' => $temoignage, 'label' => 'Photo'])
                @include('admin.partials.champ', ['nom' => 'ordre', 'label' => 'Ordre d’affichage', 'type' => 'number', 'valeur' => $temoignage->ordre, 'attributs' => 'min="0"'])
                @include('admin.partials.interrupteur', ['nom' => 'accueil', 'label' => 'Afficher sur la page d’accueil', 'valeur' => $temoignage->accueil, 'aide' => 'Les 3 premiers témoignages mis en avant sont affichés.'])
                @include('admin.partials.interrupteur', ['nom' => 'publie', 'label' => 'Publié sur le site', 'valeur' => $temoignage->publie])
            </div>
        </div>

        @include('admin.partials.traduction', ['modele' => $temoignage])

        <div class="a-form__pied">
            <a href="{{ route('admin.temoignages.index') }}" class="a-btn a-btn--clair">Annuler</a>
            <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </form>

@endsection
