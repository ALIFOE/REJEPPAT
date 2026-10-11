@extends('layouts.app')

@section('title', __('Partenaires'))

@section('body_class', 'body-bg-1 page-reseau')

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Nos partenaires'),
            'texte' => __('Ensemble pour une agriculture durable portée par la jeunesse.'),
            'image' => 'offres.jpg',
        ])

        <section class="rj-section">
            <div class="container">
                <div class="rj-intro">
                    <div class="sec-title text-center">
                        <div class="sub-title">
                            <div class="icon"><i class="icon-hat"></i></div>
                            <h4>{{ __('Ils nous accompagnent') }}</h4>
                        </div>
                        <h2>{{ __('Partenaires institutionnels, techniques et financiers') }}</h2>
                    </div>
                    <p>{{ __('Le REJEPPAT mène ses actions avec l’appui d’institutions, d’organisations agricoles et de bailleurs qui partagent sa vision d’une agriculture durable et inclusive.') }}</p>
                </div>

                @forelse ($types as $type => $libelle)
                    @continue(empty($parType[$type]))
                    <div class="{{ $loop->first ? '' : 'rj-bloc' }}">
                        <h3 class="rj-titre-bloc">{{ __($libelle) }}</h3>
                        <div class="rj-grille rj-grille--logos">
                            @foreach ($parType[$type] as $partenaire)
                                <div class="rj-logo">
                                    <div class="rj-logo__image"><img src="{{ $partenaire->visuel() }}" alt="{{ $partenaire->nom }}" loading="lazy"></div>
                                    <h4>{{ $partenaire->nom }}</h4>
                                    @if ($partenaire->tr('description'))<p>{{ $partenaire->tr('description') }}</p>@endif
                                    @if ($partenaire->site)<a href="{{ $partenaire->site }}" target="_blank" rel="noopener">{{ __('Site internet') }} <i class="fas fa-external-link-alt"></i></a>@endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </section>

        <section class="rj-section rj-section--clair" id="devenir-partenaire">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5 col-lg-5">
                        <div class="sec-title">
                            <div class="sub-title">
                                <div class="icon"><i class="icon-hat"></i></div>
                                <h4>{{ __('Devenir partenaire') }}</h4>
                            </div>
                            <h2>{{ __('Modalités de partenariat') }}</h2>
                        </div>
                        <p>{{ __('Organisations, entreprises, institutions ou médias : plusieurs formes de collaboration sont possibles avec le réseau.') }}</p>
                        <ul class="rj-liste-puces">
                            @foreach ($formes as $libelle)
                                <li class="rj-pastille">{{ __(\Illuminate\Support\Str::before($libelle, ' (')) }}</li>
                            @endforeach
                        </ul>
                        <ol class="rj-etapes">
                            <li><h4>{{ __('Présentez votre proposition') }}</h4><p>{{ __('Décrivez votre organisation et la collaboration envisagée avec le formulaire.') }}</p></li>
                            <li><h4>{{ __('Échange avec notre équipe') }}</h4><p>{{ __('Le bureau exécutif étudie votre proposition et vous recontacte pour en discuter.') }}</p></li>
                            <li><h4>{{ __('Convention de partenariat') }}</h4><p>{{ __('Les engagements de chacun sont formalisés dans une convention, puis suivis ensemble.') }}</p></li>
                        </ol>
                    </div>
                    <div class="col-xl-7 col-lg-7">
                        <div class="rj-formulaire">
                            @if (session('succes'))
                                <div class="alert alert-success">{{ session('succes') }}</div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">{{ __('Veuillez corriger les champs signalés.') }}</div>
                            @endif
                            <form action="{{ route('partenaires.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 input-box">
                                        <label class="rj-label" for="p-organisation">{{ __('Organisation') }} *</label>
                                        <input type="text" id="p-organisation" name="organisation" value="{{ old('organisation') }}" required>
                                        @error('organisation') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                    <div class="col-md-6 input-box">
                                        <label class="rj-label" for="p-type">{{ __('Forme de partenariat') }} *</label>
                                        <select id="p-type" name="type" class="ignore" required>
                                            @foreach ($formes as $cle => $libelle)
                                                <option value="{{ $cle }}" @selected(old('type') === $cle)>{{ __($libelle) }}</option>
                                            @endforeach
                                        </select>
                                        @error('type') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                    <div class="col-md-6 input-box">
                                        <label class="rj-label" for="p-nom">{{ __('Votre nom') }} *</label>
                                        <input type="text" id="p-nom" name="nom" value="{{ old('nom') }}" required>
                                        @error('nom') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                    <div class="col-md-6 input-box">
                                        <label class="rj-label" for="p-fonction">{{ __('Fonction') }}</label>
                                        <input type="text" id="p-fonction" name="fonction" value="{{ old('fonction') }}">
                                    </div>
                                    <div class="col-md-6 input-box">
                                        <label class="rj-label" for="p-email">{{ __('Adresse e-mail') }} *</label>
                                        <input type="email" id="p-email" name="email" value="{{ old('email') }}" required>
                                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                    <div class="col-md-6 input-box">
                                        <label class="rj-label" for="p-telephone">{{ __('Téléphone') }}</label>
                                        <input type="tel" id="p-telephone" name="telephone" value="{{ old('telephone') }}">
                                    </div>
                                    <div class="col-12 input-box">
                                        <label class="rj-label" for="p-message">{{ __('Votre proposition') }} *</label>
                                        <textarea id="p-message" name="message" required>{{ old('message') }}</textarea>
                                        @error('message') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn-one"><i class="icon-arrow"></i><span class="txt">{{ __('Envoyer ma proposition') }}</span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

@endsection
