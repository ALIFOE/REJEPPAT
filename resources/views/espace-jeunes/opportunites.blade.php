@extends('layouts.app')

@section('title', __('Opportunités'))

@php use App\Models\Opportunite; @endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Opportunités'),
            'texte' => __('Emplois, appels à projets, financements, bourses et formations pour les jeunes.'),
            'image' => 'demande.jpg',
            'liens' => [__('Espace Jeunes') => route('espace-jeunes.index')],
        ])

        <section class="rj-section">
            <div class="container">
                @include('espace-jeunes._menu')

                <div class="row">
                    <div class="col-xl-8 col-lg-8">
                        <h2 class="rj-titre-bloc">{{ $archives ? __('Opportunités clôturées') : ($type ? __(Opportunite::TYPES[$type]) : __('Opportunités ouvertes')) }}</h2>
                        @forelse ($opportunites as $opportunite)
                            @include('espace-jeunes._opportunite', ['opportunite' => $opportunite])
                        @empty
                            <div class="rj-vide">
                                <i class="fas fa-bullhorn"></i>
                                <p>{{ __('Aucune opportunité pour le moment. Abonnez-vous à la newsletter pour être informé des prochaines.') }}</p>
                            </div>
                        @endforelse
                        <div class="rj-pagination">{{ $opportunites->links() }}</div>
                    </div>
                    <div class="col-xl-4 col-lg-4">
                        <aside class="rj-encadre">
                            <h3>{{ __('Par type') }}</h3>
                            <ul>
                                <li class="{{ ! $type && ! $archives ? 'actif' : '' }}"><i class="icon-arrow"></i> <a href="{{ route('espace-jeunes.opportunites') }}">{{ __('Toutes les opportunités ouvertes') }} ({{ $compteurs->sum() }})</a></li>
                                @foreach (Opportunite::TYPES as $cle => $libelle)
                                    <li class="{{ $type === $cle && ! $archives ? 'actif' : '' }}"><i class="icon-arrow"></i> <a href="{{ route('espace-jeunes.opportunites', ['type' => $cle]) }}">{{ __($libelle) }} ({{ $compteurs[$cle] ?? 0 }})</a></li>
                                @endforeach
                                <li class="{{ $archives ? 'actif' : '' }}"><i class="fas fa-archive"></i> <a href="{{ route('espace-jeunes.opportunites', ['archives' => 1]) }}">{{ __('Opportunités clôturées') }}</a></li>
                            </ul>
                        </aside>
                        <aside class="rj-encadre">
                            <h3>{{ __('Vous avez une opportunité à partager ?') }}</h3>
                            <p>{{ __('Entreprises, ONG et institutions : faites connaître vos offres aux jeunes du réseau.') }}</p>
                            <a href="{{ route('contact') }}" class="rj-bouton-petit">{{ __('Nous écrire') }} <i class="icon-arrow"></i></a>
                        </aside>
                    </div>
                </div>
            </div>
        </section>

@endsection
