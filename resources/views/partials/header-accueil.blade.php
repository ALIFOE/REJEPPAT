        <header class="main-header main-header-style4">

            <div class="main-header-style4__content">
                <div class="container">
                    <div class="main-header-style4__content-inner">
                        <div class="main-header-logo-style4">
                            <a href="{{ route('home') }}">
                                <div class="main-header-logo-style4__shape"
                                    style="background-image: url({{ asset('assets/images/shapes/main-header-logo-style4-shape.png?v=vert') }});">
                                </div>
                                <img src="{{ asset('assets/images/rejeppat/logo/logo-rejeppat-officiel.png') }}" alt="{{ config('rejeppat.name') }}">
                            </a>
                        </div>

                        <div class="main-header-style4__content-top">
                            <div class="main-header-style4__content-top-inner">
                                <div class="main-header-style4__content-top-left">
                                    <ul class="main-header-style4__top-left-content">
                                        <li>
                                            <div class="icon">
                                                <i class="icon-wheat"></i>
                                            </div>
                                            <div class="text">
                                                <p><a href="{{ route('offres') }}">Nos offres &amp; services</a>
                                                </p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <i class="icon-arrow"></i>
                                            </div>
                                            <div class="text">
                                                <p><a href="{{ route('demande') }}">Faire une demande de service</a></p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="main-header-style4__content-top-right">
                                    <div class="main-header-style4__social-search-and-cart-box">
                                        <ul class="main-header-style4__social">
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
                                            <li>
                                                <a href="{{ config('rejeppat.social.linkedin') }}" target="_blank" rel="noopener">
                                                    <i class="fab fa-linkedin-in"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ config('rejeppat.social.youtube') }}" target="_blank" rel="noopener">
                                                    <i class="icon-youtube"></i>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="main-header-style4__search-box">
                                            <a href="#" class="search-toggler">
                                                Rechercher
                                                <span class="icon-search"></span>
                                            </a>
                                        </div>
                                        <div class="main-header-style4__cart-box">
                                            <a href="{{ route('boutique.index') }}">
                                                <span class="icon-empty-cart"></span>
                                                Panier
                                                <div class="main-header-style4__cart-count">(0)</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="main-header-style4__content-contact-info-box">
                            <ul class="main-header-style4__content-contact-info-list">
                                <li>
                                    <div class="icon">
                                        <span class="icon-incoming-call"></span>
                                    </div>
                                    <div class="content">
                                        <h4>Téléphone</h4>
                                        <p><a href="tel:{{ config('rejeppat.phones.0.tel') }}">{{ config('rejeppat.phones.0.label') }}</a></p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <span class="icon-map-point"></span>
                                    </div>
                                    <div class="content">
                                        <h4>Adresse</h4>
                                        <p>{{ config('rejeppat.address_short') }}</p>
                                    </div>
                                </li>
                            </ul>
                            <ul class="main-header-style4__content-contact-info-list two">
                                <li>
                                    <div class="icon">
                                        <span class="icon-mail"></span>
                                    </div>
                                    <div class="content">
                                        <h4>Email</h4>
                                        <p><a href="mailto:{{ config('rejeppat.emails.0') }}">{{ config('rejeppat.emails.0') }}</a></p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <span class="icon-map-point"></span>
                                    </div>
                                    <div class="content">
                                        <h4>Boîte postale</h4>
                                        <p>{{ config('rejeppat.po_box') }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="main-header-style4__content-bottom">
                            <div class="container">
                                <div class="main-header-style4__content-bottom-inner">
                                    <div class="main-header-style4__content-bottom-middle">

                                        <!--Start Main Menu Style1-->
                                        <nav class="main-menu main-menu-style4">
                                            <div class="main-menu__wrapper clearfix">
                                                <div class="main-menu__wrapper-inner">
                                                    <div class="sticky-logo-box-style1">
                                                        <a href="{{ route('home') }}">
                                                            <img src="{{ asset('assets/images/rejeppat/logo/logo-rejeppat-officiel.png') }}"
                                                                alt="{{ config('rejeppat.name') }}" title="">
                                                        </a>
                                                    </div>
                                                    <div class="main-menu-style1__left">
                                                        <div class="main-menu-box">
                                                            <a href="#" class="mobile-nav__toggler">
                                                                <i class="fa fa-bars"></i>
                                                            </a>

                                                            @include('partials.menu')

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </nav>
                                        <!--End Main Menu Style1-->
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </header>


        <div class="stricky-header stricky-header--style1 stricked-menu main-menu">
            <div class="sticky-header__content"></div>
            <!-- /.sticky-header__content -->
        </div>
        <!-- /.stricky-header -->
