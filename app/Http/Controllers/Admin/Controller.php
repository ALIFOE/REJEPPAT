<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Outils communs aux écrans de l'administration.
 */
abstract class Controller extends BaseController
{
    protected const PAR_PAGE = 15;

    /** Règle de validation des visuels envoyés */
    protected const REGLE_IMAGE = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'];

    protected const MESSAGES = [
        'required' => 'Ce champ est obligatoire.',
        'image' => 'Le fichier doit être une image.',
        'mimes' => 'Formats acceptés : JPG, PNG ou WEBP.',
        'max' => 'Valeur ou fichier trop grand.',
        'unique' => 'Cette valeur est déjà utilisée.',
        'integer' => 'Indiquez un nombre entier.',
        'min' => 'Valeur trop petite.',
        'email' => 'L’adresse e-mail n’est pas valide.',
        'confirmed' => 'Les deux mots de passe ne correspondent pas.',
        'date' => 'La date n’est pas valide.',
        'array' => 'Choix invalide.',
    ];

    /** Une ligne du champ texte = un élément (paragraphe, module, point fort…). */
    protected function lignes(?string $texte): array
    {
        return collect(preg_split('/\R/', (string) $texte))
            ->map(fn ($ligne) => trim($ligne))
            ->filter()
            ->values()
            ->all();
    }

    /** « Titre | Texte » sur chaque ligne (actions des projets). */
    protected function paires(?string $texte): array
    {
        return collect($this->lignes($texte))->map(function ($ligne) {
            [$titre, $texte] = array_pad(array_map('trim', explode('|', $ligne, 2)), 2, '');

            return ['titre' => $titre, 'texte' => $texte];
        })->all();
    }

    /** Mots-clés séparés par des virgules. */
    protected function liste(?string $texte): array
    {
        return collect(explode(',', (string) $texte))->map(fn ($mot) => trim($mot))->filter()->unique()->values()->all();
    }

    /** Slug unique pour le modèle, à partir de la saisie ou du titre. */
    protected function slug(string $modele, ?string $saisie, string $titre, ?Model $actuel = null): string
    {
        $base = Str::slug($saisie ?: $titre) ?: Str::lower(Str::random(6));
        $slug = $base;
        $i = 2;

        while ($modele::where('slug', $slug)->when($actuel, fn ($q) => $q->whereKeyNot($actuel->getKey()))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    /** Enregistre un visuel dans public/uploads/{dossier} et supprime l'ancien. */
    protected function enregistrerPhoto(?UploadedFile $fichier, string $dossier, ?string $ancienne = null): ?string
    {
        if (! $fichier) {
            return $ancienne;
        }

        $this->supprimerPhoto($ancienne);

        return $fichier->store($dossier, 'uploads');
    }

    protected function supprimerPhoto(?string $chemin): void
    {
        // Les visuels d'origine du site (sans dossier) ne sont jamais supprimés
        if ($chemin && str_contains($chemin, '/')) {
            Storage::disk('uploads')->delete($chemin);
        }
    }

    protected function photoDemandee(Request $request, Model $modele, string $dossier): ?string
    {
        if ($request->boolean('retirer_photo')) {
            $this->supprimerPhoto($modele->photo);

            return null;
        }

        return $this->enregistrerPhoto($request->file('photo'), $dossier, $modele->photo);
    }
}
