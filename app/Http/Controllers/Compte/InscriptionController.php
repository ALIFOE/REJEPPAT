<?php

namespace App\Http\Controllers\Compte;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InscriptionController extends Controller
{
    public function create(): View
    {
        return view('compte.inscription');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge(['email' => Str::lower(trim((string) $request->input('email')))]);

        $donnees = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', 'unique:users,email'],
            'telephone' => ['nullable', 'string', 'max:40'],
            'password' => $this->regleMotDePasse(),
            'accord' => ['accepted'],
        ], self::MESSAGES);

        $user = User::create([
            'name' => $donnees['name'],
            'email' => $donnees['email'],
            'telephone' => $donnees['telephone'] ?? null,
            'password' => $donnees['password'],
        ]);

        // Envoie l'e-mail de vérification de l'adresse
        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('verification.notice');
    }
}
