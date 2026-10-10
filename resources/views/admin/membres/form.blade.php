@extends('admin.layout')

@section('title', $membre->exists ? 'Profil de ' . $membre->nom : 'Nouveau membre')
@section('sous_titre', 'Annuaire des membres')

@php use App\Models\Membre; @endphp

@section('actions')
    <a href="{{ route('admin.membres.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Annuaire</a>
@endsection

@section('content')

    <form action="{{ $membre->exists ? route('admin.membres.update', $membre) : route('admin.membres.store') }}" method="post" enctype="multipart/form-data" class="a-carte">
        @csrf
        @if ($membre->exists) @method('put') @endif

        <div class="a-carte__corps a-grille a-grille--large">
            <div class="a-form">
                <div class="a-grille a-grille--2" style="gap: 14px">
                    @include('admin.partials.champ', ['nom' => 'nom', 'label' => 'Nom complet', 'valeur' => $membre->nom, 'requis' => true])
                    @include('admin.partials.champ', ['nom' => 'organisation', 'label' => 'Coopérative / organisation', 'valeur' => $membre->organisation])
                </div>
                <div class="a-grille a-grille--2" style="gap: 14px">
                    @include('admin.partials.champ', ['nom' => 'filiere', 'label' => 'Filière', 'type' => 'select', 'valeur' => $membre->filiere, 'options' => Membre::FILIERES, 'requis' => true])
                    @include('admin.partials.champ', ['nom' => 'localite', 'label' => 'Localité', 'valeur' => $membre->localite])
                </div>
                @include('admin.partials.champ', ['nom' => 'presentation', 'label' => 'Présentation', 'type' => 'textarea', 'lignes' => 7, 'valeur' => implode("\n", $membre->presentation ?? []), 'requis' => true, 'aide' => 'Un paragraphe par ligne : activité, productions, parcours, projets…'])
                <div class="a-grille a-grille--2" style="gap: 14px">
                    @include('admin.partials.champ', ['nom' => 'telephone', 'label' => 'Téléphone / WhatsApp', 'type' => 'tel', 'valeur' => $membre->telephone, 'aide' => 'Affiché sur le profil public.'])
                    @include('admin.partials.champ', ['nom' => 'email', 'label' => 'E-mail', 'type' => 'email', 'valeur' => $membre->email, 'aide' => 'Affiché sur le profil public.'])
                </div>
            </div>
            <div class="a-form">
                @include('admin.partials.photo', ['modele' => $membre, 'label' => 'Photo du membre'])
                @include('admin.partials.champ', ['nom' => 'region_id', 'label' => 'Section régionale', 'type' => 'select', 'valeur' => $membre->region_id, 'options' => ['' => '– Non précisée –'] + \App\Models\Region::orderBy('ordre')->pluck('nom', 'id')->all()])
                @include('admin.partials.champ', ['nom' => 'slug', 'label' => 'Adresse (slug)', 'valeur' => $membre->slug])
                @include('admin.partials.interrupteur', ['nom' => 'publie', 'label' => 'Publié dans l’annuaire', 'valeur' => $membre->publie, 'aide' => 'Les profils proposés depuis le site restent masqués jusqu’à leur validation.'])
            </div>
        </div>

        @include('admin.partials.traduction', ['modele' => $membre])

        <div class="a-form__pied">
            <a href="{{ route('admin.membres.index') }}" class="a-btn a-btn--clair">Annuler</a>
            <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </form>

@endsection
