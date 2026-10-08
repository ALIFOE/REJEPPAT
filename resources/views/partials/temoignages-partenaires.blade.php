        <!-- Start Testimonials & Partners -->
        <section class="testimonials-partners">
            <div class="testimonials-partners__bg"
                style="background-image: url({{ asset('assets/images/backgrounds/testimonials-partners__bg.jpg') }});">
            </div>
            <!-- Start Testimonial Style1-->
            <div class="testimoial-style1">
                <div class="container">
                    <div class="testimoial-style1__inner">

                        <div class="testimonial-slider-control-wrap">
                            <div class="swiper-counter wow slideInUp" data-wow-delay="1500ms">
                                <div id="current">01</div>
                                <div id="total"></div>
                            </div>
                        </div>
                        <div class="testimonial-slider-slider-nav">
                            <div class="testimonial-slider-button-prev">
                                <span><i class="icon-arrow-right" aria-hidden="true"></i></span>
                            </div>
                            <div class="testimonial-slider-button-next">
                                <span><i class="icon-arrow" aria-hidden="true"></i></span>
                            </div>
                        </div>

                        <!-- Start Single Testimonial Style1-->
                        <div class="single-testimoial-style1">
                            <div class="row">
                                @foreach ([1, 2] as $cote)
                                    @if ($cote === 2)
                                <div class="col-xl-8 col-lg-8 col-md-8">

                                    <div class="swiper-container testimonial-slider">

                                        <div class="swiper-wrapper">
                                            @foreach (config('rejeppat.temoignages') as $temoignage)
                                            <!--Start Single Testimoial Style1 Item -->
                                            <div class="swiper-slide">
                                                <div class="single-testimoial-style1-item">
                                                    <div class="testimoial-style1__content">
                                                        <div class="testimoial-style1__content-icon">
                                                            <div class="testimoial-style1__content-icon-bg"
                                                                style="background-image: url({{ asset('assets/images/backgrounds/testimoial-v1-icon__bg.png') }});">
                                                            </div>
                                                            <i class="icon-dialog"></i>
                                                            <div class="testimoial-style1__content-round-text">
                                                                Témoignages
                                                            </div>
                                                        </div>
                                                        <div class="testimoial-style1__content-inner">
                                                            <div class="testimoial-style1__content-inner-bg"
                                                                style="background-image: url({{ asset('assets/images/pattern/testimoial-v1__pattern.jpg?v=vert') }});">
                                                            </div>
                                                            <div class="testimoial-style1__content-top">
                                                                <h3>{{ $temoignage['titre'] }}</h3>
                                                                <p>{{ $temoignage['texte'] }}</p>
                                                            </div>
                                                            <div class="testimoial-style1__content-shape">
                                                                <img src="{{ asset('assets/images/shapes/activities-v1-fact__shape1.png') }}"
                                                                    alt="Image">
                                                            </div>
                                                            <div class="testimoial-style1__content-bottom">
                                                                <p><span>{{ $temoignage['nom'] }}, </span>{{ $temoignage['role'] }}</p>
                                                                <div class="reting">
                                                                    <i class="icon-rate-star-button"></i>
                                                                    <p>Bénéficiaire</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--End Single Testimoial Style1 Item -->
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                                    @endif

                                <div class="col-xl-2 col-lg-2 col-md-2">
                                    <div class="single-testimoial-style1__img">
                                        <div class="single-testimoial-style1__img-inner">
                                            <img src="{{ asset('assets/images/rejeppat/offres/temoignage-' . $cote . '.jpg') }}" alt="Jeunes producteurs du REJEPPAT">
                                            <ul class="one">
                                                <li></li>
                                                <li></li>
                                                <li></li>
                                                <li></li>
                                            </ul>
                                            <ul class="one two">
                                                <li></li>
                                                <li></li>
                                                <li></li>
                                                <li></li>
                                            </ul>
                                            <ul class="one three">
                                                <li></li>
                                                <li></li>
                                                <li></li>
                                                <li></li>
                                            </ul>
                                            <ul class="one four">
                                                <li></li>
                                                <li></li>
                                                <li></li>
                                                <li></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- End Single Testimonial Style1-->
                    </div>
                </div>
            </div>
            <!-- End Testimonial Style1-->

            <!-- Start Partner Style1-->
            <div class="partner-style1">
                <div class="container">
                    <div class="partners-style1__text">
                        <p>Nos partenaires techniques et financiers.</p>
                    </div>
                    <div class="partner-style1__inner">
                        <div class="owl-carousel owl-theme thm-owl__carousel partner-style1-carousel" data-owl-options='{
                            "loop": true,
                            "autoplay": true,
                            "margin": 0,
                            "nav": false,
                            "dots": false,
                            "smartSpeed": 500,
                            "autoplayTimeout": 10000,
                            "navText": ["<span class=\"left icon-arrow-prev\"></span>","<span class=\"icon-arrow-next\"></span>"],
                            "responsive": {
                                    "0": {
                                        "items": 1
                                    },
                                    "768": {
                                        "items": 2
                                    },
                                    "992": {
                                        "items": 3
                                    },
                                    "1199": {
                                        "items": 4
                                    },
                                    "1200": {
                                        "items": 5
                                    }
                                }
                            }'>

                            @foreach (config('rejeppat.partenaires') as $partenaire)
                            <!-- Start Single Partner Style1-->
                            <div class="single-partner-style1">
                                <a href="#">
                                    <img src="{{ asset('assets/images/rejeppat/partenaires/' . $partenaire['image']) }}" alt="{{ $partenaire['nom'] }}">
                                </a>
                            </div>
                            <!-- End Single Partner Style1-->
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
            <!-- End Partner Style1-->
        </section>
        <!-- End Testimonials & Partners -->
