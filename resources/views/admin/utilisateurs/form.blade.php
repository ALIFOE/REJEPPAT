@extends('admin.layout')

@section('title', $utilisateur->exists ? 'Modifier le compte' : 'Nouveau compte administrateur')
@section('sous_titre', 'Administrateurs')

@section('actions')
    <a href="{{ route('admin.utilisateurs.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Administrateurs</a>
@endsection

@section('content')

    <form action="{{ $utilisateur->exists ? route('admin.utilisateurs.update', $utilisateur) : route('admin.utilisateurs.store') }}" method="post" class="a-carte" style="max-width: 720px">
        @csrf
        @if ($utilisateur->exists) @method('put') @endif

        <div class="a-carte__corps a-form">
            @include('admin.partials.champ', ['nom' => 'name', 'label' => 'Nom', 'valeur' => $utilisateur->name, 'requis' => true])
            @include('admin.partials.champ', ['nom' => 'email', 'label' => 'Adresse e-mail', 'type' => 'email', 'valeur' => $utilisateur->email, 'requis' => true, 'attributs' => 'autocomplete="off"'])
            <div class="a-grille a-grille--2" style="gap: 14px">
                @include('admin.partials.champ', ['nom' => 'password', 'label' => 'Mot de passe', 'type' => 'password', 'valeur' => '', 'requis' => ! $utilisateur->exists, 'attributs' => 'autocomplete="new-password"', 'aide' => $utilisateur->exists ? 'Laisser vide pour ne pas le changer.' : '8 caractères minimum.'])
                @include('admin.partials.champ', ['nom' => 'password_confirmation', 'label' => 'Confirmation', 'type' => 'password', 'valeur' => '', 'requis' => ! $utilisateur->exists, 'attributs' => 'autocomplete="new-password"'])
            </div>
        </div>

        <div class="a-form__pied">
            <a href="{{ route('admin.utilisateurs.index') }}" class="a-btn a-btn--clair">Annuler</a>
            <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </form>

@endsection
