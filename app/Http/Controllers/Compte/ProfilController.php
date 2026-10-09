<?php

namespace App\Http\Controllers\Compte;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/** Informations personnelles, mot de passe et suppression du compte. */
class ProfilController extends Controller
{
    public function edit(): View
    {
        return view('compte.profil');
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $request->merge(['email' => Str::lower(trim((string) $request->input('email')))]);

        $donnees = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', Rule::unique('users')->ignore($user)],
            'telephone' => ['nullable', 'string', 'max:40'],
        ], self::MESSAGES);

        $user->fill($donnees);

        // Nouvelle adresse : elle doit être vérifiée à son tour
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if (! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();

            return redirect()->route('verification.notice');
        }

        return back()->with('succes', 'Vos informations ont été enregistrées.');
    }

    public function motDePasse(Request $request): RedirectResponse
    {
        $donnees = $request->validateWithBag('motDePasse', [
            'current_password' => ['required', 'current_password'],
            'password' => $this->regleMotDePasse(),
        ], self::MESSAGES);

        $request->user()->update(['password' => $donnees['password']]);

        return back()->with('succes', 'Votre mot de passe a été modifié.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('suppression', [
            'password' => ['required', 'current_password'],
        ], self::MESSAGES);

        $user = $request->user();

        if ($user->estAdministrateur()) {
            return back()->withErrors(['password' => 'Un compte administrateur se supprime depuis l’administration.'], 'suppression');
        }

        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
