@extends('admin.layout')

@section('title', $partenaire->exists ? 'Partenaire ' . $partenaire->nom : 'Nouveau partenaire')
@section('sous_titre', 'Partenaires')

@php use App\Models\Partenaire; @endphp

@section('actions')
    <a href="{{ route('admin.partenaires.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Partenaires</a>
@endsection

@section('content')

    <form action="{{ $partenaire->exists ? route('admin.partenaires.update', $partenaire) : route('admin.partenaires.store') }}" method="post" enctype="multipart/form-data" class="a-carte">
        @csrf
        @if ($partenaire->exists) @method('put') @endif

        <div class="a-carte__corps a-grille a-grille--large">
            <div class="a-form">
                @include('admin.partials.champ', ['nom' => 'nom', 'label' => 'Nom du partenaire', 'valeur' => $partenaire->nom, 'requis' => true])
                @include('admin.partials.champ', ['nom' => 'type', 'label' => 'Type de partenaire', 'type' => 'select', 'valeur' => $partenaire->type, 'options' => Partenaire::TYPES, 'requis' => true])
                @include('admin.partials.champ', ['nom' => 'description', 'label' => 'Présentation du partenariat', 'type' => 'textarea', 'lignes' => 5, 'valeur' => $partenaire->description, 'aide' => 'Ex. : appui à la formation des jeunes dans les fermes écoles depuis 2019.'])
                @include('admin.partials.champ', ['nom' => 'site', 'label' => 'Site internet', 'type' => 'url', 'valeur' => $partenaire->site, 'attributs' => 'placeholder="https://"'])
            </div>
            <div class="a-form">
                @include('admin.partials.photo', ['modele' => $partenaire, 'label' => 'Logo'])
                @include('admin.partials.champ', ['nom' => 'ordre', 'label' => 'Ordre d’affichage', 'type' => 'number', 'valeur' => $partenaire->ordre, 'attributs' => 'min="0"'])
                @include('admin.partials.interrupteur', ['nom' => 'publie', 'label' => 'Affiché sur le site', 'valeur' => $partenaire->publie])
            </div>
        </div>

        @include('admin.partials.traduction', ['modele' => $partenaire])

        <div class="a-form__pied">
            <a href="{{ route('admin.partenaires.index') }}" class="a-btn a-btn--clair">Annuler</a>
            <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </form>

@endsection
