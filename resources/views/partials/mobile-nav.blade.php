    <div class="mobile-nav__wrapper">
        <div class="mobile-nav__overlay mobile-nav__toggler"></div>
        <div class="mobile-nav__content">
            <span class="mobile-nav__close mobile-nav__toggler">
                <i class="fa fa-times-circle"></i>
            </span>
            <div class="logo-box">
                <a href="{{ route('home') }}" aria-label="logo image">
                    <img src="{{ asset('assets/images/rejeppat/logo/logo-rejeppat-officiel.png') }}" alt="{{ config('rejeppat.name') }}" />
                </a>
            </div>
            <div class="mobile-nav-search-box">
                <form class="search-form" action="{{ route('actualites.index') }}">
                    <input placeholder="Mot-clé" type="text" name="q" />
                    <button type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="mobile-nav__container"></div>
            <ul class="mobile-nav__contact list-unstyled">
                <li>
                    <i class="fa fa-envelope"></i>
                    <a href="mailto:{{ config('rejeppat.emails.0') }}">{{ config('rejeppat.emails.0') }}</a>
                </li>
                <li>
                    <i class="fa fa-phone-alt"></i>
                    <a href="tel:{{ config('rejeppat.phones.0.tel') }}">{{ config('rejeppat.phones.0.label') }}</a>
                </li>
            </ul>
            <div class="mobile-nav__social">
                <a href="{{ config('rejeppat.social.twitter') }}" class="fab fa-twitter" target="_blank" rel="noopener"></a>
                <a href="{{ config('rejeppat.social.facebook') }}" class="fab fa-facebook-square" target="_blank" rel="noopener"></a>
                <a href="{{ config('rejeppat.social.linkedin') }}" class="fab fa-linkedin-in" target="_blank" rel="noopener"></a>
                <a href="{{ config('rejeppat.social.youtube') }}" class="fab fa-youtube" target="_blank" rel="noopener"></a>
            </div>
        </div>
    </div>


    <!--Start Search Popup -->
    <div class="search-popup">
        <div class="search-popup__overlay search-toggler">
            <div class="search-popup__close-btn">
                <span class="fa fa-times"></span>
            </div>
        </div>
        <div class="search-popup__content">
            <form action="{{ route('actualites.index') }}">
                <label for="search" class="sr-only">Rechercher</label>
                <input type="text" id="search" name="q" placeholder="Rechercher..." />
                <button type="submit" aria-label="Lancer la recherche" class="thm-btn">
                    <i class="icon-search"></i>
                </button>
            </form>
        </div>
    </div>
    <!--End Search Popup -->


    <!--Scroll to top-->
    <div class="scroll-to-top">
        <div>
            <div class="scroll-top-inner">
                <div class="scroll-bar">
                    <div class="bar-inner"></div>
                </div>
                <div class="scroll-bar-text">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Scroll to top End -->
