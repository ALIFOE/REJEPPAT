<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use App\Support\Photo;
use App\Support\TexteRiche;
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
        'in' => 'Choix invalide.',
        'url' => 'L’adresse doit commencer par https://.',
        'file' => 'Le fichier n’a pas pu être envoyé.',
        'after_or_equal' => 'La date de fin doit suivre la date de début.',
        'exists' => 'Choix invalide.',
    ];

    /** Documents téléchargeables (ressources, opportunités) */
    protected const REGLE_DOCUMENT = ['file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,odt,zip', 'max:20480'];

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
            $slug = $base.'-'.$i++;
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

        return $this->stockerPhoto($fichier, $dossier);
    }

    /** Stocke une image dans public/uploads/{dossier} puis l'optimise (orientation, taille, netteté). */
    protected function stockerPhoto(UploadedFile $fichier, string $dossier): string
    {
        $chemin = $fichier->store($dossier, 'uploads');
        Photo::optimiser($chemin);

        return $chemin;
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

    /**
     * Emplacements de photos d'une galerie (champ « photos[i] », cases « retirer_photos[] ») :
     * chacun peut être rempli, remplacé ou vidé.
     *
     * @param  array<int, string>|null  $actuelles
     * @return array<int, string>
     */
    protected function galerieDemandee(Request $request, ?array $actuelles, int $emplacements, string $dossier): array
    {
        $actuelles = array_values($actuelles ?? []);
        $retirer = array_map('strval', (array) $request->input('retirer_photos', []));

        return collect(range(0, $emplacements - 1))
            ->map(function (int $i) use ($request, $actuelles, $retirer, $dossier) {
                $actuelle = $actuelles[$i] ?? null;

                if ($actuelle && in_array((string) $i, $retirer, true)) {
                    $this->supprimerPhoto($actuelle);
                    $actuelle = null;
                }

                return $this->enregistrerPhoto($request->file("photos.{$i}"), $dossier, $actuelle);
            })
            ->filter()
            ->values()
            ->all();
    }

    /** Document (PDF, Word, audio…) stocké tel quel dans public/uploads/{dossier}, en remplaçant l'ancien. */
    protected function enregistrerFichier(?UploadedFile $fichier, string $dossier, ?string $ancien = null): ?string
    {
        if (! $fichier) {
            return $ancien;
        }

        $this->supprimerPhoto($ancien);

        return $fichier->store($dossier, 'uploads');
    }

    /** Règles de validation de la version anglaise (champs « traductions[en][…] »). */
    protected function reglesTraductions(): array
    {
        return [
            'traductions' => ['nullable', 'array'],
            'traductions.en' => ['nullable', 'array'],
            'traductions.en.*' => ['nullable', 'string', 'max:50000'],
        ];
    }

    /**
     * Version anglaise saisie, mise en forme selon le type de chaque champ traduisible du modèle
     * (constante TRADUCTIONS). Les champs vides ne sont pas enregistrés : le français s'affiche.
     *
     * @param  class-string<Model>  $modele
     * @return array{en: array<string, mixed>}|null
     */
    protected function traductions(Request $request, string $modele): ?array
    {
        $saisies = (array) $request->input('traductions.en', []);

        $anglais = collect($modele::TRADUCTIONS)
            ->map(function (array $definition, string $champ) use ($saisies) {
                $valeur = $saisies[$champ] ?? null;

                return match ($definition[1]) {
                    'lignes' => $this->lignes($valeur),
                    'paires' => $this->paires($valeur),
                    'html' => blank(strip_tags((string) $valeur)) ? null : TexteRiche::nettoyer(
                        $valeur === strip_tags((string) $valeur) ? TexteRiche::depuisParagraphes($this->lignes($valeur)) : $valeur
                    ),
                    default => trim((string) $valeur),
                };
            })
            ->filter(fn ($valeur) => filled($valeur))
            ->all();

        return $anglais ? ['en' => $anglais] : null;
    }
}
