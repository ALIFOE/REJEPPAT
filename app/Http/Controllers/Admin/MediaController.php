<?php

namespace App\Http\Controllers\Admin;

use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(Request $request): View
    {
        $medias = Media::query()
            ->when($request->query('type'), fn ($q, $type) => $q->where('type', $type))
            ->orderByDesc('date')->orderByDesc('id')
            ->paginate(self::PAR_PAGE)->withQueryString();

        return view('admin.medias.index', [
            'medias' => $medias,
            'compteurs' => Media::selectRaw('type, COUNT(*) as total')->groupBy('type')->pluck('total', 'type'),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.medias.form', ['media' => new Media([
            'type' => array_key_exists($request->query('type'), Media::TYPES) ? $request->query('type') : 'photo',
            'date' => now(),
            'publie' => true,
        ])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->enregistrer($request, new Media);

        return redirect()->route('admin.medias.index')->with('succes', 'Média ajouté à la médiathèque.');
    }

    public function edit(Media $media): View
    {
        return view('admin.medias.form', compact('media'));
    }

    public function update(Request $request, Media $media): RedirectResponse
    {
        $this->enregistrer($request, $media);

        return redirect()->route('admin.medias.index')->with('succes', 'Média mis à jour.');
    }

    public function destroy(Media $media): RedirectResponse
    {
        $this->supprimerPhoto($media->photo);
        $this->supprimerFichierMedia($media);
        $media->delete();

        return redirect()->route('admin.medias.index')->with('succes', 'Média supprimé.');
    }

    private function enregistrer(Request $request, Media $media): void
    {
        $donnees = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(array_keys(Media::TYPES))],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:2000'],
            'url' => ['nullable', 'url:http,https', 'max:255'],
            'photo' => self::REGLE_IMAGE,
            'fichier' => $request->input('type') === 'video'
                ? ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:307200']
                : ['nullable', 'file', 'mimes:mp3,m4a,ogg,wav', 'max:40960'],
            'ordre' => ['nullable', 'integer', 'min:0'],
        ] + $this->reglesTraductions(), self::MESSAGES + [
            'fichier.mimes' => 'Formats audio acceptés : MP3, M4A, OGG ou WAV.',
            'fichier.mimetypes' => 'Formats vidéo acceptés : MP4 ou WEBM.',
        ]);

        $media->fill([
            'titre' => $donnees['titre'],
            'type' => $donnees['type'],
            'date' => $donnees['date'],
            'description' => $donnees['description'] ?? null,
            'url' => $donnees['url'] ?? null,
            'photo' => $this->photoDemandee($request, $media, 'medias'),
            'fichier' => $this->fichierDemande($request, $media, $donnees['type']),
            'accueil' => $donnees['type'] === 'video' && $request->boolean('accueil'),
            'ordre' => (int) ($donnees['ordre'] ?? 0),
            'publie' => $request->boolean('publie'),
            'traductions' => $this->traductions($request, Media::class),
        ]);

        // Chaque type a besoin de son contenu : une photo, une vidéo YouTube, un fichier ou un lien audio
        $erreur = match ($media->type) {
            'photo' => $media->photo ? null : ['photo' => 'Choisissez la photo à publier.'],
            'video' => $media->youtubeId() || $media->fichier ? null : ['url' => 'Envoyez le fichier vidéo ou indiquez le lien d’une vidéo YouTube (ex. https://www.youtube.com/watch?v=…).'],
            'podcast' => $media->fichier || $media->url ? null : ['fichier' => 'Envoyez le fichier audio ou indiquez le lien d’écoute.'],
        };

        if ($erreur) {
            throw ValidationException::withMessages($erreur);
        }

        $media->save();
    }

    /**
     * Fichier du média : une vidéo est stockée hors du dossier public (diffusée par un lien signé),
     * un podcast dans public/uploads. Changer de type ou de fichier supprime l'ancien.
     */
    private function fichierDemande(Request $request, Media $media, string $type): ?string
    {
        $nouveau = $request->file('fichier');
        $changeDeType = $media->exists && $media->fichier && $media->getOriginal('type') !== $type;

        if (! $nouveau && ! $changeDeType && ! $request->boolean('retirer_fichier')) {
            return $media->fichier;
        }

        $this->supprimerFichierMedia($media);

        if (! $nouveau) {
            return null;
        }

        return $type === 'video'
            ? $nouveau->store(Media::DOSSIER_VIDEOS, Media::DISQUE_VIDEOS)
            : $nouveau->store('medias/audio', 'uploads');
    }

    private function supprimerFichierMedia(Media $media): void
    {
        if (! $media->getOriginal('fichier')) {
            return;
        }

        $media->getOriginal('type') === 'video'
            ? Storage::disk(Media::DISQUE_VIDEOS)->delete($media->getOriginal('fichier'))
            : $this->supprimerPhoto($media->getOriginal('fichier'));
    }
}
