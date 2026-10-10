<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lettre->sujet }}</title>
</head>
<body style="margin: 0; padding: 0; background: #f1f8ec; font-family: Arial, Helvetica, sans-serif; color: #4d6352;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background: #f1f8ec; padding: 24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 640px; background: #ffffff; border-radius: 12px; overflow: hidden;">
                    <tr>
                        <td style="background: #2e6b3a; padding: 20px 28px;">
                            <img src="{{ asset('assets/images/rejeppat/logo/logo-rejeppat-officiel.png') }}" alt="{{ config('rejeppat.name') }}" height="48" style="display: block; background: #ffffff; border-radius: 8px; padding: 6px;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 28px; font-size: 16px; line-height: 1.6;">
                            <h1 style="margin: 0 0 18px; font-size: 22px; color: #0c2213;">{{ $lettre->sujet }}</h1>
                            {!! $lettre->contenu !!}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 18px 28px; background: #e6f2de; font-size: 13px; line-height: 1.5; color: #4f6b52;">
                            {{ config('rejeppat.full_name') }} – {{ config('rejeppat.address_short') }}<br>
                            <a href="{{ route('home') }}" style="color: #2e6b3a;">{{ preg_replace('#^https?://#', '', url('/')) }}</a>
                            · <a href="https://wa.me/{{ config('rejeppat.whatsapp') }}" style="color: #2e6b3a;">WhatsApp</a>
                            · <a href="{{ config('rejeppat.social.facebook') }}" style="color: #2e6b3a;">Facebook</a><br><br>
                            Vous recevez cette lettre car vous êtes abonné(e) à la newsletter du {{ config('rejeppat.name') }}.
                            <a href="{{ $abonne->lienDesabonnement() }}" style="color: #2e6b3a;">Se désabonner</a>.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
