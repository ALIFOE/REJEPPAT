@extends('layouts.app')

@section('title', $membre->nom)

@php
    $whatsapp = preg_replace('/\D+/', '', (string) $membre->telephone);
    $whatsapp = strlen($whatsapp) === 8 ? '228' . $whatsapp : $whatsapp;
@endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => $membre->nom,
            'texte' => $membre->organisation ?: __('Membre du réseau REJEPPAT'),
            'image' => 'demande.jpg',
            'liens' => [__('Espace Jeunes') => route('espace-jeunes.index'), __('Annuaire') => route('espace-jeunes.annuaire')],
        ])

        <section class="rj-section">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="rj-profil">
                            <span class="rj-avatar rj-avatar--grand">
                                @if ($membre->photo)
                                    <img src="{{ $membre->photoUrl() }}" alt="{{ $membre->nom }}">
                                @else
                                    {{ $membre->initiales() }}
                                @endif
                            </span>
                            <div>
                                <h2>{{ $membre->nom }}</h2>
                                <ul class="rj-meta" style="margin-bottom: 12px">
                                    @if ($membre->organisation)<li><i class="fas fa-users"></i> {{ $membre->organisation }}</li>@endif
                                    @if ($membre->localite || $membre->region)<li><i class="icon-map-point"></i> {{ collect([$membre->localite, $membre->region ? __($membre->region->nom) : null])->filter()->implode(', ') }}</li>@endif
                                </ul>
                                <span class="rj-pastille">{{ $membre->filiereLibelle() }}</span>
                            </div>
                        </div>
                        <div class="rj-texte">
                            @foreach ($membre->tr('presentation') ?? [] as $paragraphe)
                                <p>{{ $paragraphe }}</p>
                            @endforeach
                        </div>
                        <p style="margin-top: 30px"><a class="rj-lien-fleche" href="{{ route('espace-jeunes.annuaire') }}"><i class="icon-arrow-right"></i> {{ __('Retour à l’annuaire') }}</a></p>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <aside class="rj-encadre">
                            <h3>{{ __('Contacter ce membre') }}</h3>
                            <ul>
                                @if ($membre->telephone)
                                    <li><i class="icon-incoming-call"></i> <a href="tel:{{ preg_replace('/[^\d+]/', '', $membre->telephone) }}">{{ $membre->telephone }}</a></li>
                                    <li><i class="fab fa-whatsapp"></i> <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener">WhatsApp</a></li>
                                @endif
                                @if ($membre->email)
                                    <li><i class="icon-mail"></i> <a href="mailto:{{ $membre->email }}">{{ $membre->email }}</a></li>
                                @endif
                                @unless ($membre->telephone || $membre->email)
                                    <li><i class="icon-mail"></i> <span>{{ __('Passez par le REJEPPAT pour être mis en relation :') }} <a href="{{ route('contact') }}">{{ __('nous contacter') }}</a></span></li>
                                @endunless
                            </ul>
                        </aside>
                        @if ($membre->region)
                            <aside class="rj-encadre">
                                <h3>{{ __('Section régionale') }}</h3>
                                <p style="margin: 0"><a class="rj-lien-fleche" href="{{ route('regions.show', $membre->region->slug) }}">{{ __($membre->region->nom) }} <i class="icon-arrow"></i></a></p>
                            </aside>
                        @endif
                    </div>
                </div>
            </div>
        </section>

@endsection
