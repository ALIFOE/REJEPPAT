<?php

namespace App\Support;

use GdImage;
use Illuminate\Support\Facades\Storage;

/**
 * Optimisation des photos envoyées depuis l'administration (extension GD) :
 * remise à l'endroit (photos de téléphone), réduction des très grandes images,
 * légère netteté et compression de qualité. Le fichier garde son nom et son format.
 */
class Photo
{
    /** Côté le plus long conservé : suffisant pour un affichage net en pleine largeur */
    public const COTE_MAX = 1920;

    private const QUALITE = 86;

    public static function optimiser(string $chemin, string $disque = 'uploads'): void
    {
        $fichier = Storage::disk($disque)->path($chemin);
        $type = @getimagesize($fichier)[2] ?? null;

        // GIF (animations) et formats inconnus : laissés tels quels
        if (! in_array($type, [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            return;
        }

        $image = @imagecreatefromstring((string) file_get_contents($fichier));
        if (! $image) {
            return;
        }

        if ($type === IMAGETYPE_JPEG) {
            $image = self::remettreALEndroit($image, self::orientation($fichier));
        }

        $image = self::reduire($image);

        if ($type === IMAGETYPE_PNG) {
            // Logos et images transparentes : pas de netteté, transparence conservée
            imagealphablending($image, false);
            imagesavealpha($image, true);
            imagepng($image, $fichier, 6);

            return;
        }

        imageconvolution($image, [[0, -1, 0], [-1, 10, -1], [0, -1, 0]], 6, 0);

        if ($type === IMAGETYPE_WEBP) {
            imagewebp($image, $fichier, self::QUALITE);

            return;
        }

        imageinterlace($image, true);
        imagejpeg($image, $fichier, self::QUALITE);
    }

    private static function reduire(GdImage $image): GdImage
    {
        $largeur = imagesx($image);
        $hauteur = imagesy($image);

        if (max($largeur, $hauteur) <= self::COTE_MAX) {
            return $image;
        }

        $ratio = self::COTE_MAX / max($largeur, $hauteur);
        $reduite = imagecreatetruecolor((int) round($largeur * $ratio), (int) round($hauteur * $ratio));
        imagealphablending($reduite, false);
        imagesavealpha($reduite, true);
        imagecopyresampled($reduite, $image, 0, 0, 0, 0, imagesx($reduite), imagesy($reduite), $largeur, $hauteur);

        return $reduite;
    }

    private static function remettreALEndroit(GdImage $image, int $orientation): GdImage
    {
        return match ($orientation) {
            2 => tap($image, fn ($i) => imageflip($i, IMG_FLIP_HORIZONTAL)),
            3 => imagerotate($image, 180, 0),
            4 => tap($image, fn ($i) => imageflip($i, IMG_FLIP_VERTICAL)),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };
    }

    /** Orientation EXIF d'une photo JPEG (1 = à l'endroit), lue sans dépendre de l'extension exif. */
    private static function orientation(string $fichier): int
    {
        if (function_exists('exif_read_data')) {
            return (int) (@exif_read_data($fichier)['Orientation'] ?? 1);
        }

        $donnees = (string) file_get_contents($fichier, false, null, 0, 65536);
        $debut = strpos($donnees, "Exif\0\0");
        if ($debut === false) {
            return 1;
        }

        $tiff = substr($donnees, $debut + 6);
        $intel = str_starts_with($tiff, 'II');
        $lire = function (int $position, int $taille) use ($tiff, $intel): int {
            $octets = substr($tiff, $position, $taille);
            if (strlen($octets) < $taille) {
                return 0;
            }
            $format = $taille === 2 ? ($intel ? 'v' : 'n') : ($intel ? 'V' : 'N');

            return unpack($format, $octets)[1];
        };

        $repertoire = $lire(4, 4);
        for ($i = 0, $entrees = $lire($repertoire, 2); $i < $entrees; $i++) {
            $entree = $repertoire + 2 + $i * 12;
            if ($lire($entree, 2) === 0x0112) {
                return $lire($entree + 8, 2);
            }
        }

        return 1;
    }
}
