{{--
    Contact rapide présent sur toutes les pages : bouton WhatsApp et petit assistant
    (réponses aux questions fréquentes + formulaire de message rapide).
--}}
@php
    $questions = [
        ['question' => __('Comment commander ?'), 'reponse' => __('Ajoutez vos produits au panier depuis la boutique, puis validez la commande : vous choisissez la livraison et le paiement (Mobile Money ou à la livraison).'), 'lien' => route('boutique.index'), 'libelle' => __('Aller à la boutique')],
        ['question' => __('Livraison et paiement'), 'reponse' => __('Retrait au siège à Sokodé, livraison à domicile à Sokodé ou expédition dans tout le Togo. Paiement à la réception (espèces ou Mobile Money) ou en ligne selon les options proposées à la commande.'), 'lien' => route('commande.suivi'), 'libelle' => __('Suivre une commande')],
        ['question' => __('Se former dans une ferme école'), 'reponse' => __('Nos fermes écoles forment les jeunes par la pratique (maraîchage, élevage, transformation…). Faites une demande de service pour vous inscrire.'), 'lien' => route('demande'), 'libelle' => __('Faire une demande')],
        ['question' => __('Devenir membre ou partenaire'), 'reponse' => __('Les jeunes producteurs peuvent rejoindre l’annuaire des membres ; les organisations peuvent proposer un partenariat.'), 'lien' => route('partenaires.index') . '#devenir-partenaire', 'libelle' => __('Devenir partenaire')],
    ];
@endphp
<div class="rj-aide" data-contact-rapide>
    <div class="rj-aide__boutons">
        <button type="button" class="rj-aide__bouton" data-aide-ouvrir aria-expanded="false" aria-controls="rj-aide-panneau" title="{{ __('Besoin d’aide ?') }}">
            <i class="far fa-comments"></i>
        </button>
        <a href="https://wa.me/{{ config('rejeppat.whatsapp') }}?text={{ rawurlencode(__('Bonjour REJEPPAT, je vous contacte depuis votre site.')) }}" class="rj-aide__bouton rj-aide__bouton--whatsapp" target="_blank" rel="noopener" title="{{ __('Discuter sur WhatsApp') }}" aria-label="{{ __('Discuter sur WhatsApp') }}">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>

    <div class="rj-aide__panneau" id="rj-aide-panneau" hidden>
        <div class="rj-aide__entete">
            <div>
                <strong>{{ __('Bonjour ! Comment pouvons-nous vous aider ?') }}</strong>
                <small>{{ __('Réponse rapide par téléphone ou WhatsApp.') }}</small>
            </div>
            <button type="button" class="rj-aide__fermer" data-aide-fermer aria-label="{{ __('Fermer') }}">&times;</button>
        </div>
        <div class="rj-aide__corps">
            <div class="rj-aide__bulle">{{ __('Choisissez une question fréquente ou laissez-nous un message.') }}</div>
            <div class="rj-aide__questions">
                @foreach ($questions as $i => $question)
                    <button type="button" data-aide-question="{{ $i }}">{{ $question['question'] }}</button>
                @endforeach
            </div>
            @foreach ($questions as $i => $question)
                <div class="rj-aide__bulle rj-aide__bulle--reponse" data-aide-reponse="{{ $i }}" hidden>
                    {{ $question['reponse'] }}
                    <br><a href="{{ $question['lien'] }}"><strong>{{ $question['libelle'] }} →</strong></a>
                </div>
            @endforeach

            <form class="rj-aide__formulaire" action="{{ route('contact.rapide') }}" method="post" data-aide-formulaire>
                @csrf
                <input type="text" name="nom" placeholder="{{ __('Votre nom') }} *" required maxlength="120" aria-label="{{ __('Votre nom') }}">
                <input type="tel" name="telephone" placeholder="{{ __('Téléphone / WhatsApp') }} *" required maxlength="40" aria-label="{{ __('Téléphone / WhatsApp') }}">
                <textarea name="message" placeholder="{{ __('Votre message…') }}" required maxlength="3000" aria-label="{{ __('Votre message…') }}"></textarea>
                <button type="submit">{{ __('Envoyer') }}</button>
                <p class="rj-aide__statut" data-aide-statut role="status">{{ session('contact_rapide') }}</p>
            </form>
        </div>
    </div>
</div>
