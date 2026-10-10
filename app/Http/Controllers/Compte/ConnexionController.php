<?php

namespace App\Http\Controllers\Compte;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConnexionController extends Controller
{
    public function create(): View
    {
        return view('compte.connexion');
    }

    public function store(Request $request): RedirectResponse
    {
        $identifiants = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], $this->messages(self::MESSAGES));

        if (! Auth::attempt($identifiants, $request->boolean('remember'))) {
            return back()->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => __('Adresse e-mail ou mot de passe incorrect.')]);
        }

        $request->session()->regenerate();

        // Chaque rôle arrive sur son tableau de bord (ou sur la page demandée avant la connexion)
        return redirect()->intended($request->user()->tableauDeBord());
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
