@extends('admin.layout')

@section('title', $utilisateur->exists ? 'Modifier le compte' : 'Nouveau compte')
@section('sous_titre', 'Utilisateurs & rôles')

@php use App\Models\User; @endphp

@section('actions')
    <a href="{{ route('admin.utilisateurs.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Utilisateurs</a>
@endsection

@section('content')

    <form action="{{ $utilisateur->exists ? route('admin.utilisateurs.update', $utilisateur) : route('admin.utilisateurs.store') }}" method="post" class="a-carte" style="max-width: 720px">
        @csrf
        @if ($utilisateur->exists) @method('put') @endif

        <div class="a-carte__corps a-form">
            @include('admin.partials.champ', ['nom' => 'name', 'label' => 'Nom', 'valeur' => $utilisateur->name, 'requis' => true])
            <div class="a-grille a-grille--2" style="gap: 14px">
                @include('admin.partials.champ', ['nom' => 'email', 'label' => 'Adresse e-mail', 'type' => 'email', 'valeur' => $utilisateur->email, 'requis' => true, 'attributs' => 'autocomplete="off"'])
                @include('admin.partials.champ', ['nom' => 'telephone', 'label' => 'Téléphone', 'type' => 'tel', 'valeur' => $utilisateur->telephone])
            </div>
            @include('admin.partials.champ', [
                'nom' => 'role',
                'label' => 'Rôle',
                'type' => 'select',
                'options' => collect(User::ROLES)->map(fn ($role) => $role[0])->all(),
                'valeur' => $utilisateur->role,
                'requis' => true,
                'attributs' => $utilisateur->is(auth()->user()) ? 'disabled' : '',
                'aide' => $utilisateur->is(auth()->user())
                    ? 'Vous ne pouvez pas modifier votre propre rôle.'
                    : 'Super admin : tous les droits, dont les comptes. Administrateur : contenus et boutique. Client : espace « Mon compte » uniquement.',
            ])
            @if ($utilisateur->is(auth()->user()))
                <input type="hidden" name="role" value="{{ $utilisateur->role }}">
            @endif
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
