<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/** Comptes du site et leurs rôles (super admin, administrateur, client) : réservé au super admin. */
class UtilisateurController extends Controller
{
    public function index(Request $request): View
    {
        $utilisateurs = User::query()
            ->when($request->query('q'), fn ($q, $recherche) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$recherche}%")
                ->orWhere('email', 'like', "%{$recherche}%")
                ->orWhere('telephone', 'like', "%{$recherche}%")))
            ->when($request->query('role'), fn ($q, $role) => $q->where('role', $role))
            ->withCount('commandes')
            ->orderBy('name')
            ->paginate(self::PAR_PAGE)->withQueryString();

        $compteurs = User::selectRaw('role, COUNT(*) as total')->groupBy('role')->pluck('total', 'role');

        return view('admin.utilisateurs.index', compact('utilisateurs', 'compteurs'));
    }

    public function create(): View
    {
        return view('admin.utilisateurs.form', ['utilisateur' => new User(['role' => User::ADMIN])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $utilisateur = User::create($this->donnees($request, new User));

        // Compte créé par le super admin : l'adresse est considérée comme vérifiée
        $utilisateur->markEmailAsVerified();

        return redirect()->route('admin.utilisateurs.index')->with('succes', 'Compte « '.$utilisateur->roleLibelle().' » créé.');
    }

    public function edit(User $utilisateur): View
    {
        return view('admin.utilisateurs.form', compact('utilisateur'));
    }

    public function update(Request $request, User $utilisateur): RedirectResponse
    {
        $donnees = $this->donnees($request, $utilisateur);

        // Le super admin ne peut pas se retirer ses propres droits (il resterait toujours au moins un super admin)
        if ($utilisateur->is($request->user()) && $donnees['role'] !== $utilisateur->role) {
            return back()->withInput()->withErrors(['role' => 'Vous ne pouvez pas modifier votre propre rôle.']);
        }

        $utilisateur->update($donnees);

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
            'telephone' => ['nullable', 'string', 'max:40'],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'password' => [$utilisateur->exists ? 'nullable' : 'required', 'confirmed', Password::min(8)],
        ], self::MESSAGES + [
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'role.in' => 'Veuillez choisir un rôle.',
        ]);

        if (blank($donnees['password'] ?? null)) {
            unset($donnees['password']);
        }

        return $donnees;
    }
}
