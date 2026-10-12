@extends('layouts.app')

@section('title', __('Médiathèque'))

@php use App\Models\Media; @endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Médiathèque'),
            'texte' => __('Photos, vidéos et podcasts des activités du réseau.'),
            'image' => 'actualites.jpg',
            'liens' => [__('Actualités & Événements') => route('actualites.index')],
        ])

        <section class="rj-section">
            <div class="container">
                <div class="rj-filtres">
                    <a href="{{ route('mediatheque') }}" class="{{ $type ? '' : 'actif' }}">{{ __('Tout') }} <span>{{ $compteurs->sum() }}</span></a>
                    @foreach (Media::TYPES as $cle => $libelle)
                        <a href="{{ route('mediatheque', ['type' => $cle]) }}" class="{{ $type === $cle ? 'actif' : '' }}">
                            <i class="{{ ['photo' => 'far fa-image', 'video' => 'fab fa-youtube', 'podcast' => 'fas fa-podcast'][$cle] }}"></i>
                            {{ __($libelle) }} <span>{{ $compteurs[$cle] ?? 0 }}</span>
                        </a>
                    @endforeach
                </div>

                @if ($medias->isEmpty())
                    <div class="rj-vide">
                        <i class="fas fa-photo-video"></i>
                        <p>{{ __('La médiathèque sera bientôt alimentée. En attendant, retrouvez nos vidéos sur YouTube.') }}</p>
                        <a href="{{ config('rejeppat.social.youtube') }}" class="btn-one" target="_blank" rel="noopener"><i class="icon-arrow"></i><span class="txt">{{ __('Notre chaîne YouTube') }}</span></a>
                    </div>
                @else
                    <div class="rj-grille">
                        @foreach ($medias as $media)
                            <figure style="margin: 0">
                                @if ($media->type === 'photo')
                                    <a href="{{ $media->visuel() }}" class="rj-media" data-fancybox="mediatheque" data-caption="{{ $media->tr('titre') }}">
                                        <img src="{{ $media->visuel() }}" alt="{{ $media->tr('titre') }}" loading="lazy">
                                    </a>
                                @elseif ($media->estVideoProtegee())
                                    @include('partials.lecteur-video', ['media' => $media, 'titre' => false])
                                @elseif ($media->type === 'video')
                                    <a href="https://www.youtube.com/watch?v={{ $media->youtubeId() }}" class="rj-media" data-fancybox="mediatheque-videos" data-caption="{{ $media->tr('titre') }}">
                                        <img src="{{ $media->visuel() }}" alt="{{ $media->tr('titre') }}" loading="lazy">
                                        <span class="rj-media__lecture"><i class="fas fa-play"></i></span>
                                    </a>
                                @else
                                    <div class="rj-podcast">
                                        <a href="{{ $media->url ?: $media->fichierUrl() }}" class="rj-media" target="_blank" rel="noopener">
                                            <img src="{{ $media->visuel() }}" alt="{{ $media->tr('titre') }}" loading="lazy">
                                            <span class="rj-media__lecture"><i class="fas fa-podcast"></i></span>
                                        </a>
                                        @if ($media->fichier)
                                            <audio controls preload="none" src="{{ $media->fichierUrl() }}"></audio>
                                        @endif
                                    </div>
                                @endif
                                <figcaption class="rj-media__legende">
                                    <strong>{{ $media->tr('titre') }}</strong>
                                    <small>{{ \App\Support\Contenu::date($media->date, 'j F Y') }}@if ($media->tr('description')) · {{ \Illuminate\Support\Str::limit($media->tr('description'), 90) }}@endif</small>
                                </figcaption>
                            </figure>
                        @endforeach
                    </div>
                    <div class="rj-pagination">{{ $medias->links('partials.pagination') }}</div>
                @endif
            </div>
        </section>

@endsection
