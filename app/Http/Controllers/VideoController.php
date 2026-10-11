<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Diffusion des vidéos envoyées sur le site, stockées hors du dossier public.
 *
 * Le lien est signé et temporaire (Media::lienLecture) et la vidéo n'est servie qu'au lecteur
 * de la page : ouvrir le lien directement dans un onglet est refusé. Le lecteur du site n'a pas
 * de bouton de téléchargement. Une vidéo lue dans un navigateur reste techniquement copiable par
 * un utilisateur averti : ces protections empêchent le téléchargement simple et le partage du lien.
 */
class VideoController extends Controller
{
    public function lecture(Request $request, Media $media): BinaryFileResponse
    {
        // Une vidéo non publiée reste visible dans l'administration (aperçu du formulaire)
        abort_unless(($media->publie || $request->user()?->estAdministrateur()) && $media->estVideoProtegee(), 404);

        // Navigation directe vers le fichier (nouvel onglet, lien copié) : seule la balise <video> peut le lire
        abort_if(in_array($request->header('Sec-Fetch-Dest'), ['document', 'iframe', 'embed', 'object'], true), 403);

        $disque = Storage::disk(Media::DISQUE_VIDEOS);
        abort_unless($disque->exists($media->fichier), 404);

        return response()->file($disque->path($media->fichier), [
            'Content-Type' => $disque->mimeType($media->fichier) ?: 'video/mp4',
            'Content-Disposition' => 'inline',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
            'X-Robots-Tag' => 'noindex',
        ]);
    }
}
