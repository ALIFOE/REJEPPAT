@extends('layouts.app')

@section('title', 'Contact')

@php
    $carte = 'https://www.google.com/maps?q=' . urlencode(config('rejeppat.map_query'));
@endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => 'Contact',
            'texte' => 'Contactez-nous pour des produits biologiques et naturels.',
            'image' => 'contact.jpg',
        ])

        <!--Start Location Info -->
        <section class="location-info">
            <div class="container">

                <div class="sec-title withtext text-center sec-title-animation animation-style2">
                    <div class="sub-title">
                        <div class="icon">
                            <i class="icon-hat"></i>
                        </div>
                        <h4>Nos coordonnées</h4>
                    </div>
                    <h2 class="title-animation">Contactez-nous</h2>
                    <div class="text">
                        <p>Notre équipe reste à votre disposition pour répondre à toutes vos questions. <br>
                            Contactez-nous par téléphone, WhatsApp, e-mail ou via le formulaire ci-dessous.</p>
                    </div>
                </div>

                <div class="row">
                    <!--Start Location Info Single-->
                    <div class="col-xl-4 col-lg-4">
                        <div class="location-info__single">
                            <div class="location-info__single-img">
                                <img src="{{ asset('assets/images/rejeppat/contact/email.jpg') }}" alt="Email">
                            </div>

                            <div class="location-info__single-content">
                                <div class="location-info__single-content-title">
                                    <h3>Email</h3>
                                </div>
                                <div class="location-info__single-content-text">
                                    @foreach (config('rejeppat.emails') as $email)
                                        <p><a href="mailto:{{ $email }}">{{ $email }}</a></p>
                                    @endforeach
                                </div>
                                <div class="location-info__single-content-btn">
                                    <a href="mailto:{{ config('rejeppat.emails.0') }}">Nous écrire <span class="icon-arrow"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Location Info Single-->

                    <!--Start Location Info Single-->
                    <div class="col-xl-4 col-lg-4">
                        <div class="location-info__single">
                            <div class="location-info__single-img">
                                <img src="{{ asset('assets/images/rejeppat/contact/telephone.jpg') }}" alt="Téléphone">
                            </div>

                            <div class="location-info__single-content">
                                <div class="location-info__single-content-title">
                                    <h3>Numéro de téléphone</h3>
                                </div>
                                <div class="location-info__single-content-text">
                                    @foreach (config('rejeppat.phones') as $phone)
                                        <p><a href="tel:{{ $phone['tel'] }}">{{ $phone['label'] }}</a></p>
                                    @endforeach
                                </div>
                                <div class="location-info__single-content-btn">
                                    <a href="https://wa.me/{{ config('rejeppat.whatsapp') }}" target="_blank" rel="noopener">Écrire sur WhatsApp <span class="icon-arrow"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Location Info Single-->

                    <!--Start Location Info Single-->
                    <div class="col-xl-4 col-lg-4">
                        <div class="location-info__single">
                            <div class="location-info__single-img">
                                <img src="{{ asset('assets/images/rejeppat/contact/adresse.jpg') }}" alt="Adresse">
                            </div>

                            <div class="location-info__single-content">
                                <div class="location-info__single-content-title">
                                    <h3>Adresse</h3>
                                </div>
                                <div class="location-info__single-content-text">
                                    <p>{{ config('rejeppat.address') }}</p>
                                    <p>{{ config('rejeppat.po_box') }}</p>
                                </div>
                                <div class="location-info__single-content-btn">
                                    <a href="{{ $carte }}" target="_blank" rel="noopener">Voir sur la carte <span class="icon-arrow"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Location Info Single-->
                </div>
            </div>
        </section>
        <!--End Location Info -->

        <!-- Start Smiling Fields -->
        <section class="smiling-fields">
            <div class="section-top-shape" style="background-image: url({{ asset('assets/images/shapes/section-top-shape.png') }});">
            </div>
            <div class="smiling-fields__bg"
                style="background-image: url({{ asset('assets/images/backgrounds/smiling-fields__bg.jpg') }});">
            </div>
            <div class="container">
                <div class="smiling-fields__content">
                    <div class="sec-title-two sec-title-animation animation-style2">
                        <div class="sub-title">
                            <div class="icon">
                                <img src="{{ asset('assets/images/icon/sec-title-two-icon2.png?v=vert') }}" alt="Icon">
                            </div>
                            <h4>Achetez local et frais</h4>
                        </div>
                        <h2 class="title-animation">Des produits biologiques et naturels.</h2>
                    </div>
                    <div class="smiling-fields__content-text">
                        <p>Directement chez les producteurs du réseau REJEPPAT.</p>
                    </div>
                    <div class="smiling-fields__content-btn">
                        <a class="btn-one" href="{{ route('boutique.index') }}">
                            <i class="icon-arrow"></i>
                            <span class="txt">Accéder à la boutique</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Smiling Fields -->


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
                            <h4>Contactez-nous</h4>
                        </div>
                        <h2 class="title-animation">Envoyez-nous un message</h2>
                    </div>
                    <div class="row">
                        <!--Start Main Contact Form-->
                        <div class="contact-form">
                            @if (session('succes'))
                                <div class="alert alert-success">{{ session('succes') }}</div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">Veuillez corriger les champs signalés.</div>
                            @endif

                            <form id="contact-form-rejeppat" class="default-form2" action="{{ route('contact.envoyer') }}" method="post">
                                @csrf

                                <div class="row">
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="text" name="nom" placeholder="Votre nom *" required value="{{ old('nom') }}">
                                            @error('nom') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="text" name="telephone" placeholder="Numéro de téléphone" value="{{ old('telephone') }}">
                                            @error('telephone') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <input type="email" name="email" placeholder="Adresse e-mail *" required value="{{ old('email') }}">
                                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="input-box">
                                            <div class="select-box clearfix">
                                                <select class="wide" name="objet">
                                                    @foreach (config('rejeppat.objets_contact') as $objet)
                                                        <option value="{{ $objet }}" @selected(old('objet') === $objet)>{{ $objet }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('objet') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="input-box">
                                            <textarea name="message" placeholder="Votre message..." required>{{ old('message') }}</textarea>
                                            @error('message') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="checked-box1">
                                    <input type="checkbox" name="accord" id="contact" checked="" required>
                                    <label for="contact">
                                        <span></span>J’accepte que mes informations soient utilisées pour répondre à ma demande.
                                    </label>
                                </div>
                                <div class="btn-box">
                                    <button type="submit">
                                        Envoyer votre message
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


        <!--Start Map Style1-->
        <section class="map-style1">
            <div class="map-style1__content text-center">
                <div class="title">
                    <h3>Sokodé</h3>
                    <p>Route Nationale N°1, Quartier Ataworo <br>{{ config('rejeppat.po_box') }}</p>
                </div>
                <div class="btn-box">
                    <a href="{{ $carte }}" target="_blank" rel="noopener">
                        <i class="icon-thin-arrow"></i>
                        Voir sur la carte
                    </a>
                </div>
                <div class="phone-box">
                    <div class="icon">
                        <i class="icon-phone"></i>
                    </div>
                    <div class="number">
                        <h3><a href="tel:{{ config('rejeppat.phones.0.tel') }}">{{ config('rejeppat.phones.0.label') }}</a></h3>
                    </div>
                </div>
            </div>
            <!--Google Map Start-->
            <div class="google-map">
                <iframe src="{{ $carte }}&output=embed" class="contact-page__map-box" allowfullscreen loading="lazy"
                    title="Localisation du REJEPPAT à Sokodé"></iframe>
            </div>
            <!--Google Map End-->
        </section>
        <!--End Map Style1-->

@endsection
