        <!-- Start footer Style3 -->
        <footer class="footer-style4" id="contact">
            <div class="footer-style4__shape-1">
                <img src="{{ asset('assets/images/shapes/footer-style4-shape-1.png') }}?v=vert" alt="">
            </div>
            <div class="footer-style4__shape-2">
                <img src="{{ asset('assets/images/shapes/footer-style4-shape-2.png') }}?v=vert" alt="">
            </div>
            <!--Start Footer Main-->
            <div class="footer-main-style4">
                <div class="container">
                    <div class="row">

                        <!--Start Single Footer Widget-->
                        <div class="col-xl-6 single-widget">
                            <div class="single-footer-widget-style4 single-footer-widget-style4__about">
                                <div class="footer-widget-style4-about">
                                    <div class="footer-widget-style4__logo">
                                        <a href="{{ route('home') }}"><img src="{{ asset('assets/images/rejeppat/logo/logo-rejeppat-officiel.png') }}"
                                                alt="{{ config('rejeppat.name') }}"></a>
                                    </div>
                                    <div class="footer-widget-style4__seasonal-offer">
                                        <h4 class="footer-widget-style4__seasonal-title">{!! __('Produits bio de nos producteurs ! <br> Profitez des prix réduits.') !!}</h4>
                                        <div class="owl-carousel owl-theme thm-owl__carousel footer-widget-style4__seasonal-offer-carousel owl-nav-style-one"
                                            data-owl-options='{
                                            "loop": true,
                                            "autoplay": true,
                                            "margin": 30,
                                            "nav": true,
                                            "dots": false,
                                            "smartSpeed": 500,
                                            "autoplayTimeout": 10000,
                                            "navText": ["<span class=\"left icon-arrow-right\"></span>","<span class=\"icon-arrow\"></span>"],
                                            "responsive": {
                                                    "0": {
                                                        "items": 1
                                                    },
                                                    "768": {
                                                        "items": 1
                                                    },
                                                    "992": {
                                                        "items": 1
                                                    },
                                                    "1200": {
                                                        "items": 1
                                                    }
                                                }
                                            }'>

                                            @foreach (\App\Support\Contenu::produits()->filter(fn ($produit) => $produit->reduction() > 0)->take(5) as $produit)
                                            <!-- Start Single Products Style4 -->
                                            <div class="footer-widget-style4__seasonal-offer-single">
                                                <div class="footer-widget-style4__seasonal-offer-single-inner">
                                                    <div class="footer-widget-style4__seasonal-offer-shape-1">
                                                        <img src="{{ asset('assets/images/shapes/footer-widget-style4-seasonal-offer-shape-1.png') }}?v=vert"
                                                            alt="">
                                                    </div>
                                                    <div class="content">
                                                        <p>{{ __('Promotion') }}</p>
                                                        <h4><a href="{{ route('boutique.show', $produit->slug) }}" style="color: inherit">{{ \Illuminate\Support\Str::limit($produit->tr('nom'), 28) }}</a></h4>
                                                        <h5>{{ __('Prix') }} : {{ \App\Support\Contenu::prix($produit->prix) }}</h5>
                                                    </div>
                                                    <div class="discount-box">
                                                        <h2>{{ $produit->reduction() }}%</h2>
                                                        <h6>{{ __('Réduction') }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Single Products Style4 -->
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--End Single Footer Widget-->
                        <!--Start Single Footer Widget-->
                        <div class="col-xl-3 col-lg-6 col-md-6 single-widget">
                            <div class="single-footer-widget-style4 single-footer-widget-style4__useful-links">
                                <div class="title">
                                    <h3>{{ __('Nous contacter') }}</h3>
                                    <div class="border-dot"></div>
                                </div>
                                <div class="footer-widget-useful-links-style4">
                                    <ul>
                                        @foreach (config('rejeppat.phones') as $phone)
                                            <li>
                                                <a href="tel:{{ $phone['tel'] }}">
                                                    <i class="icon-incoming-call"></i>
                                                    {{ $phone['label'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                        @foreach (config('rejeppat.emails') as $email)
                                            <li>
                                                <a href="mailto:{{ $email }}" style="text-transform: none;">
                                                    <i class="icon-mail"></i>
                                                    {{ $email }}
                                                </a>
                                            </li>
                                        @endforeach
                                        <li>
                                            <a href="https://wa.me/{{ config('rejeppat.whatsapp') }}" target="_blank" rel="noopener">
                                                <i class="icon-corn"></i>
                                                WhatsApp
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="icon-map-point"></i>
                                                {{ config('rejeppat.address') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="icon-map-point"></i>
                                                {{ config('rejeppat.po_box') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--End Single Footer Widget-->

                        <!--Start Single Footer Widget-->
                        <div class="col-xl-3 col-lg-6 col-md-6 single-widget">
                            <div class="single-footer-widget-style4 single-footer-widget-style4__newsletter">
                                <div class="title">
                                    <h3>{{ __('Newsletter') }}</h3>
                                    <div class="border-dot"></div>
                                </div>
                                <p class="single-footer-widget-style4__text">{{ __('Abonnez-vous à notre newsletter pour recevoir plus d’informations.') }}</p>
                                <form class="single-footer-widget-style4__newsletter-form" action="{{ route('newsletter.abonner') }}" method="post" id="newsletter">
                                    @csrf
                                    @if (session('newsletter'))
                                        <p class="single-footer-widget-style4__text" role="status" style="color: var(--thm-primary-color)">{{ session('newsletter') }}</p>
                                    @endif
                                    @if ($errors->newsletter->any())
                                        <p class="single-footer-widget-style4__text" role="alert" style="color: #ffd0c8">{{ $errors->newsletter->first() }}</p>
                                    @endif
                                    <div class="single-footer-widget-style4__newsletter-input">
                                        <input type="email" name="email" placeholder="{{ __('Adresse e-mail...') }}" required aria-label="{{ __('Adresse e-mail...') }}">
                                        <div class="single-footer-widget-style4__newsletter-input-icon">
                                            <span class="icon-mail"></span>
                                        </div>
                                    </div>
                                    <div class="checked-box">
                                        <input type="checkbox" name="accord" id="skipper" checked="" required>
                                        <label for="skipper"><span></span>{{ __('J’accepte de recevoir la newsletter du REJEPPAT.') }}</label>
                                    </div>
                                    <div class="single-footer-widget-style4__newsletter-btn">
                                        <button type="submit" class="btn-one">
                                            <i class="icon-arrow"></i>
                                            <span class="txt">{{ __('S’abonner') }}</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--End Single Footer Widget-->

                    </div>
                </div>
            </div>
            <!--End Footer Main-->

            <!--Start Footer Bottom-->
            <div class="footer-style4-bottom">
                <div class=" container">
                    <div class="footer-style4-bottom-inner">
                        <div class="copyright-text">
                            <p>
                                Copyrights © {{ date('Y') }} <a href="{{ route('home') }}">{{ config('rejeppat.name') }}.</a> {{ __('Tous droits réservés.') }}
                            </p>
                        </div>
                        <div class="footer-menu">
                            <ul class="clearfix">
                                <li>
                                    <a href="{{ route('home') }}#qui-sommes-nous">{{ __('Qui sommes-nous') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('home') }}#fermes-ecoles">{{ __('Fermes Écoles') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('home') }}#programmes-projets">{{ __('Programmes & Projets') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Footer Bottom-->



        </footer>
        <!-- End footer Style3 -->
