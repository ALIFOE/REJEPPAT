@extends('layouts.app')

@section('title', $evenement->tr('titre'))

@php
    $langue = app()->getLocale();
    $format = $langue === 'fr' ? 'l j F Y, H\hi' : 'l, F j, Y, g:i A';
    $estPasse = $evenement->debut->isPast() && (! $evenement->fin || $evenement->fin->isPast());
@endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => $evenement->typeLibelle(),
            'texte' => $evenement->lieu,
            'image' => 'actualites.jpg',
            'liens' => [__('Agenda') => route('agenda.index')],
            'actif' => \Illuminate\Support\Str::limit($evenement->tr('titre'), 40),
        ])

        <section class="rj-section">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        @if ($evenement->photo)
                            <img src="{{ $evenement->visuel() }}" alt="{{ $evenement->tr('titre') }}" style="width: 100%; border-radius: 10px; margin-bottom: 30px; max-height: 440px; object-fit: cover">
                        @endif
                        <span class="rj-pastille {{ $estPasse ? 'rj-pastille--gris' : '' }}">{{ $estPasse ? __('Événement passé') : $evenement->typeLibelle() }}</span>
                        <h2 style="margin: 14px 0 18px">{{ $evenement->tr('titre') }}</h2>
                        <div class="rj-texte">
                            @foreach ($evenement->tr('description') ?? [] as $paragraphe)
                                <p>{{ $paragraphe }}</p>
                            @endforeach
                        </div>
                        @if ($evenement->lien_inscription && ! $estPasse)
                            <a href="{{ $evenement->lien_inscription }}" class="btn-one" target="_blank" rel="noopener" style="margin-top: 20px"><i class="icon-arrow"></i><span class="txt">{{ __('S’inscrire') }}</span></a>
                        @endif
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <aside class="rj-encadre">
                            <h3>{{ __('Informations pratiques') }}</h3>
                            <ul>
                                <li><i class="far fa-clock"></i> <span>{{ $evenement->debut->locale($langue)->translatedFormat($format) }}@if ($evenement->fin)<br>{{ __('jusqu’au') }} {{ $evenement->fin->locale($langue)->translatedFormat($format) }}@endif</span></li>
                                <li><i class="icon-map-point"></i> <span>{{ $evenement->lieu }}</span></li>
                                <li><i class="icon-incoming-call"></i> <a href="tel:{{ config('rejeppat.phones.0.tel') }}">{{ config('rejeppat.phones.0.label') }}</a></li>
                                <li><i class="fab fa-whatsapp"></i> <a href="https://wa.me/{{ config('rejeppat.whatsapp') }}?text={{ rawurlencode(__('Bonjour, je souhaite des informations sur : :titre', ['titre' => $evenement->tr('titre')])) }}" target="_blank" rel="noopener">{{ __('Se renseigner sur WhatsApp') }}</a></li>
                            </ul>
                        </aside>
                        @if ($prochains->isNotEmpty())
                            <aside class="rj-encadre">
                                <h3>{{ __('Prochains rendez-vous') }}</h3>
                                <ul>
                                    @foreach ($prochains as $prochain)
                                        <li><i class="far fa-calendar-alt"></i> <a href="{{ route('agenda.show', $prochain->slug) }}">{{ $prochain->debut->locale($langue)->translatedFormat('j M') }} – {{ $prochain->tr('titre') }}</a></li>
                                    @endforeach
                                </ul>
                            </aside>
                        @endif
                    </div>
                </div>
            </div>
        </section>

@endsection
