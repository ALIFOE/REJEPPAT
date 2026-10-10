@extends('layouts.app')

@section('title', __('Ressources téléchargeables'))

@php use App\Models\Ressource; @endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Ressources téléchargeables'),
            'texte' => __('Guides, manuels et supports de formation, libres d’accès.'),
            'image' => 'demande.jpg',
            'liens' => [__('Espace Jeunes') => route('espace-jeunes.index')],
        ])

        <section class="rj-section rj-section--clair">
            <div class="container">
                @include('espace-jeunes._menu')

                <div class="rj-filtres" style="margin-top: -20px">
                    <a href="{{ route('espace-jeunes.ressources') }}" class="{{ $categorie ? '' : 'actif' }}">{{ __('Toutes') }} <span>{{ $compteurs->sum() }}</span></a>
                    @foreach (Ressource::CATEGORIES as $cle => $libelle)
                        @continue(empty($compteurs[$cle]))
                        <a href="{{ route('espace-jeunes.ressources', ['categorie' => $cle]) }}" class="{{ $categorie === $cle ? 'actif' : '' }}">{{ __($libelle) }} <span>{{ $compteurs[$cle] }}</span></a>
                    @endforeach
                </div>

                @if ($ressources->isEmpty())
                    <div class="rj-vide"><i class="fas fa-file-download"></i><p>{{ __('Les premiers documents seront bientôt disponibles.') }}</p></div>
                @else
                    <div class="rj-grille rj-grille--3">
                        @foreach ($ressources as $ressource)
                            <article class="rj-document">
                                <span class="rj-document__icone">{{ $ressource->format() }}</span>
                                <div>
                                    <h3>{{ $ressource->tr('titre') }}</h3>
                                    @if ($ressource->tr('description'))<p>{{ $ressource->tr('description') }}</p>@endif
                                    <ul class="rj-meta" style="font-size: 14px; margin-bottom: 12px">
                                        <li>{{ $ressource->categorieLibelle() }}</li>
                                        @if ($taille = $ressource->taille())<li>{{ $taille }}</li>@endif
                                    </ul>
                                    <a href="{{ route('espace-jeunes.ressources.telecharger', $ressource) }}" class="rj-bouton-petit"><i class="fas fa-download"></i> {{ __('Télécharger') }}</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

@endsection
