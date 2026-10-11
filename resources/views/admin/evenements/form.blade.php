@extends('admin.layout')

@section('title', $evenement->exists ? 'Modifier l’événement' : 'Nouvel événement')
@section('sous_titre', 'Agenda')

@php use App\Models\Evenement; @endphp

@section('actions')
    <a href="{{ route('admin.evenements.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Agenda</a>
@endsection

@section('content')

    <form action="{{ $evenement->exists ? route('admin.evenements.update', $evenement) : route('admin.evenements.store') }}" method="post" enctype="multipart/form-data" class="a-carte">
        @csrf
        @if ($evenement->exists) @method('put') @endif

        <div class="a-carte__corps a-grille a-grille--large">
            <div class="a-form">
                @include('admin.partials.champ', ['nom' => 'titre', 'label' => 'Titre', 'valeur' => $evenement->titre, 'requis' => true, 'aide' => 'Ex. : Foire agroécologique de Sokodé'])
                <div class="a-grille a-grille--2" style="gap: 14px">
                    @include('admin.partials.champ', ['nom' => 'debut', 'label' => 'Début', 'type' => 'datetime-local', 'valeur' => optional($evenement->debut)->format('Y-m-d\TH:i'), 'requis' => true])
                    @include('admin.partials.champ', ['nom' => 'fin', 'label' => 'Fin', 'type' => 'datetime-local', 'valeur' => optional($evenement->fin)->format('Y-m-d\TH:i'), 'aide' => 'Facultatif.'])
                </div>
                @include('admin.partials.champ', ['nom' => 'lieu', 'label' => 'Lieu', 'valeur' => $evenement->lieu, 'requis' => true, 'aide' => 'Ex. : Siège du REJEPPAT, Sokodé'])
                @include('admin.partials.champ', ['nom' => 'description', 'label' => 'Description', 'type' => 'textarea', 'lignes' => 8, 'valeur' => implode("\n", $evenement->description ?? []), 'requis' => true, 'aide' => 'Un paragraphe par ligne : programme, public, conditions de participation…'])
                @include('admin.partials.champ', ['nom' => 'lien_inscription', 'label' => 'Lien d’inscription', 'type' => 'url', 'valeur' => $evenement->lien_inscription, 'attributs' => 'placeholder="https://"', 'aide' => 'Facultatif (formulaire en ligne, page de l’événement…).'])
            </div>
            <div class="a-form">
                @include('admin.partials.photo', ['modele' => $evenement, 'label' => 'Visuel de l’événement'])
                @include('admin.partials.champ', ['nom' => 'type', 'label' => 'Type', 'type' => 'select', 'valeur' => $evenement->type, 'options' => Evenement::TYPES, 'requis' => true])
                @include('admin.partials.champ', ['nom' => 'region_id', 'label' => 'Section régionale', 'type' => 'select', 'valeur' => $evenement->region_id, 'options' => ['' => '– Activité nationale –'] + \App\Models\Region::orderBy('ordre')->pluck('nom', 'id')->all(), 'aide' => 'L’activité apparaît aussi dans la section régionale, sur la page d’accueil.'])
                @include('admin.partials.champ', ['nom' => 'slug', 'label' => 'Adresse (slug)', 'valeur' => $evenement->slug])
                @include('admin.partials.interrupteur', ['nom' => 'publie', 'label' => 'Publié dans l’agenda', 'valeur' => $evenement->publie])
            </div>
        </div>

        @include('admin.partials.traduction', ['modele' => $evenement])

        <div class="a-form__pied">
            <a href="{{ route('admin.evenements.index') }}" class="a-btn a-btn--clair">Annuler</a>
            <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </form>

@endsection
