{{-- Carte d'une ferme école (bloc « team » du template). Paramètre : $ferme --}}
@php
    $lien = route('fermes.show', $ferme['slug']);
@endphp
                    <!-- Start Single Team Style1 -->
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="single-team-style1">
                            <div class="single-team-style1__img">
                                <div class="single-team-style1__img-inner">
                                    <img src="{{ $ferme->visuel('') }}" alt="Ferme école {{ $ferme['nom'] }}">
                                </div>
                                <div class="single-team-style1__img-designation">
                                    <div class="single-team-style1__img-designation-inner">
                                        <span>{{ $ferme['specialite'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="single-team-style1__content">
                                <div class="single-team-style1__content-top">
                                    <h3><a href="{{ $lien }}">{{ $ferme['nom'] }}</a></h3>
                                    <p>{{ $ferme['localisation'] ?? 'Ferme école du réseau' }}</p>
                                </div>
                                <div class="single-team-style1__content-bottom">
                                    <div class="single-team-style1__content-bottom-btn">
                                        <a href="{{ $lien }}" class="btn-box1">
                                            <i class="icon-arrow"></i>
                                        </a>
                                        <a class="btn-one" href="{{ $lien }}">
                                            <i class="icon-arrow"></i>
                                            <span class="txt">Découvrir</span>
                                        </a>
                                    </div>
                                    <div class="single-team-style1__content-bottom-social">
                                        <ul>
                                            <li>
                                                <a href="{{ config('rejeppat.social.facebook') }}" target="_blank" rel="noopener">
                                                    <i class="icon-facebook"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ config('rejeppat.social.twitter') }}" target="_blank" rel="noopener">
                                                    <i class="icon-twitter"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Team Style1 -->
