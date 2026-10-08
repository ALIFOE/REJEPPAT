@extends('admin.layout')

@section('title', $offre->exists ? 'Modifier l’offre' : 'Nouvelle offre')
@section('sous_titre', 'Nos offres & services')

@section('actions')
    <a href="{{ route('admin.offres.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Offres</a>
@endsection

@section('content')

    <form action="{{ $offre->exists ? route('admin.offres.update', $offre) : route('admin.offres.store') }}" method="post" class="a-carte" style="max-width: 820px">
        @csrf
        @if ($offre->exists) @method('put') @endif

        <div class="a-carte__corps a-form">
            @include('admin.partials.champ', ['nom' => 'titre', 'label' => 'Titre de l’offre', 'valeur' => $offre->titre, 'requis' => true, 'aide' => 'Apparaît aussi dans la liste « Service souhaité » du formulaire de demande.'])
            @include('admin.partials.champ', ['nom' => 'texte', 'label' => 'Description', 'type' => 'textarea', 'lignes' => 5, 'valeur' => $offre->texte, 'requis' => true])
            <div class="a-grille a-grille--2" style="gap: 14px">
                @include('admin.partials.champ', ['nom' => 'ordre', 'label' => 'Ordre d’affichage', 'type' => 'number', 'valeur' => $offre->ordre, 'attributs' => 'min="0"'])
                @include('admin.partials.interrupteur', ['nom' => 'actif', 'label' => 'Affichée sur le site', 'valeur' => $offre->actif])
            </div>
        </div>

        <div class="a-form__pied">
            <a href="{{ route('admin.offres.index') }}" class="a-btn a-btn--clair">Annuler</a>
            <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </form>

@endsection
