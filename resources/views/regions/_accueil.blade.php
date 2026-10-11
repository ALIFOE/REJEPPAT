{{--
    Sections régionales mises en avant sur l'accueil : un onglet par région avec le ou les gouverneurs,
    les activités et informations à venir (texte saisi + activités de l'agenda rattachées à la région)
    et la liste à jour des fermes écoles de la section.
    Paramètre : $regions (avec les événements à venir et les fermes publiées chargés)
--}}
@if ($regions->isNotEmpty())
<section class="rj-section rj-regions-accueil" id="sections-regionales">
    <div class="container">
        <div class="sec-title withtext text-center">
            <div class="sub-title">
                <div class="icon"><i class="icon-hat"></i></div>
                <h4>{{ __('Le réseau en région') }}</h4>
            </div>
            <h2>{{ __('Nos sections régionales') }}</h2>
            <div class="text">
                <p>{{ __('Activités, informations à venir et gouverneurs de chacune de nos 5 sections régionales.') }}</p>
            </div>
        </div>

        <div class="rj-regions-onglets" role="tablist" aria-label="{{ __('Sections régionales') }}">
            @foreach ($regions as $region)
                <button type="button" role="tab" id="onglet-region-{{ $region->slug }}" aria-controls="panneau-region-{{ $region->slug }}"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}" @class(['actif' => $loop->first]) data-onglet-region>
                    <i class="icon-map-point"></i> {{ __($region->nom) }}
                </button>
            @endforeach
        </div>

        @foreach ($regions as $region)
            @php
                $gouverneurs = $region->listeGouverneurs();
                $activites = $region->tr('activites') ?? [];
                $evenements = $region->evenements->take(3);
            @endphp
            <div class="rj-region-panneau" role="tabpanel" id="panneau-region-{{ $region->slug }}" aria-labelledby="onglet-region-{{ $region->slug }}" @unless ($loop->first) hidden @endunless>
                <div class="rj-region-panneau__visuel" style="background-image: url({{ $region->visuel() }})">
                    <div class="rj-region-panneau__titre">
                        <h3>{{ __($region->nom) }}</h3>
                        <ul class="rj-meta">
                            @if ($region->chef_lieu)<li><i class="icon-map-point"></i> {{ $region->chef_lieu }}</li>@endif
                            <li><i class="icon-farm-house-1"></i> {{ trans_choice(':count ferme école|:count fermes écoles', $region->fermes->count()) }}</li>
                            @if ($region->nb_cooperatives)<li><i class="fas fa-users"></i> {{ trans_choice(':count coopérative|:count coopératives', $region->nb_cooperatives) }}</li>@endif
                        </ul>
                        <a href="{{ route('regions.show', $region->slug) }}" class="rj-bouton-petit">{{ __('Découvrir la section') }} <i class="icon-arrow"></i></a>
                    </div>
                </div>

                <div class="rj-region-panneau__bloc">
                    <h4><i class="fas fa-user-tie"></i> {{ trans_choice('Gouverneur de la section|Gouverneurs de la section', max(1, count($gouverneurs))) }}</h4>
                    @forelse ($gouverneurs as $gouverneur)
                        <div class="rj-gouverneur">
                            <span class="rj-avatar">
                                @if ($gouverneur['photo_url'])
                                    <img src="{{ $gouverneur['photo_url'] }}" alt="{{ $gouverneur['nom'] }}" loading="lazy">
                                @else
                                    {{ $gouverneur['initiales'] }}
                                @endif
                            </span>
                            <div>
                                <strong>{{ $gouverneur['nom'] }}</strong>
                                <span>{{ $gouverneur['fonction'] ?: __('Gouverneur de la section') }}</span>
                                @if ($gouverneur['telephone'])
                                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $gouverneur['telephone']) }}"><i class="icon-incoming-call"></i> {{ $gouverneur['telephone'] }}</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="rj-region-panneau__vide">{{ __('Le gouverneur de la section sera bientôt présenté.') }}</p>
                    @endforelse
                </div>

                <div class="rj-region-panneau__bloc">
                    <h4><i class="far fa-calendar-alt"></i> {{ __('Activités et informations à venir') }}</h4>
                    @if ($evenements->isEmpty() && empty($activites))
                        <p class="rj-region-panneau__vide">{{ __('Les prochaines activités de la section seront annoncées ici.') }}</p>
                    @else
                        <ul class="rj-region-activites">
                            @foreach ($evenements as $evenement)
                                <li>
                                    <span class="rj-region-activites__date">{{ $evenement->debut->locale(app()->getLocale())->translatedFormat('d M') }}</span>
                                    <a href="{{ route('agenda.show', $evenement->slug) }}">{{ $evenement->tr('titre') }}</a>
                                    <small>{{ $evenement->typeLibelle() }} · {{ $evenement->lieu }}</small>
                                </li>
                            @endforeach
                            @foreach ($activites as $activite)
                                <li>
                                    <span class="rj-region-activites__date"><i class="fas fa-bullhorn"></i></span>
                                    <span>{{ $activite }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="rj-region-panneau__fermes">
                    <h4><i class="icon-farm-house-1"></i> {{ __('Fermes écoles de la section') }}</h4>
                    @forelse ($region->fermes as $ferme)
                        <a href="{{ route('fermes.show', $ferme->slug) }}" class="rj-region-ferme">
                            <img src="{{ $ferme->visuel() }}" alt="" loading="lazy">
                            <span><strong>{{ $ferme->nom }}</strong><small>{{ $ferme->tr('specialite') }}</small></span>
                        </a>
                    @empty
                        <p class="rj-region-panneau__vide">{{ __('Aucune ferme école n’est encore rattachée à cette section.') }}</p>
                    @endforelse
                </div>
            </div>
        @endforeach

        <p class="text-center" style="margin-top: 40px">
            <a class="btn-one" href="{{ route('regions.index') }}"><i class="icon-arrow"></i><span class="txt">{{ __('Toutes les sections régionales') }}</span></a>
        </p>
    </div>
</section>

@push('scripts')
    <script>
        // Onglets des sections régionales (accueil)
        (function () {
            var onglets = document.querySelectorAll('[data-onglet-region]');
            onglets.forEach(function (onglet) {
                onglet.addEventListener('click', function () {
                    onglets.forEach(function (autre) {
                        var actif = autre === onglet;
                        autre.classList.toggle('actif', actif);
                        autre.setAttribute('aria-selected', actif ? 'true' : 'false');
                        document.getElementById(autre.getAttribute('aria-controls')).hidden = !actif;
                    });
                });
            });
        })();
    </script>
@endpush
@endif
