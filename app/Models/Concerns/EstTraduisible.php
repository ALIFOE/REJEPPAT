<?php

namespace App\Models\Concerns;

/**
 * Contenu proposé en français (langue de saisie) et, facultativement, en anglais.
 *
 * La colonne « traductions » contient {"en": {"champ": valeur}} ; les champs traduisibles
 * sont déclarés dans la constante TRADUCTIONS du modèle : champ => [libellé, type de saisie]
 * (texte, textarea, lignes, paires ou html).
 */
trait EstTraduisible
{
    /**
     * Valeur du champ dans la langue du visiteur : la traduction si elle est remplie, sinon le français.
     */
    public function tr(string $champ): mixed
    {
        $langue = app()->getLocale();

        if ($langue !== 'fr') {
            $traduction = data_get($this->traductions, "{$langue}.{$champ}");

            if (filled($traduction)) {
                return $traduction;
            }
        }

        return $this->{$champ};
    }

    /** Traduction saisie pour un champ (formulaires de l'administration). */
    public function traduction(string $champ, string $langue = 'en'): mixed
    {
        return data_get($this->traductions, "{$langue}.{$champ}");
    }
}
