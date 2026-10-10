<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /** Messages de validation des formulaires publics */
    protected const MESSAGES_FORMULAIRE = [
        'required' => 'Ce champ est obligatoire.',
        'accepted' => 'Veuillez accepter les conditions.',
        'email' => 'L’adresse e-mail n’est pas valide.',
        'in' => 'Veuillez choisir un élément dans la liste.',
        'max' => 'Ce champ est trop long.',
        'exists' => 'Veuillez choisir un élément dans la liste.',
        'image' => 'Le fichier doit être une image.',
        'mimes' => 'Formats acceptés : JPG, PNG ou WEBP.',
    ];

    /**
     * Messages de validation dans la langue du visiteur (les textes français servent de clés de traduction).
     *
     * @param  array<string, string>  $messages
     * @return array<string, string>
     */
    protected function messages(array $messages): array
    {
        return array_map(fn (string $message) => __($message), $messages);
    }
}
