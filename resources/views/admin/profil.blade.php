@extends('admin.layout')

@section('title', 'Mon profil')
@section('sous_titre', 'Vos informations, votre rôle et votre mot de passe')

@php use App\Models\User; @endphp

@section('content')

    <div class="a-grille a-grille--large">
        <div>
            <form action="{{ route('admin.profil.update') }}" method="post" class="a-carte a-section">
                @csrf
                @method('put')
                <div class="a-carte__entete"><div><h2>Mes informations</h2></div></div>
                <div class="a-carte__corps a-form">
                    @include('admin.partials.champ', ['nom' => 'name', 'label' => 'Nom', 'valeur' => $utilisateur->name, 'requis' => true, 'attributs' => 'autocomplete="name"'])
                    <div class="a-grille a-grille--2" style="gap: 14px">
                        @include('admin.partials.champ', ['nom' => 'email', 'label' => 'Adresse e-mail', 'type' => 'email', 'valeur' => $utilisateur->email, 'requis' => true, 'attributs' => 'autocomplete="email"'])
                        @include('admin.partials.champ', ['nom' => 'telephone', 'label' => 'Téléphone', 'type' => 'tel', 'valeur' => $utilisateur->telephone, 'attributs' => 'autocomplete="tel"'])
                    </div>
                </div>
                <div class="a-form__pied">
                    <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
                </div>
            </form>

            <form action="{{ route('admin.profil.mot-de-passe') }}" method="post" class="a-carte">
                @csrf
                @method('put')
                <div class="a-carte__entete"><div><h2>Changer mon mot de passe</h2></div></div>
                <div class="a-carte__corps a-form">
                    @include('admin.partials.champ', ['nom' => 'current_password', 'label' => 'Mot de passe actuel', 'type' => 'password', 'valeur' => '', 'requis' => true, 'attributs' => 'autocomplete="current-password"'])
                    <div class="a-grille a-grille--2" style="gap: 14px">
                        @include('admin.partials.champ', ['nom' => 'password', 'label' => 'Nouveau mot de passe', 'type' => 'password', 'valeur' => '', 'requis' => true, 'aide' => '8 caractères minimum.', 'attributs' => 'autocomplete="new-password"'])
                        @include('admin.partials.champ', ['nom' => 'password_confirmation', 'label' => 'Confirmation', 'type' => 'password', 'valeur' => '', 'requis' => true, 'attributs' => 'autocomplete="new-password"'])
                    </div>
                </div>
                <div class="a-form__pied">
                    <button type="submit" class="a-btn"><i class="fas fa-key"></i> Modifier le mot de passe</button>
                </div>
            </form>
        </div>

        <div>
            <div class="a-carte a-section">
                <div class="a-carte__corps a-profil">
                    <span class="a-utilisateur__avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($utilisateur->name, 0, 1)) }}</span>
                    <h2>{{ $utilisateur->name }}</h2>
                    @include('admin.partials.badge', ['statuts' => User::ROLES, 'valeur' => $utilisateur->role])
                    <p>Membre depuis le {{ $utilisateur->created_at?->locale('fr')->translatedFormat('j F Y') }}</p>
                </div>
            </div>

            <div class="a-carte">
                <div class="a-carte__entete"><div><h2>Mes droits</h2><p>Ce que votre rôle « {{ $utilisateur->roleLibelle() }} » vous permet</p></div></div>
                <div class="a-carte__corps">
                    <ul class="a-droits">
                        @foreach (User::DROITS[$utilisateur->role] as $droit)
                            <li><i class="fas fa-check-circle"></i> {{ $droit }}</li>
                        @endforeach
                        @unless ($utilisateur->estSuperAdmin())
                            <li><i class="fas fa-lock" style="color: var(--a-vert-doux)"></i> Les comptes et les rôles sont gérés par un super admin.</li>
                        @endunless
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
