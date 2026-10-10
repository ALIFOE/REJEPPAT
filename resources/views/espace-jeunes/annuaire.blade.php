@extends('layouts.app')

@section('title', __('Annuaire des membres'))

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Annuaire des membres'),
            'texte' => __('Jeunes producteurs et coopératives du réseau REJEPPAT.'),
            'image' => 'demande.jpg',
            'liens' => [__('Espace Jeunes') => route('espace-jeunes.index')],
        ])

        <section class="rj-section">
            <div class="container">
                @include('espace-jeunes._menu')

                <form class="rj-recherche" method="get" action="{{ route('espace-jeunes.annuaire') }}">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('Nom, coopérative, localité…') }}" aria-label="{{ __('Rechercher') }}">
                    <select name="region" class="ignore" aria-label="{{ __('Région') }}">
                        <option value="">{{ __('Toutes les régions') }}</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->slug }}" @selected(request('region') === $region->slug)>{{ __($region->nom) }}</option>
                        @endforeach
                    </select>
                    <select name="filiere" class="ignore" aria-label="{{ __('Filière') }}">
                        <option value="">{{ __('Toutes les filières') }}</option>
                        @foreach (\App\Models\Membre::FILIERES as $cle => $libelle)
                            <option value="{{ $cle }}" @selected(request('filiere') === $cle)>{{ __($libelle) }}</option>
                        @endforeach
                    </select>
                    <button type="submit"><i class="icon-search"></i> {{ __('Rechercher') }}</button>
                </form>

                @if ($membres->isEmpty())
                    <div class="rj-vide">
                        <i class="fas fa-address-book"></i>
                        <p>{{ request()->hasAny(['q', 'region', 'filiere']) ? __('Aucun membre ne correspond à votre recherche.') : __('L’annuaire est en cours de constitution : les premiers profils seront bientôt publiés.') }}</p>
                        <a href="{{ route('espace-jeunes.rejoindre') }}" class="btn-one"><i class="icon-arrow"></i><span class="txt">{{ __('Rejoindre l’annuaire') }}</span></a>
                    </div>
                @else
                    <div class="rj-grille">
                        @foreach ($membres as $membre)
                            @include('espace-jeunes._membre', ['membre' => $membre])
                        @endforeach
                    </div>
                    <div class="rj-pagination">{{ $membres->links() }}</div>
                    <p class="text-center" style="margin-top: 40px">
                        <a href="{{ route('espace-jeunes.rejoindre') }}" class="btn-one"><i class="icon-arrow"></i><span class="txt">{{ __('Rejoindre l’annuaire') }}</span></a>
                    </p>
                @endif
            </div>
        </section>

@endsection
