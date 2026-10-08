<?php

namespace App\Models\Concerns;

/**
 * Visuel d'un contenu : la photo envoyée depuis l'administration (public/uploads/…)
 * est prioritaire ; sinon on utilise les visuels d'origine du site, recadrés par variante
 * (…-detail.jpg, …-thumb.jpg…), et enfin un visuel par défaut.
 */
trait AUnVisuel
{
    public const VISUEL_PAR_DEFAUT = 'assets/images/rejeppat/visuel-par-defaut.svg';

    public const DOSSIER_UPLOADS = 'uploads';

    public function photoUrl(): ?string
    {
        return $this->photo ? asset(self::DOSSIER_UPLOADS . '/' . $this->photo) : null;
    }

    /** @param  array<int, string|null>  $candidats  chemins relatifs à public/ */
    protected function premierVisuel(array $candidats): string
    {
        if ($url = $this->photoUrl()) {
            return $url;
        }

        foreach (array_filter($candidats) as $chemin) {
            if (is_file(public_path($chemin))) {
                return asset($chemin);
            }
        }

        return asset(self::VISUEL_PAR_DEFAUT);
    }
}
