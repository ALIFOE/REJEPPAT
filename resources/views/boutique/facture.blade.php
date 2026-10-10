@php
    use App\Support\Contenu;

    $estPayee = $commande->estPayee();
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ __('Facture') }} {{ $commande->numero }} || {{ config('rejeppat.name') }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/rejeppat/favicons/favicon-32x32.png?v=logo') }}">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 32px 16px; background: #f1f8ec; font-family: Arial, Helvetica, sans-serif; color: #24352a; font-size: 14px; line-height: 1.5; }
        .facture { max-width: 820px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 40px; box-shadow: 0 6px 24px rgba(31, 74, 44, .08); }
        .entete { display: flex; justify-content: space-between; gap: 24px; flex-wrap: wrap; border-bottom: 3px solid #2e6b3a; padding-bottom: 20px; }
        .entete img { height: 64px; }
        .entete h1 { margin: 0 0 4px; font-size: 26px; color: #0c2213; text-align: right; }
        .entete p { margin: 0; text-align: right; color: #4f6b52; }
        .parties { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin: 28px 0; }
        .parties h2 { margin: 0 0 6px; font-size: 12px; text-transform: uppercase; letter-spacing: .06em; color: #6f8f72; }
        .parties p { margin: 0; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #e6f2de; color: #0c2213; text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #d6e8cc; }
        .num { text-align: right; white-space: nowrap; }
        tfoot td { border-bottom: none; }
        tfoot tr.total td { font-size: 18px; font-weight: bold; color: #0c2213; border-top: 2px solid #2e6b3a; }
        .statut { display: inline-block; margin-top: 24px; padding: 6px 14px; border-radius: 30px; font-weight: bold; }
        .statut--payee { background: #e3f6e8; color: #1f7a3c; }
        .statut--due { background: #fdf2cc; color: #8a6a0c; }
        .mentions { margin-top: 28px; padding-top: 16px; border-top: 1px solid #d6e8cc; color: #6f8f72; font-size: 12px; }
        .actions { max-width: 820px; margin: 0 auto 16px; display: flex; gap: 10px; justify-content: flex-end; }
        .actions button, .actions a { border: 0; border-radius: 8px; padding: 10px 18px; background: #2e6b3a; color: #fff; font-size: 14px; cursor: pointer; text-decoration: none; }
        .actions a { background: #fff; color: #2e6b3a; border: 1px solid #d6e8cc; }
        @media (max-width: 600px) {
            .facture { padding: 22px; }
            .parties { grid-template-columns: 1fr; }
            .entete h1, .entete p { text-align: left; }
            th:nth-child(2), td:nth-child(2) { display: none; }
        }
        @media print {
            body { background: #fff; padding: 0; }
            .facture { box-shadow: none; padding: 0; }
            .actions { display: none; }
        }
    </style>
</head>
<body>

    <div class="actions">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}">{{ __('Retour') }}</a>
        <button type="button" onclick="window.print()">{{ __('Imprimer / enregistrer en PDF') }}</button>
    </div>

    <main class="facture">
        <div class="entete">
            <div>
                <img src="{{ asset('assets/images/rejeppat/logo/logo-rejeppat-officiel.png') }}" alt="{{ config('rejeppat.name') }}">
            </div>
            <div>
                <h1>{{ __('Facture') }}</h1>
                <p><strong>N° {{ $commande->numero }}</strong></p>
                <p>{{ __('Date') }} : {{ $commande->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="parties">
            <div>
                <h2>{{ __('Vendeur') }}</h2>
                <p><strong>{{ config('rejeppat.name') }}</strong><br>
                    {{ config('rejeppat.full_name') }}<br>
                    {{ config('rejeppat.address_short') }} – {{ config('rejeppat.po_box') }}<br>
                    {{ config('rejeppat.phones.0.label') }} · {{ config('rejeppat.emails.0') }}</p>
            </div>
            <div>
                <h2>{{ __('Client') }}</h2>
                <p><strong>{{ $commande->nom }}</strong><br>
                    {{ $commande->telephone }}@if ($commande->email)<br>{{ $commande->email }}@endif
                    @if ($commande->ville || $commande->adresse)<br>{{ trim($commande->ville . ', ' . $commande->adresse, ', ') }}@endif</p>
            </div>
        </div>

        <table>
            <thead>
                <tr><th>{{ __('Produit') }}</th><th class="num">{{ __('Prix unitaire') }}</th><th class="num">{{ __('Quantité') }}</th><th class="num">{{ __('Total') }}</th></tr>
            </thead>
            <tbody>
                @foreach ($commande->lignes as $ligne)
                    <tr>
                        <td>{{ $ligne->nom_produit }}</td>
                        <td class="num">{{ Contenu::prix($ligne->prix_unitaire) }}</td>
                        <td class="num">{{ $ligne->quantite }}</td>
                        <td class="num">{{ Contenu::prix($ligne->total) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr><td colspan="3" class="num">{{ __('Sous-total') }}</td><td class="num">{{ Contenu::prix($commande->sous_total) }}</td></tr>
                <tr><td colspan="3" class="num">{{ __('Livraison') }} ({{ __($commande->modeLivraisonLibelle()) }})</td><td class="num">{{ Contenu::prix($commande->frais_livraison) }}</td></tr>
                <tr class="total"><td colspan="3" class="num">{{ __('Total à payer') }}</td><td class="num">{{ Contenu::prix($commande->total) }}</td></tr>
            </tfoot>
        </table>

        <p class="statut {{ $estPayee ? 'statut--payee' : 'statut--due' }}">
            {{ $estPayee ? __('Payée') : __('À payer') }} – {{ __($commande->modePaiementLibelle()) }}@if ($commande->reference_paiement) ({{ __('réf.') }} {{ $commande->reference_paiement }})@endif
        </p>

        <div class="mentions">
            {{ __('Montants en francs CFA. Merci de soutenir les jeunes producteurs agricoles du Togo.') }}<br>
            {{ config('rejeppat.name') }} – {{ config('rejeppat.address') }}
        </div>
    </main>

</body>
</html>
