<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/** Profil du super admin ou de l'administrateur connecté. */
class ProfilController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.profil', ['utilisateur' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $utilisateur = $request->user();

        $donnees = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', Rule::unique('users')->ignore($utilisateur)],
            'telephone' => ['nullable', 'string', 'max:40'],
        ], self::MESSAGES);

        $utilisateur->update($donnees);

        return back()->with('succes', 'Votre profil a été mis à jour.');
    }

    public function motDePasse(Request $request): RedirectResponse
    {
        $donnees = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], self::MESSAGES + [
            'current_password' => 'Le mot de passe actuel est incorrect.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        $request->user()->update(['password' => $donnees['password']]);

        return back()->with('succes', 'Votre mot de passe a été modifié.');
    }
}
