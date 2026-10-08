        <header class="main-header main-header-style1">

            <div class="main-header-style1__content">
                <div class="container">
                    <div class="main-header-style1__content-inner">

                        <div class="main-header-style1__content-top">

                            <div class="main-header-style1__content-top-left">
                                <div class="icon-holder">
                                    <i class="icon-wheat"></i>
                                </div>
                                <div class="text-box">
                                    <p><span>{{ config('rejeppat.name') }} –</span> {{ config('rejeppat.tagline') }}</p>
                                </div>
                                <div class="btn-box">
                                    <a href="{{ route('offres') }}"><i class="icon-arrow"></i></a>
                                </div>
                            </div>
                            <div class="main-header-style1__content-top-right">
                                <div class="header-phone-style1">
                                    <div class="icon">
                                        <i class="icon-incoming-call"></i>
                                    </div>
                                    <div class="text">
                                        <p><a href="tel:{{ config('rejeppat.phones.0.tel') }}">{{ config('rejeppat.phones.0.label') }}</a> Une question ? Appelez-nous !</p>
                                    </div>
                                </div>
                                <div class="header-social-link-style1">
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
                                </div>
                            </div>
                        </div>

                        <div class="main-header-style1__content-bottom">
                            <div class="main-header-style1__content-bottom-left">
                                <div class="header-logo-box-style1">
                                    <a href="{{ route('home') }}">
                                        <img src="{{ asset('assets/images/rejeppat/logo/logo-rejeppat-officiel.png') }}" alt="{{ config('rejeppat.name') }}" title="">
                                    </a>
                                </div>

                                <!--Start Main Menu Style1-->
                                <nav class="main-menu main-menu-style1">
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

                                                <div class="header-btn-style1 header-sticky-btn-one">
                                                    <a class="btn-one" href="{{ route('demande') }}">
                                                        <i class="icon-arrow"></i>
                                                        <span class="txt">Demande de service</span>
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </nav>
                                <!--End Main Menu Style1-->
                            </div>

                            <div class="main-header-style1__content-bottom-right">
                                <div class="box-search-style1">
                                    <a href="#" class="search-toggler">
                                        <span class="icon-search"></span>
                                    </a>
                                </div>
                                <div class="header-cart-btn-style1">
                                    <div class="cart-icon">
                                        <a href="{{ route('boutique.index') }}" aria-label="Boutique"><span class="icon-empty-cart"></span></a>
                                    </div>
                                </div>
                                <div class="header-btn-style1">
                                    <a class="btn-one" href="{{ route('demande') }}">
                                        <i class="icon-arrow"></i>
                                        <span class="txt">Demande de service</span>
                                    </a>
                                </div>

                                <div class="header-address-style1">
                                    <div class="icon">
                                        <img src="{{ asset('assets/images/icon/address-icon1.png') }}" alt="Icon">
                                    </div>
                                    <div class="text">
                                        <h4>Sokodé, Togo</h4>
                                    </div>
                                    <a href="{{ route('contact') }}" class="btn-box1">
                                        <i class="icon-arrow"></i>
                                    </a>
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
