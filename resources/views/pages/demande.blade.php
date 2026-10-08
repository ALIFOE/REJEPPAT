@extends('layouts.app')

@section('title', 'Demande de service')

@php
    $etapes = [
        ['image' => 'demande-personnalisee.jpg', 'titre' => 'Demande personnalisée', 'texte' => 'Votre demande est étudiée en fonction de vos besoins.'],
        ['image' => 'echange-equipe.jpg', 'titre' => 'Échange avec notre équipe', 'texte' => 'Notre équipe pourra vous contacter pour approfondir votre demande.'],
        ['image' => 'whatsapp.jpg', 'titre' => 'Suivi et réponse', 'texte' => 'Votre demande est enregistrée et suivie par notre équipe, qui vous répond par téléphone, WhatsApp ou e-mail.'],
    ];
@endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => 'Demande de service',
            'texte' => 'Présentez-nous votre besoin et transmettez votre demande à l’équipe du REJEPPAT.',
            'image' => 'demande.jpg',
            'liens' => ['Nos offres & services' => route('offres')],
        ])

        <!--Start Location Info -->
        <section class="location-info">
            <div class="container">

                <div class="sec-title withtext text-center sec-title-animation animation-style2">
                    <div class="sub-title">
                        <div class="icon">
                            <i class="icon-hat"></i>
                        </div>
                        <h4>Parlons de votre projet</h4>
                    </div>
                    <h2 class="title-animation">Comment ça marche ?</h2>
                    <div class="text">
                        <p>Quelques informations nous permettront de mieux comprendre votre besoin <br> et de vous
                            orienter vers le service approprié.</p>
                    </div>
                </div>

                <div class="row">
                    @foreach ($etapes as $etape)
                    <!--Start Location Info Single-->
                    <div class="col-xl-4 col-lg-4">
                        <div class="location-info__single">
                            <div class="location-info__single-img">
                                <img src="{{ asset('assets/images/rejeppat/demande/' . $etape['image']) }}" alt="{{ $etape['titre'] }}">
                            </div>

                            <div class="location-info__single-content">
                                <div class="location-info__single-content-title">
                                    <h3>{{ $etape['titre'] }}</h3>
                                </div>
                                <div class="location-info__single-content-text">
                                    <p>{{ $etape['texte'] }}</p>
                                </div>
                                <div class="location-info__single-content-btn">
                                    <a href="#formulaire">Remplir le formulaire <span class="icon-arrow"></span></a>
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
                        style="background-image: url({{ asset('assets/images/shapes/main-contact-form-bg.jpg?v=vert') }});"></div>
                    <div class="sec-title sec-title-animation animation-style2">
                        <div class="sub-title">
                            <div class="icon">
                                <i class="icon-hat"></i>
                            </div>
                            <h4>Vos informations</h4>
                        </div>
                        <h2 class="title-animation">Formulaire de demande de service</h2>
                    </div>
                    <div class="row">
                        <!--Start Main Contact Form-->
                        <div class="contact-form">
                            @if (session('succes'))
                                <div class="alert alert-success">
                                    {{ session('succes') }}
                                    @if (session('demande_whatsapp'))
                                        <br><a href="{{ session('demande_whatsapp') }}" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Transmettre aussi ma demande sur WhatsApp</a>
                                    @endif
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">Veuillez corriger les champs signalés.</div>
                            @endif

                            <form id="demande-service-form" class="default-form2" action="{{ route('demande.envoyer') }}" method="post">
                                @csrf
                                <p class="mb-3">Les champs marqués d’un * sont obligatoires.</p>

                                <div class="row">
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="text" name="nom" placeholder="Nom complet *" required value="{{ old('nom') }}">
                                            @error('nom') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="text" name="telephone" placeholder="Téléphone *" required value="{{ old('telephone') }}">
                                            @error('telephone') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="email" name="email" placeholder="Adresse e-mail" value="{{ old('email') }}">
                                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="text" name="organisation" placeholder="Organisation / Structure" value="{{ old('organisation') }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <div class="select-box clearfix">
                                                <select class="wide" name="service" required>
                                                    <option value="" data-display="Service souhaité *">Sélectionnez le service souhaité</option>
                                                    @foreach ($services as $service)
                                                        <option value="{{ $service }}" @selected(old('service', request('service')) === $service)>{{ $service }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('service') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="text" name="region" placeholder="Région / Localité" value="{{ old('region') }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="input-box">
                                            <input type="text" name="objet" placeholder="Objet de la demande *" required value="{{ old('objet') }}">
                                            @error('objet') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="input-box">
                                            <textarea name="besoin" placeholder="Décrivez votre besoin *" required>{{ old('besoin') }}</textarea>
                                            @error('besoin') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="checked-box1">
                                    <input type="checkbox" name="accord" id="accord" value="1" required @checked(old('accord'))>
                                    <label for="accord">
                                        <span></span>J’accepte que les informations renseignées soient utilisées pour traiter ma demande de service. *
                                    </label>
                                </div>
                                @error('accord') <p class="text-danger mt-2">{{ $message }}</p> @enderror
                                <div class="btn-box">
                                    <button type="submit">
                                        Envoyer ma demande
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
