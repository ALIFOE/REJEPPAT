<?php

namespace App\Http\Controllers;

use App\Support\Contenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PageController extends Controller
{
    public function accueil(): View
    {
        return view('pages.home', [
            'actualites' => Contenu::actualites()->take(3),
            'produits' => Contenu::produits(),
            'projets' => Contenu::projets(),
            'fermes' => Contenu::fermes()->whereIn('slug', ['ma-joie', 'cadete', 'pain-de-vie', 'pirenadou'])->values(),
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
        return view('pages.demande');
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
            'objet' => ['required', 'in:' . implode(',', config('rejeppat.objets_contact'))],
            'message' => ['required', 'string', 'max:5000'],
        ], [
            'required' => 'Ce champ est obligatoire.',
            'email' => 'L’adresse e-mail n’est pas valide.',
            'in' => 'Veuillez choisir un objet dans la liste.',
            'max' => 'Ce champ est trop long.',
        ]);

        $corps = "Nouveau message depuis le site du REJEPPAT\n\n"
            . "Nom : {$donnees['nom']}\n"
            . 'Téléphone : ' . ($donnees['telephone'] ?: '-') . "\n"
            . "Email : {$donnees['email']}\n"
            . "Objet : {$donnees['objet']}\n\n"
            . $donnees['message'];

        Mail::raw($corps, function ($message) use ($donnees) {
            $message->to(config('rejeppat.emails.0'))
                ->replyTo($donnees['email'], $donnees['nom'])
                ->subject('[Site REJEPPAT] ' . $donnees['objet']);
        });

        return redirect()->to(route('contact') . '#formulaire')
            ->with('succes', 'Merci ! Votre message a bien été envoyé. Notre équipe vous répondra rapidement.');
    }
}
