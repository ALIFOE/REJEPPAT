        <!--Start footer Style1 -->
        <footer class="footer-style1">

            <!--Start Footer Main-->
            <div class="footer-main">
                <div class="container">
                    <div class="row">

                        <!--Start Single Footer Widget-->
                        <div class="col-xl-3 col-lg-6 col-md-6 single-widget">
                            <div class="single-footer-widget">
                                <div class="single-footer-widget-about">
                                    <div class="footer-logo-style1">
                                        <a href="{{ route('home') }}">
                                            <img src="{{ asset('assets/images/rejeppat/logo/logo-rejeppat-officiel.png') }}" alt="{{ config('rejeppat.name') }}">
                                        </a>
                                    </div>
                                    <div class="single-footer-widget-about-text">
                                        <p>{{ config('rejeppat.full_name') }}.</p>
                                    </div>
                                    <div class="single-footer-widget-about-certification">
                                        <div class="footer-widget-about-certification-logo">
                                            <a href="{{ route('home') }}">
                                                <img src="{{ asset('assets/images/rejeppat/footer/ctop.jpg') }}"
                                                    alt="CTOP">
                                            </a>
                                        </div>
                                        <div class="footer-widget-about-certification-title">
                                            <h4>Membre de la Coordination Togolaise des OP (CTOP).</h4>
                                            <p>Créé le : 10 juillet 2010</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--End Single Footer Widget-->

                        <!--Start Single Footer Widget-->
                        <div class="col-xl-3 col-lg-6 col-md-6 single-widget">
                            <div class="single-footer-widget">
                                <div class="title">
                                    <h3>Liens utiles</h3>
                                    <div class="border-dot"></div>
                                </div>
                                <div class="footer-widget-useful-links">
                                    <ul>
                                        <li><a href="{{ route('offres') }}"><i class="icon-corn"></i> Nos offres &amp; services</a></li>
                                        <li><a href="{{ route('fermes.index') }}"><i class="icon-corn"></i> Fermes Écoles</a></li>
                                        <li><a href="{{ route('projets.index') }}"><i class="icon-corn"></i> Nos Programmes &amp; Projets</a></li>
                                        <li><a href="{{ route('actualites.index') }}"><i class="icon-corn"></i> Actualités &amp; Événements</a></li>
                                        <li><a href="{{ route('boutique.index') }}"><i class="icon-corn"></i> Boutique</a></li>
                                        <li><a href="{{ route('contact') }}"><i class="icon-corn"></i> Contact</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--End Single Footer Widget-->

                        <!--Start Single Footer Widget-->
                        <div class="col-xl-3 col-lg-6 col-md-6 single-widget">
                            <div class="single-footer-widget">
                                <div class="title">
                                    <h3>Nous contacter</h3>
                                    <div class="border-dot"></div>
                                </div>
                                <div class="footer-widget-useful-links">
                                    <ul>
                                        @foreach (config('rejeppat.phones') as $phone)
                                            <li><a href="tel:{{ $phone['tel'] }}"><i class="icon-incoming-call"></i> {{ $phone['label'] }}</a></li>
                                        @endforeach
                                        <li><a href="mailto:{{ config('rejeppat.emails.0') }}" style="text-transform: none;"><i class="icon-mail"></i> {{ config('rejeppat.emails.0') }}</a></li>
                                        <li><a href="https://wa.me/{{ config('rejeppat.whatsapp') }}" target="_blank" rel="noopener"><i class="icon-corn"></i> WhatsApp</a></li>
                                        <li><a href="{{ route('contact') }}"><i class="icon-map-point"></i> {{ config('rejeppat.address_short') }}</a></li>
                                        <li><a href="{{ route('contact') }}"><i class="icon-map-point"></i> {{ config('rejeppat.po_box') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--End Single Footer Widget-->

                        <!--Start Single Footer Widget-->
                        <div class="col-xl-3 col-lg-6 col-md-6 single-widget">
                            <div class="single-footer-widget">
                                <div class="title">
                                    <h3>Nos actualités</h3>
                                    <div class="border-dot"></div>
                                </div>
                                <div class="footer-widget-blog-post">
                                    <ul class="footer-widget-blog-post-list">
                                        @foreach (\App\Support\Contenu::actualites()->take(2) as $article)
                                            <li class="footer-widget-blog-post-single">
                                                <div class="footer-widget-blog-post-img">
                                                    <img src="{{ asset('assets/images/rejeppat/actualites/' . $article['image'] . '-mini.jpg') }}" alt="{{ $article['title'] }}">
                                                </div>
                                                <div class="footer-widget-blog-post-content">
                                                    <div class="date">
                                                        <i class="fa fa-solid fa-calendar-day"></i>
                                                        <p>{{ \App\Support\Contenu::date($article['date']) }}</p>
                                                    </div>
                                                    <div class="title">
                                                        <h4>
                                                            <a href="{{ route('actualites.show', $article['slug']) }}">{{ \Illuminate\Support\Str::limit($article['title'], 60) }}</a>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--End Single Footer Widget-->

                    </div>
                </div>
            </div>
            <!--End Footer Main-->

            <!--Start Footer Bottom-->
            <div class="footer-bottom">
                <div class=" container">
                    <div class="footer-bottom-inner">
                        <div class="copyright-text">
                            <p>
                                Copyrights © {{ date('Y') }} <a href="{{ route('home') }}">{{ config('rejeppat.name') }}.</a> Tous droits réservés.
                            </p>
                        </div>
                        <div class="footer-menu">
                            <ul class="clearfix">
                                <li>
                                    <a href="{{ route('fermes.index') }}">Fermes Écoles</a>
                                </li>
                                <li>
                                    <a href="{{ route('demande') }}">Demande de service</a>
                                </li>
                                <li>
                                    <a href="{{ route('contact') }}">Contact</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Footer Bottom-->

        </footer>
        <!--End footer Style1-->
