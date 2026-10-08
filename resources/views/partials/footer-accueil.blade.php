        <!-- Start footer Style3 -->
        <footer class="footer-style4" id="contact">
            <div class="footer-style4__shape-1">
                <img src="{{ asset('assets/images/shapes/footer-style4-shape-1.png?v=vert') }}" alt="">
            </div>
            <div class="footer-style4__shape-2">
                <img src="{{ asset('assets/images/shapes/footer-style4-shape-2.png?v=vert') }}" alt="">
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
                                        <h4 class="footer-widget-style4__seasonal-title">Produits bio de nos producteurs !
                                            <br>
                                            Profitez des prix réduits.</h4>
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

                                            <!-- Start Single Products Style4 -->
                                            <div class="footer-widget-style4__seasonal-offer-single">
                                                <div class="footer-widget-style4__seasonal-offer-single-inner">
                                                    <div class="footer-widget-style4__seasonal-offer-shape-1">
                                                        <img src="{{ asset('assets/images/shapes/footer-widget-style4-seasonal-offer-shape-1.png?v=vert') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="content">
                                                        <p>Promotion</p>
                                                        <h4><span>Carotte</span> biologique.</h4>
                                                        <h5>Prix : 2 500 FCFA</h5>
                                                    </div>
                                                    <div class="discount-box">
                                                        <h2>17%</h2>
                                                        <h6>Réduction</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Single Products Style4 -->
                                            <!-- Start Single Products Style4 -->
                                            <div class="footer-widget-style4__seasonal-offer-single">
                                                <div class="footer-widget-style4__seasonal-offer-single-inner">
                                                    <div class="footer-widget-style4__seasonal-offer-shape-1">
                                                        <img src="{{ asset('assets/images/shapes/footer-widget-style4-seasonal-offer-shape-1.png?v=vert') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="content">
                                                        <p>Promotion</p>
                                                        <h4><span>Oignon</span> biologique.</h4>
                                                        <h5>Prix : 33 FCFA</h5>
                                                    </div>
                                                    <div class="discount-box">
                                                        <h2>23%</h2>
                                                        <h6>Réduction</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Single Products Style4 -->
                                            <!-- Start Single Products Style4 -->
                                            <div class="footer-widget-style4__seasonal-offer-single">
                                                <div class="footer-widget-style4__seasonal-offer-single-inner">
                                                    <div class="footer-widget-style4__seasonal-offer-shape-1">
                                                        <img src="{{ asset('assets/images/shapes/footer-widget-style4-seasonal-offer-shape-1.png?v=vert') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="content">
                                                        <p>Promotion</p>
                                                        <h4><span>Tomates</span> bio.</h4>
                                                        <h5>Prix : 300 FCFA</h5>
                                                    </div>
                                                    <div class="discount-box">
                                                        <h2>40%</h2>
                                                        <h6>Réduction</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Single Products Style4 -->

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
                                    <h3>Nous contacter</h3>
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
                                    <h3>Newsletter</h3>
                                    <div class="border-dot"></div>
                                </div>
                                <p class="single-footer-widget-style4__text">Abonnez-vous à notre newsletter pour
                                    recevoir plus d’informations.</p>
                                <form class="single-footer-widget-style4__newsletter-form">
                                    <div class="single-footer-widget-style4__newsletter-input">
                                        <input type="email" placeholder="Adresse email...">
                                        <div class="single-footer-widget-style4__newsletter-input-icon">
                                            <span class="icon-mail"></span>
                                        </div>
                                    </div>
                                    <div class="checked-box">
                                        <input type="checkbox" name="skipper1" id="skipper" checked="">
                                        <label for="skipper"><span></span>J’accepte les conditions d’utilisation.</label>
                                    </div>
                                    <div class="single-footer-widget-style4__newsletter-btn">
                                        <button type="submit" class="btn-one">
                                            <i class="icon-arrow"></i>
                                            <span class="txt">S’abonner</span>
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
                                Copyrights © {{ date('Y') }} <a href="{{ route('home') }}">{{ config('rejeppat.name') }}.</a> Tous droits réservés.
                            </p>
                        </div>
                        <div class="footer-menu">
                            <ul class="clearfix">
                                <li>
                                    <a href="{{ route('home') }}#qui-sommes-nous">Qui sommes-nous</a>
                                </li>
                                <li>
                                    <a href="{{ route('home') }}#fermes-ecoles">Fermes Écoles</a>
                                </li>
                                <li>
                                    <a href="{{ route('home') }}#programmes-projets">Programmes &amp; Projets</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Footer Bottom-->



        </footer>
        <!-- End footer Style3 -->
