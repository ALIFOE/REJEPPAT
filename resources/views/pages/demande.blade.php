@extends('layouts.app')

@section('title', __('Demande de service'))

@section('body_class', 'body-bg-1 page-reseau')

@php
    $etapes = [
        ['image' => 'demande-personnalisee.jpg', 'titre' => __('Demande personnalisée'), 'texte' => __('Votre demande est étudiée en fonction de vos besoins.')],
        ['image' => 'echange-equipe.jpg', 'titre' => __('Échange avec notre équipe'), 'texte' => __('Notre équipe pourra vous contacter pour approfondir votre demande.')],
        ['image' => 'whatsapp.jpg', 'titre' => __('Suivi et réponse'), 'texte' => __('Votre demande est enregistrée et suivie par notre équipe, qui vous répond par téléphone, WhatsApp ou e-mail.')],
    ];
@endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Demande de service'),
            'texte' => __('Présentez-nous votre besoin et transmettez votre demande à l’équipe du REJEPPAT.'),
            'image' => 'demande.jpg',
            'liens' => [__('Nos offres & services') => route('offres')],
        ])

        <!--Start Location Info -->
        <section class="location-info">
            <div class="container">

                <div class="sec-title withtext text-center sec-title-animation animation-style2">
                    <div class="sub-title">
                        <div class="icon">
                            <i class="icon-hat"></i>
                        </div>
                        <h4>{{ __('Parlons de votre projet') }}</h4>
                    </div>
                    <h2 class="title-animation">{{ __('Comment ça marche ?') }}</h2>
                    <div class="text">
                        <p>{!! __('Quelques informations nous permettront de mieux comprendre votre besoin <br> et de vous orienter vers le service approprié.') !!}</p>
                    </div>
                </div>

                <div class="row">
                    @foreach ($etapes as $etape)
                    <!--Start Location Info Single-->
                    <div class="col-xl-4 col-lg-4">
                        <div class="location-info__single">
                            <div class="location-info__single-img">
                                <img src="{{ asset('assets/images/rejeppat/demande/' . $etape['image']) }}" alt="{{ __($etape['titre']) }}">
                            </div>

                            <div class="location-info__single-content">
                                <div class="location-info__single-content-title">
                                    <h3>{{ __($etape['titre']) }}</h3>
                                </div>
                                <div class="location-info__single-content-text">
                                    <p>{{ __($etape['texte']) }}</p>
                                </div>
                                <div class="location-info__single-content-btn">
                                    <a href="#formulaire">{{ __('Remplir le formulaire') }} <span class="icon-arrow"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Location Info Single-->
                    @endforeach
                </div>
            </div>
        </section>
        <!--End Location Info -->


        <!-- Start Main Contact Form -->
        <section class="main-contact-form" id="formulaire">
            <div class="container">
                <div class="main-contact-form__inner">
                    <div class="main-contact-form__shape-bg"
                        style="background-image: url({{ asset('assets/images/shapes/main-contact-form-bg.jpg') }}?v=vert);"></div>
                    <div class="sec-title sec-title-animation animation-style2">
                        <div class="sub-title">
                            <div class="icon">
                                <i class="icon-hat"></i>
                            </div>
                            <h4>{{ __('Vos informations') }}</h4>
                        </div>
                        <h2 class="title-animation">{{ __('Formulaire de demande de service') }}</h2>
                    </div>
                    <div class="row">
                        <!--Start Main Contact Form-->
                        <div class="contact-form">
                            @if (session('succes'))
                                <div class="alert alert-success">
                                    {{ session('succes') }}
                                    @if (session('demande_whatsapp'))
                                        <br><a href="{{ session('demande_whatsapp') }}" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> {{ __('Transmettre aussi ma demande sur WhatsApp') }}</a>
                                    @endif
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">{{ __('Veuillez corriger les champs signalés.') }}</div>
                            @endif

                            <form id="demande-service-form" class="default-form2" action="{{ route('demande.envoyer') }}" method="post">
                                @csrf
                                <p class="mb-3">{{ __('Les champs marqués d’un * sont obligatoires.') }}</p>

                                <div class="row">
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="text" name="nom" placeholder="{{ __('Nom complet *') }}" required value="{{ old('nom') }}">
                                            @error('nom') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="text" name="telephone" placeholder="{{ __('Téléphone *') }}" required value="{{ old('telephone') }}">
                                            @error('telephone') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="email" name="email" placeholder="{{ __('Adresse e-mail') }}" value="{{ old('email') }}">
                                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="text" name="organisation" placeholder="{{ __('Organisation / Structure') }}" value="{{ old('organisation') }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <div class="select-box clearfix">
                                                <select class="wide" name="service" required>
                                                    <option value="" data-display="Service souhaité *">{{ __('Sélectionnez le service souhaité') }}</option>
                                                    @php $libellesServices = \App\Support\Contenu::offres()->mapWithKeys(fn ($offre) => [$offre->titre => $offre->tr('titre')]); @endphp
                                                    @foreach ($services as $service)
                                                        <option value="{{ $service }}" @selected(old('service', request('service')) === $service)>{{ $libellesServices[$service] ?? __($service) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('service') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="text" name="region" placeholder="{{ __('Région / Localité') }}" value="{{ old('region') }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="input-box">
                                            <input type="text" name="objet" placeholder="{{ __('Objet de la demande *') }}" required value="{{ old('objet') }}">
                                            @error('objet') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="input-box">
                                            <textarea name="besoin" placeholder="{{ __('Décrivez votre besoin *') }}" required>{{ old('besoin') }}</textarea>
                                            @error('besoin') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="checked-box1">
                                    <input type="checkbox" name="accord" id="accord" value="1" required @checked(old('accord'))>
                                    <label for="accord">
                                        <span></span>{{ __('J’accepte que les informations renseignées soient utilisées pour traiter ma demande de service. *') }}
                                    </label>
                                </div>
                                @error('accord') <p class="text-danger mt-2">{{ $message }}</p> @enderror
                                <div class="btn-box">
                                    <button type="submit">
                                        {{ __('Envoyer ma demande') }}
                                        <i class="icon-angle-double-small-right"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!--End Main Contact Form-->
                    </div>
                </div>
            </div>
        </section>
        <!-- End Main Contact Form -->

@endsection
