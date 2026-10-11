{{--
    Section « Le REJEPPAT en vidéo » de l'accueil, juste après les sections régionales.
    Paramètre : $videos (vidéos envoyées dans la médiathèque et mises en avant sur l'accueil).
    La première vidéo est dans le lecteur ; les suivantes s'y chargent depuis la liste.
--}}
@if ($videos->isNotEmpty())
@php $principale = $videos->first(); @endphp
<section class="rj-videos-accueil" id="videos">
    <div class="container">
        <div class="sec-title withtext text-center white">
            <div class="sub-title">
                <div class="icon"><i class="icon-hat"></i></div>
                <h4>{{ __('En vidéo') }}</h4>
            </div>
            <h2>{{ __('Le REJEPPAT en vidéo') }}</h2>
            <div class="text">
                <p>{{ __('Nos activités, nos fermes écoles et nos jeunes producteurs en images.') }}</p>
            </div>
        </div>

        <div class="rj-videos-accueil__grille {{ $videos->count() === 1 ? 'rj-videos-accueil__grille--seule' : '' }}">
            <div>
                @include('partials.lecteur-video', ['media' => $principale, 'id' => 'lecteur-accueil-video'])
                @if ($videos->count() === 1 && $principale->tr('description'))
                    <p class="rj-videos-accueil__description">{{ $principale->tr('description') }}</p>
                @endif
            </div>

            @if ($videos->count() > 1)
                <ul class="rj-videos-liste" data-liste-videos="lecteur-accueil-video">
                    @foreach ($videos as $video)
                        <li>
                            <button type="button" @class(['actif' => $loop->first]) data-video-source="{{ $video->lienLecture() }}"
                                data-video-affiche="{{ $video->affiche() }}" data-video-titre="{{ $video->tr('titre') }}">
                                <span class="rj-videos-liste__vignette">
                                    @if ($video->affiche())<img src="{{ $video->affiche() }}" alt="" loading="lazy">@endif
                                    <i class="fas fa-play-circle"></i>
                                </span>
                                <span>
                                    <strong>{{ $video->tr('titre') }}</strong>
                                    <small>{{ \App\Support\Contenu::date($video->date, 'j F Y') }}</small>
                                </span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <p class="text-center" style="margin-top: 40px">
            <a class="btn-one" href="{{ route('mediatheque', ['type' => 'video']) }}"><i class="icon-arrow"></i><span class="txt">{{ __('Toutes nos vidéos') }}</span></a>
        </p>
    </div>
</section>
@endif
