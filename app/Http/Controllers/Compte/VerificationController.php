<?php

namespace App\Http\Controllers\Compte;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Vérification de l'adresse e-mail après l'inscription ou un changement d'adresse. */
class VerificationController extends Controller
{
    public function notice(Request $request): View|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('compte.index');
        }

        return view('compte.verifier-email');
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        return redirect()->route('compte.index')->with('succes', __('Merci, votre adresse e-mail est confirmée.'));
    }

    public function renvoyer(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('compte.index');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('succes', __('Un nouveau lien de vérification vient de vous être envoyé.'));
    }
}
