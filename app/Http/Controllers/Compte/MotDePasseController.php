<?php

namespace App\Http\Controllers\Compte;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

/** Mot de passe oublié : envoi du lien par e-mail puis choix d'un nouveau mot de passe. */
class MotDePasseController extends Controller
{
    public function demande(): View
    {
        return view('compte.mot-de-passe-oublie');
    }

    public function envoyerLien(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']], $this->messages(self::MESSAGES));

        $statut = Password::sendResetLink(['email' => Str::lower(trim($request->input('email')))]);

        if ($statut === Password::RESET_THROTTLED) {
            return back()->withInput($request->only('email'))->withErrors(['email' => __($statut)]);
        }

        // Même réponse que le compte existe ou non : on ne révèle pas les adresses inscrites
        return back()->with('succes', __('Si un compte existe avec cette adresse, vous allez recevoir un e-mail contenant un lien pour choisir un nouveau mot de passe.'));
    }

    public function edit(Request $request, string $token): View
    {
        return view('compte.reinitialiser', ['token' => $token, 'email' => $request->query('email')]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => $this->regleMotDePasse(),
        ], $this->messages(self::MESSAGES));

        $statut = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $motDePasse) {
                $user->forceFill(['password' => $motDePasse, 'remember_token' => Str::random(60)])->save();

                event(new PasswordReset($user));
            },
        );

        if ($statut !== Password::PASSWORD_RESET) {
            return back()->withInput($request->only('email'))->withErrors(['email' => __($statut)]);
        }

        return redirect()->route('connexion')->with('succes', __($statut));
    }
}
