@extends('layouts.app')

@section('title', __('Agenda'))

@php use App\Models\Evenement; @endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Agenda'),
            'texte' => __('Ateliers, formations, panels et foires du réseau.'),
            'image' => 'actualites.jpg',
            'liens' => [__('Actualités & Événements') => route('actualites.index')],
        ])

        <section class="rj-section">
            <div class="container">
                <div class="rj-filtres">
                    <a href="{{ route('agenda.index') }}" class="{{ $type ? '' : 'actif' }}">{{ __('Tous les rendez-vous') }}</a>
                    @foreach (Evenement::TYPES as $cle => $libelle)
                        <a href="{{ route('agenda.index', ['type' => $cle]) }}" class="{{ $type === $cle ? 'actif' : '' }}">{{ __($libelle) }}</a>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-xl-8 col-lg-8">
                        <h2 class="rj-titre-bloc">{{ __('Prochains rendez-vous') }}</h2>
                        @forelse ($aVenir as $evenement)
                            @include('agenda._evenement', ['evenement' => $evenement])
                        @empty
                            <div class="rj-vide">
                                <i class="far fa-calendar-alt"></i>
                                <p>{{ __('Aucun rendez-vous programmé pour le moment. Les prochaines dates seront annoncées ici et dans notre newsletter.') }}</p>
                            </div>
                        @endforelse

                        @if ($passes->isNotEmpty())
                            <h2 class="rj-titre-bloc" style="margin-top: 60px">{{ __('Rendez-vous passés') }}</h2>
                            @foreach ($passes as $evenement)
                                @include('agenda._evenement', ['evenement' => $evenement, 'passe' => true])
                            @endforeach
                        @endif
                    </div>
                    <div class="col-xl-4 col-lg-4">
                        <aside class="rj-encadre">
                            <h3>{{ __('Ne manquez aucun rendez-vous') }}</h3>
                            <p>{{ __('Recevez les dates des formations et des foires en vous abonnant à la newsletter (en bas de page).') }}</p>
                            <a href="{{ route('actualites.index', ['categorie' => 'evenements']) }}" class="rj-bouton-petit">{{ __('Comptes rendus d’événements') }} <i class="icon-arrow"></i></a>
                        </aside>
                        <aside class="rj-encadre">
                            <h3>{{ __('Médiathèque') }}</h3>
                            <p>{{ __('Photos, vidéos et podcasts des activités du réseau.') }}</p>
                            <a href="{{ route('mediatheque') }}" class="rj-bouton-petit">{{ __('Voir la médiathèque') }} <i class="icon-arrow"></i></a>
                        </aside>
                    </div>
                </div>
            </div>
        </section>

@endsection
