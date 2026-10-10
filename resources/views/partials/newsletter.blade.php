{{-- Bandeau d'inscription à la newsletter (pages intérieures, au-dessus du pied de page) --}}
<section class="rj-newsletter" id="newsletter">
    <div class="container">
        <div class="rj-newsletter__inner">
            <div>
                <h3>{{ __('Restez informé du réseau') }}</h3>
                <p>{{ __('Actualités, formations, foires et opportunités : recevez notre newsletter.') }}</p>
            </div>
            <form action="{{ route('newsletter.abonner') }}" method="post">
                @csrf
                <input type="email" name="email" placeholder="{{ __('Votre adresse e-mail') }}" aria-label="{{ __('Votre adresse e-mail') }}" required>
                <button type="submit">{{ __('S’abonner') }}</button>
            </form>
            @if (session('newsletter'))
                <p class="rj-newsletter__message" role="status">{{ session('newsletter') }}</p>
            @endif
            @if ($errors->newsletter->any())
                <p class="rj-newsletter__message rj-newsletter__message--erreur" role="alert">{{ $errors->newsletter->first() }}</p>
            @endif
        </div>
    </div>
</section>
