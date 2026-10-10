@extends('layouts.app')

@section('title', __('Mon compte'))

@php
    use App\Support\Contenu;

    $user = auth()->user();
    // Étapes du suivi d'une commande en cours
    $etapes = ['en_attente' => __('Reçue'), 'confirmee' => __('Confirmée'), 'en_livraison' => __('En livraison'), 'livree' => __('Livrée')];
    $cartes = [
        ['valeur' => $chiffres['commandes'], 'libelle' => __('Commandes passées'), 'icone' => 'fas fa-shopping-basket'],
        ['valeur' => $chiffres['en_cours'], 'libelle' => __('Commandes en cours'), 'icone' => 'fas fa-truck'],
        ['valeur' => $chiffres['livrees'], 'libelle' => __('Commandes livrées'), 'icone' => 'fas fa-check-circle'],
        ['valeur' => Contenu::prix($chiffres['depense']), 'libelle' => __('Total de mes achats'), 'icone' => 'fas fa-coins'],
    ];
@endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Mon compte'),
            'texte' => __('Bonjour :nom, bienvenue dans votre espace client.', ['nom' => $user->name]),
            'image' => 'fermes.jpg',
        ])

        <section class="compte-section">
            <div class="container">
                <div class="compte-espace">
                    @include('compte._menu')

                    <div class="compte-espace__contenu">
                        @if (session('succes'))
                            <div class="alert alert-success" role="status">{{ session('succes') }}</div>
                        @endif
                        @if (session('erreur'))
                            <div class="alert alert-warning" role="alert">{{ session('erreur') }}</div>
                        @endif

                        <div class="compte-bienvenue">
                            <div>
                                <h3>{{ __('Bonjour :nom !', ['nom' => $user->name]) }}</h3>
                                <p>{{ __('Suivez vos commandes, retrouvez vos achats et gérez vos informations.') }}</p>
                            </div>
                            <a class="btn-one" href="{{ route('boutique.index') }}">
                                <i class="icon-arrow"></i>
                                <span class="txt">{{ __('Commander') }}</span>
                            </a>
                        </div>

                        <div class="compte-chiffres">
                            @foreach ($cartes as $carte)
                                <div class="compte-chiffre">
                                    <span><i class="{{ $carte['icone'] }}"></i></span>
                                    <strong>{{ $carte['valeur'] }}</strong>
                                    <small>{{ $carte['libelle'] }}</small>
                                </div>
                            @endforeach
                        </div>

                        @if ($commandeEnCours)
                            @php $position = array_search($commandeEnCours->statut, array_keys($etapes), true); @endphp
                            <div class="compte-bloc">
                                <div class="compte-bloc__entete">
                                    <h3>{{ __('Ma commande en cours') }}</h3>
                                    <a href="{{ route('compte.commande', $commandeEnCours->numero) }}">{{ __('Voir le détail') }} <i class="fas fa-chevron-right"></i></a>
                                </div>
                                <p class="compte-bloc__texte">
                                    <strong>{{ $commandeEnCours->numero }}</strong> {{ __('du') }} {{ \App\Support\Contenu::date($commandeEnCours->created_at, 'j F Y') }}
                                    · {{ Contenu::prix($commandeEnCours->total) }}
                                </p>
                                <ol class="compte-suivi">
                                    @foreach ($etapes as $cle => $libelle)
                                        <li @class(['fait' => $loop->index <= $position, 'actuel' => $loop->index === $position])>
                                            <span>@if ($loop->index < $position)<i class="fas fa-check"></i>@else{{ $loop->iteration }}@endif</span>
                                            {{ $libelle }}
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        @endif

                        <div class="compte-bloc">
                            <div class="compte-bloc__entete">
                                <h3>{{ __('Mes dernières commandes') }}</h3>
                                @if ($chiffres['commandes'] > 0)
                                    <a href="{{ route('compte.commandes') }}">{{ __('Toutes mes commandes') }} <i class="fas fa-chevron-right"></i></a>
                                @endif
                            </div>

                            @if ($dernieresCommandes->isEmpty())
                                <div class="compte-vide">
                                    <span><i class="fas fa-shopping-basket"></i></span>
                                    <p>{{ __('Vous n’avez pas encore passé de commande avec ce compte.') }}</p>
                                    <a class="btn-one" href="{{ route('boutique.index') }}">
                                        <i class="icon-arrow"></i>
                                        <span class="txt">{{ __('Découvrir la boutique') }}</span>
                                    </a>
                                </div>
                            @else
                                <div class="compte-tableau">
                                    <table>
                                        <thead>
                                            <tr><th>{{ __('Numéro') }}</th><th>{{ __('Date') }}</th><th>{{ __('Total') }}</th><th>{{ __('Statut') }}</th><th><span class="sr-only">{{ __('Détail') }}</span></th></tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dernieresCommandes as $commande)
                                                <tr>
                                                    <td><strong>{{ $commande->numero }}</strong></td>
                                                    <td>{{ $commande->created_at->locale('fr')->translatedFormat('j F Y') }}</td>
                                                    <td>{{ Contenu::prix($commande->total) }}</td>
                                                    <td><span class="statut-commande statut-commande--{{ $commande->statut }}">{{ $commande->statutLibelle() }}</span></td>
                                                    <td><a href="{{ route('compte.commande', $commande->numero) }}">{{ __('Voir') }} <i class="fas fa-chevron-right"></i></a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <div class="compte-raccourcis">
                            <a href="{{ route('boutique.index') }}"><i class="fas fa-store"></i><strong>{{ __('La boutique') }}</strong><small>{{ __('Produits frais du réseau') }}</small></a>
                            <a href="{{ route('panier.index') }}"><i class="fas fa-shopping-cart"></i><strong>{{ __('Mon panier') }}</strong><small>{{ __('Finaliser une commande') }}</small></a>
                            <a href="{{ route('compte.profil') }}"><i class="fas fa-user-cog"></i><strong>{{ __('Mon profil') }}</strong><small>{{ __('Informations et mot de passe') }}</small></a>
                            <a href="https://wa.me/{{ config('rejeppat.whatsapp') }}" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i><strong>{{ __('Une question ?') }}</strong><small>{{ __('Écrivez-nous sur WhatsApp') }}</small></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

@endsection
