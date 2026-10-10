<?php

namespace App\Http\Controllers;

use App\Models\DemandePartenariat;
use App\Models\Partenaire;
use App\Support\Contenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PartenaireController extends Controller
{
    public function index(): View
    {
        return view('partenaires.index', [
            'parType' => Contenu::partenaires()->groupBy('type'),
            'types' => Partenaire::TYPES,
            'formes' => DemandePartenariat::TYPES,
        ]);
    }

    /** Proposition de partenariat : enregistrée pour l'administration, l'équipe est prévenue par e-mail. */
    public function store(Request $request): RedirectResponse
    {
        $donnees = $request->validate([
            'organisation' => ['required', 'string', 'max:160'],
            'nom' => ['required', 'string', 'max:120'],
            'fonction' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'telephone' => ['nullable', 'string', 'max:40'],
            'type' => ['required', Rule::in(array_keys(DemandePartenariat::TYPES))],
            'message' => ['required', 'string', 'max:5000'],
        ], $this->messages(self::MESSAGES_FORMULAIRE));

        $demande = DemandePartenariat::create($donnees);

        rescue(fn () => Mail::raw(
            "Nouvelle proposition de partenariat sur le site du REJEPPAT\n\n"
                ."Organisation : {$demande->organisation}\n"
                ."Contact : {$demande->nom}".($demande->fonction ? " ({$demande->fonction})" : '')."\n"
                ."E-mail : {$demande->email}\n"
                .'Téléphone : '.($demande->telephone ?: '-')."\n"
                .'Forme de partenariat : '.$demande->typeLibelle()."\n\n"
                .$demande->message."\n\n"
                .'Suivi : '.route('admin.partenariats.show', $demande),
            fn ($message) => $message->to(config('rejeppat.emails.0'))
                ->replyTo($demande->email, $demande->nom)
                ->subject('[Site REJEPPAT] Proposition de partenariat : '.$demande->organisation)
        ));

        return redirect()->to(route('partenaires.index').'#devenir-partenaire')
            ->with('succes', __('Merci ! Votre proposition de partenariat a bien été envoyée. Notre équipe vous répondra rapidement.'));
    }
}
