<?php

namespace App\Http\Controllers;

use App\Models\DemandeService;
use App\Models\MessageContact;
use App\Support\Contenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PageController extends Controller
{
    private const MESSAGES_VALIDATION = [
        'required' => 'Ce champ est obligatoire.',
        'accepted' => 'Veuillez accepter les conditions.',
        'email' => 'L’adresse e-mail n’est pas valide.',
        'in' => 'Veuillez choisir un élément dans la liste.',
        'max' => 'Ce champ est trop long.',
    ];

    public function accueil(): View
    {
        return view('pages.home', [
            'actualites' => Contenu::actualites()->take(3),
            'produits' => Contenu::produits(),
            'projets' => Contenu::projets(),
            'fermes' => Contenu::fermes()->where('accueil', true)->take(4)->values(),
        ]);
    }

    public function offres(): View
    {
        return view('pages.offres', [
            'projets' => Contenu::projets()->take(5),
        ]);
    }

    public function demande(): View
    {
        return view('pages.demande', [
            'services' => Contenu::servicesDemande(),
        ]);
    }

    public function envoyerDemande(Request $request): RedirectResponse
    {
        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:120'],
            'telephone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'organisation' => ['nullable', 'string', 'max:160'],
            'service' => ['required', Rule::in(Contenu::servicesDemande())],
            'region' => ['nullable', 'string', 'max:120'],
            'objet' => ['required', 'string', 'max:200'],
            'besoin' => ['required', 'string', 'max:5000'],
            'accord' => ['accepted'],
        ], self::MESSAGES_VALIDATION);

        $demande = DemandeService::create($donnees);

        // La demande est enregistrée : un échec d'envoi de l'e-mail ne doit pas bloquer le visiteur.
        rescue(fn () => Mail::raw(
            "Nouvelle demande de service sur le site du REJEPPAT\n\n"
                . "Nom : {$demande->nom}\nTéléphone : {$demande->telephone}\n"
                . 'E-mail : ' . ($demande->email ?: '-') . "\n"
                . 'Organisation : ' . ($demande->organisation ?: '-') . "\n"
                . "Service : {$demande->service}\n"
                . 'Région : ' . ($demande->region ?: '-') . "\n"
                . "Objet : {$demande->objet}\n\n{$demande->besoin}\n\n"
                . 'Suivi : ' . route('admin.demandes.show', $demande),
            fn ($message) => $message->to(config('rejeppat.emails.0'))->subject('[Site REJEPPAT] Demande de service : ' . $demande->objet)
        ));

        return redirect()->to(route('demande') . '#formulaire')
            ->with('succes', 'Merci ! Votre demande a bien été enregistrée. Notre équipe vous contactera rapidement.')
            ->with('demande_whatsapp', $this->messageWhatsapp($demande));
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function envoyerContact(Request $request): RedirectResponse
    {
        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:120'],
            'telephone' => ['nullable', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:160'],
            'objet' => ['required', Rule::in(config('rejeppat.objets_contact'))],
            'message' => ['required', 'string', 'max:5000'],
        ], self::MESSAGES_VALIDATION);

        MessageContact::create($donnees);

        $corps = "Nouveau message depuis le site du REJEPPAT\n\n"
            . "Nom : {$donnees['nom']}\n"
            . 'Téléphone : ' . (($donnees['telephone'] ?? null) ?: '-') . "\n"
            . "Email : {$donnees['email']}\n"
            . "Objet : {$donnees['objet']}\n\n"
            . $donnees['message'];

        rescue(fn () => Mail::raw($corps, function ($message) use ($donnees) {
            $message->to(config('rejeppat.emails.0'))
                ->replyTo($donnees['email'], $donnees['nom'])
                ->subject('[Site REJEPPAT] ' . $donnees['objet']);
        }));

        return redirect()->to(route('contact') . '#formulaire')
            ->with('succes', 'Merci ! Votre message a bien été envoyé. Notre équipe vous répondra rapidement.');
    }

    /** Lien WhatsApp pré-rempli, proposé après l'envoi (comme sur l'ancien site). */
    private function messageWhatsapp(DemandeService $demande): string
    {
        $texte = implode("\n", [
            '*Demande de service - REJEPPAT*',
            'Nom complet : ' . $demande->nom,
            'Téléphone : ' . $demande->telephone,
            'Service souhaité : ' . $demande->service,
            'Objet : ' . $demande->objet,
            '',
            $demande->besoin,
        ]);

        return 'https://wa.me/' . config('rejeppat.whatsapp') . '?text=' . rawurlencode($texte);
    }
}
