<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConnexionController extends Controller
{
    public function create(): View
    {
        return view('admin.connexion');
    }

    public function store(Request $request): RedirectResponse
    {
        $identifiants = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], self::MESSAGES);

        // Seuls les super admins et administrateurs se connectent ici
        if (! Auth::attemptWhen($identifiants, fn (User $user) => $user->estAdministrateur(), $request->boolean('remember'))) {
            return back()->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Adresse e-mail ou mot de passe incorrect.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.tableau'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.connexion')->with('succes', 'Vous êtes déconnecté.');
    }
}
