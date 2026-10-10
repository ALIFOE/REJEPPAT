{{-- Choix de la langue du site. Paramètre : $classe (facultatif) --}}
<div class="rj-langues {{ $classe ?? '' }}" role="group" aria-label="{{ __('Langue du site') }}">
    @foreach (['fr' => 'Français', 'en' => 'English'] as $code => $nom)
        <a href="{{ route('langue', $code) }}" class="{{ app()->getLocale() === $code ? 'actif' : '' }}" hreflang="{{ $code }}" lang="{{ $code }}" title="{{ $nom }}" @if (app()->getLocale() === $code) aria-current="true" @endif>{{ strtoupper($code) }}</a>
    @endforeach
</div>
