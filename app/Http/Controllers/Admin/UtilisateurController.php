<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/** Comptes ayant accès à l'administration. */
class UtilisateurController extends Controller
{
    public function index(): View
    {
        return view('admin.utilisateurs.index', ['utilisateurs' => User::where('is_admin', true)->orderBy('name')->get()]);
    }

    public function create(): View
    {
        return view('admin.utilisateurs.form', ['utilisateur' => new User()]);
    }

    public function store(Request $request): RedirectResponse
    {
        User::create($this->donnees($request, new User()) + ['is_admin' => true]);

        return redirect()->route('admin.utilisateurs.index')->with('succes', 'Compte administrateur créé.');
    }

    public function edit(User $utilisateur): View
    {
        return view('admin.utilisateurs.form', compact('utilisateur'));
    }

    public function update(Request $request, User $utilisateur): RedirectResponse
    {
        $utilisateur->update($this->donnees($request, $utilisateur));

        return redirect()->route('admin.utilisateurs.index')->with('succes', 'Compte mis à jour.');
    }

    public function destroy(Request $request, User $utilisateur): RedirectResponse
    {
        if ($utilisateur->is($request->user())) {
            return back()->withErrors(['compte' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        $utilisateur->delete();

        return redirect()->route('admin.utilisateurs.index')->with('succes', 'Compte supprimé.');
    }

    private function donnees(Request $request, User $utilisateur): array
    {
        $donnees = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', Rule::unique('users')->ignore($utilisateur)],
            'password' => [$utilisateur->exists ? 'nullable' : 'required', 'confirmed', Password::min(8)],
        ], self::MESSAGES + ['password.min' => 'Le mot de passe doit contenir au moins 8 caractères.']);

        if (blank($donnees['password'] ?? null)) {
            unset($donnees['password']);
        }

        return $donnees;
    }
}
