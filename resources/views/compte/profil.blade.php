@extends('layouts.app')

@section('title', __('Mon profil'))

@php $user = auth()->user(); @endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Mon profil'),
            'texte' => __('Vos informations personnelles et votre mot de passe.'),
            'image' => 'fermes.jpg',
            'liens' => [__('Mon compte') => route('compte.index')],
        ])

        <section class="compte-section">
            <div class="container">
                <div class="compte-espace">
                    @include('compte._menu')

                    <div class="compte-espace__contenu">
                        @if (session('succes'))
                            <div class="alert alert-success" role="status">{{ session('succes') }}</div>
                        @endif

                        <div class="compte-bloc compte-role">
                            <div class="compte-role__entete">
                                <h3>{{ __('Mon rôle') }}</h3>
                                <span class="compte-role__badge">{{ __($user->roleLibelle()) }}</span>
                            </div>
                            <ul class="compte-role__droits">
                                @foreach (\App\Models\User::DROITS[$user->role] as $droit)
                                    <li><i class="fas fa-check-circle"></i> {{ __($droit) }}</li>
                                @endforeach
                            </ul>
                            @if ($user->estAdministrateur())
                                <a href="{{ route('admin.profil') }}" class="compte-role__lien">{{ __('Mon profil dans l’administration') }} <i class="fas fa-chevron-right"></i></a>
                            @endif
                        </div>

                        <div class="compte-bloc">
                            <h3>{{ __('Mes informations') }}</h3>
                            <p class="compte-bloc__texte">{{ __('Si vous changez d’adresse e-mail, vous devrez confirmer la nouvelle adresse.') }}</p>
                            <form action="{{ route('compte.profil.update') }}" method="post" class="compte-form">
                                @csrf
                                @method('patch')
                                <div class="compte-form__grille">
                                    @include('compte._champ', ['nom' => 'name', 'label' => __('Nom complet'), 'valeur' => $user->name, 'requis' => true, 'attributs' => 'autocomplete="name" maxlength="120"'])
                                    @include('compte._champ', ['nom' => 'telephone', 'label' => __('Téléphone / WhatsApp'), 'type' => 'tel', 'valeur' => $user->telephone, 'attributs' => 'autocomplete="tel" maxlength="40"'])
                                </div>
                                @include('compte._champ', ['nom' => 'email', 'label' => __('Adresse e-mail'), 'type' => 'email', 'valeur' => $user->email, 'requis' => true, 'attributs' => 'autocomplete="email" maxlength="160"'])
                                <button type="submit" class="btn-one">
                                    <i class="icon-arrow"></i>
                                    <span class="txt">{{ __('Enregistrer') }}</span>
                                </button>
                            </form>
                        </div>

                        <div class="compte-bloc">
                            <h3>{{ __('Changer mon mot de passe') }}</h3>
                            <form action="{{ route('compte.mot-de-passe') }}" method="post" class="compte-form">
                                @csrf
                                @method('put')
                                @include('compte._champ', ['nom' => 'current_password', 'label' => __('Mot de passe actuel'), 'type' => 'password', 'requis' => true, 'sac' => 'motDePasse', 'attributs' => 'autocomplete="current-password"'])
                                <div class="compte-form__grille">
                                    @include('compte._champ', ['nom' => 'password', 'label' => __('Nouveau mot de passe'), 'type' => 'password', 'requis' => true, 'sac' => 'motDePasse', 'aide' => __('Au moins 8 caractères.'), 'attributs' => 'autocomplete="new-password" minlength="8"'])
                                    @include('compte._champ', ['nom' => 'password_confirmation', 'label' => __('Confirmer le mot de passe'), 'type' => 'password', 'requis' => true, 'sac' => 'motDePasse', 'attributs' => 'autocomplete="new-password" minlength="8"'])
                                </div>
                                <button type="submit" class="btn-one">
                                    <i class="icon-arrow"></i>
                                    <span class="txt">{{ __('Modifier le mot de passe') }}</span>
                                </button>
                            </form>
                        </div>

                        @unless ($user->estAdministrateur())
                            <div class="compte-bloc compte-bloc--danger">
                                <h3>{{ __('Supprimer mon compte') }}</h3>
                                <p class="compte-bloc__texte">{{ __('La suppression est définitive. Vos commandes restent traitées par la boutique, mais ne seront plus visibles dans un espace client.') }}</p>
                                <form action="{{ route('compte.supprimer') }}" method="post" class="compte-form"
                                    onsubmit="return confirm(@js(__('Supprimer définitivement votre compte ?')));">
                                    @csrf
                                    @method('delete')
                                    @include('compte._champ', ['nom' => 'password', 'label' => __('Mot de passe (pour confirmer)'), 'type' => 'password', 'requis' => true, 'sac' => 'suppression', 'attributs' => 'autocomplete="current-password"'])
                                    <button type="submit" class="compte-bouton-danger">
                                        <i class="fas fa-trash-alt"></i> {{ __('Supprimer mon compte') }}
                                    </button>
                                </form>
                            </div>
                        @endunless
                    </div>
                </div>
            </div>
        </section>

@endsection
