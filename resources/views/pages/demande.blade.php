@extends('layouts.app')

@section('title', 'Demande de service')

@php
    $etapes = [
        ['image' => 'demande-personnalisee.jpg', 'titre' => 'Demande personnalisée', 'texte' => 'Votre demande est étudiée en fonction de vos besoins.'],
        ['image' => 'echange-equipe.jpg', 'titre' => 'Échange avec notre équipe', 'texte' => 'Notre équipe pourra vous contacter pour approfondir votre demande.'],
        ['image' => 'whatsapp.jpg', 'titre' => 'Transmission par WhatsApp', 'texte' => 'Votre demande sera préparée et transmise directement sur WhatsApp.'],
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
                            <form id="demande-service-form" class="default-form2" action="#" method="post" novalidate>
                                <p class="mb-3">Les champs marqués d’un * sont obligatoires.</p>

                                <div class="row">
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="text" name="nom" placeholder="Nom complet *" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="text" name="telephone" placeholder="Téléphone *" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="email" name="email" placeholder="Adresse e-mail">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="text" name="organisation" placeholder="Organisation / Structure">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <div class="select-box clearfix">
                                                <select class="wide" name="service" required>
                                                    <option value="" data-display="Service souhaité *">Sélectionnez le service souhaité</option>
                                                    @foreach (config('rejeppat.services_demande') as $service)
                                                        <option value="{{ $service }}" @selected(request('service') === $service)>{{ $service }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="text" name="region" placeholder="Région / Localité">
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="input-box">
                                            <input type="text" name="objet" placeholder="Objet de la demande *" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="input-box">
                                            <textarea name="besoin" placeholder="Décrivez votre besoin *" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="checked-box1">
                                    <input type="checkbox" name="accord" id="accord" required>
                                    <label for="accord">
                                        <span></span>J’accepte que les informations renseignées soient utilisées pour traiter ma demande de service. *
                                    </label>
                                </div>
                                <p class="text-danger mt-2" id="demande-erreur" hidden>Veuillez remplir tous les champs obligatoires (*) et accepter les conditions.</p>
                                <div class="btn-box">
                                    <button type="submit">
                                        Envoyer ma demande via WhatsApp
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

@push('scripts')
    <script>
        // Comme sur l'ancien site : la demande est préparée puis transmise sur WhatsApp.
        document.getElementById('demande-service-form').addEventListener('submit', function (event) {
            event.preventDefault();
            var form = event.target;
            var champ = function (nom) { return (form.elements[nom].value || '').trim(); };
            var erreur = document.getElementById('demande-erreur');

            var obligatoires = ['nom', 'telephone', 'service', 'objet', 'besoin'];
            var incomplet = obligatoires.some(function (nom) { return champ(nom) === ''; }) || !form.elements.accord.checked;
            erreur.hidden = !incomplet;
            if (incomplet) {
                return;
            }

            var lignes = [
                '*Demande de service - REJEPPAT*',
                'Nom complet : ' + champ('nom'),
                'Téléphone : ' + champ('telephone'),
                'E-mail : ' + (champ('email') || '-'),
                'Organisation / Structure : ' + (champ('organisation') || '-'),
                'Service souhaité : ' + champ('service'),
                'Région / Localité : ' + (champ('region') || '-'),
                'Objet : ' + champ('objet'),
                '',
                champ('besoin')
            ];

            window.open('https://wa.me/{{ config('rejeppat.whatsapp') }}?text=' + encodeURIComponent(lignes.join('\n')), '_blank', 'noopener');
        });
    </script>
@endpush
