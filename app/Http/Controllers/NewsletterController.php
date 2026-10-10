<?php

namespace App\Http\Controllers;

use App\Models\Abonne;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    /** Inscription depuis le pied de page ; une adresse désabonnée peut se réinscrire. */
    public function abonner(Request $request): RedirectResponse
    {
        $donnees = $request->validateWithBag('newsletter', [
            'email' => ['required', 'email', 'max:160'],
        ], $this->messages(self::MESSAGES_FORMULAIRE));

        $abonne = Abonne::firstOrNew(['email' => mb_strtolower(trim($donnees['email']))]);
        $abonne->fill(['locale' => app()->getLocale(), 'desabonne_le' => null])->save();

        return redirect()->to(url()->previous().'#newsletter')
            ->with('newsletter', __('Merci ! Vous êtes bien inscrit(e) à notre newsletter.'));
    }

    public function desabonnement(string $jeton): View
    {
        $abonne = Abonne::where('jeton', $jeton)->firstOrFail();

        if (! $abonne->desabonne_le) {
            $abonne->update(['desabonne_le' => now()]);
        }

        return view('pages.desabonnement', compact('abonne'));
    }
}
