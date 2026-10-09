<?php

namespace App\Http\Controllers\Compte;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Validation\Rules\Password;

/**
 * Outils communs à l'espace client : connexion, inscription, mot de passe et compte.
 */
abstract class Controller extends BaseController
{
    protected const MESSAGES = [
        'required' => 'Ce champ est obligatoire.',
        'email' => 'L’adresse e-mail n’est pas valide.',
        'max' => 'Ce champ est trop long.',
        'unique' => 'Un compte existe déjà avec cette adresse e-mail.',
        'confirmed' => 'Les deux mots de passe ne correspondent pas.',
        'accepted' => 'Vous devez accepter cette condition.',
        'current_password' => 'Le mot de passe actuel est incorrect.',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
    ];

    /** Règle du nouveau mot de passe (inscription, réinitialisation, changement). */
    protected function regleMotDePasse(): array
    {
        return ['required', 'confirmed', Password::min(8)];
    }
}
