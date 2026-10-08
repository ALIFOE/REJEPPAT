{{-- Bloc de contact des barres latérales --}}
<!--Start Sidebar Style1 Single-->
<div class="sidebar-style1__single sidebar-style1__contact-info">
    <div class="sidebar-style1__contact-info-bg"
        style="background-image: url({{ asset('assets/images/sidebar/sidebar-contact-info-bg.jpg') }});">
    </div>
    <div class="sidebar-style1__contact-info-inner">
        <div class="sidebar-style1__contact-info-icon">
            <span class="icon-incoming-call"></span>
        </div>
        <div class="sidebar-style1__contact-info-text">
            <p class="font"><a href="tel:{{ config('rejeppat.phones.0.tel') }}">{{ config('rejeppat.phones.0.label') }}</a></p>
            <p><a href="mailto:{{ config('rejeppat.emails.0') }}">{{ config('rejeppat.emails.0') }}</a></p>
        </div>
        <div class="sidebar-style1__contact-info-btn">
            <a class="btn-one" href="{{ route('contact') }}">
                <i class="icon-arrow"></i>
                <span class="txt">Nous contacter</span>
            </a>
        </div>
    </div>
</div>
<!--End Sidebar Style1 Single-->
