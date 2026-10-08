<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', config('rejeppat.tagline')) || {{ config('rejeppat.name') }}</title>
    <!-- Favicons Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/rejeppat/favicons/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/rejeppat/favicons/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/rejeppat/favicons/favicon-16x16.png') }}" />
    <meta name="description" content="@yield('description', config('rejeppat.full_name') . ' (REJEPPAT) : faîtière des organisations paysannes de jeunes, créée le 10 juillet 2010, pour une agriculture durable et inclusive au Togo.')" />

    <link rel="stylesheet" href="{{ asset('assets/vendors/animate/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/animate/custom-animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/aos/aos.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-touchspin/jquery.bootstrap-touchspin.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/fancybox/fancybox.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/jarallax/jarallax.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/jquery-magnific-popup/jquery.magnific-popup.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/jquery-ui/jquery-ui.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/nice-select/nice-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/odometer/odometer.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel/owl.theme.default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/swiper/swiper.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/thm-icons/style.css') }}" />
    <!-- Module css -->
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/01-header-section.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/02-banner-section.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/03-about-section.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/04-fact-counter-section.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/05-testimonial-section.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/06-partner-section.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/07-footer-section.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/08-blog-section.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/09-breadcrumb-section.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/10-contact.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/11-services-section.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/12-shop.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/13-team-section.css') }}" />
    <!-- Template styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/rejeppat.css') }}" />

</head>

<body class="@yield('body_class', 'body-bg-1')">

    <!-- Preloader -->
    <div class="loader-wrap">
        <div class="preloader">
            <div id="handle-preloader" class="handle-preloader">
                <div class="layer layer-one">
                    <span class="overlay"></span>
                </div>
                <div class="layer layer-three">
                    <span class="overlay"></span>
                </div>
                <div class="layer layer-two">
                    <span class="overlay"></span>
                </div>
                <div class="animation-preloader">
                    <div class="spinner"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader End -->

    <!-- Cursor -->
    <div class="cursor"></div>
    <div class="cursor-follower"></div>
    <!-- Cursor End -->



    <div class="page-wrapper boxed_wrapper">

        @include(($accueil ?? false) ? 'partials.header-accueil' : 'partials.header')

        @yield('content')

        @include(($accueil ?? false) ? 'partials.footer-accueil' : 'partials.footer')

    </div>
    <!-- /.page-wrapper -->

    @include('partials.mobile-nav')



    <script src="{{ asset('assets/vendors/jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-touchspin/jquery.bootstrap-touchspin.js') }}"></script>
    <script src="{{ asset('assets/vendors/countdown/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/fancybox/jquery.fancybox.js') }}"></script>
    <script src="{{ asset('assets/vendors/isotope/isotope.js') }}"></script>
    <script src="{{ asset('assets/vendors/jarallax/jarallax.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-ajaxchimp/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-appear/jquery.appear.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-magnific-popup/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/nice-select/jquery.nice-select.min.js') }}"></script>
    <script>
        // Compteurs sans séparateur de milliers (ex. 2010, 15751)
        window.odometerOptions = { format: 'd' };
    </script>
    <script src="{{ asset('assets/vendors/odometer/odometer.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/owl-carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/swiper/swiper.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/wow/wow.js') }}"></script>


    <!-- Gsap JS files -->
    <script src="{{ asset('assets/vendors/gsap/gsap.js') }}"></script>
    <script src="{{ asset('assets/vendors/gsap/ScrollTrigger.js') }}"></script>
    <script src="{{ asset('assets/vendors/gsap/SplitText.js') }}"></script>

    <script src="{{ asset('assets/vendors/extra-scripts/extra-scripts.js') }}"></script>
    <script src="{{ asset('assets/vendors/marquee/marquee.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/curved-text/jquery.circleType.js') }}"></script>
    <script src="{{ asset('assets/vendors/curved-text/jquery.lettering.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/curved-text/jquery.fittext.js') }}"></script>


    <!-- Template js -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    @stack('scripts')


</body>

</html>