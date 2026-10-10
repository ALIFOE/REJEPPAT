<?php

namespace App\Http\Controllers\Admin;

use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $this->supprimerPhoto($media->fichier);
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
            'fichier' => ['nullable', 'file', 'mimes:mp3,m4a,ogg,wav', 'max:40960'],
        ] + $this->reglesTraductions(), self::MESSAGES + ['fichier.mimes' => 'Formats audio acceptés : MP3, M4A, OGG ou WAV.']);

        $media->fill([
            'titre' => $donnees['titre'],
            'type' => $donnees['type'],
            'date' => $donnees['date'],
            'description' => $donnees['description'] ?? null,
            'url' => $donnees['url'] ?? null,
            'photo' => $this->photoDemandee($request, $media, 'medias'),
            'fichier' => $this->enregistrerFichier($request->file('fichier'), 'medias/audio', $media->fichier),
            'publie' => $request->boolean('publie'),
            'traductions' => $this->traductions($request, Media::class),
        ]);

        // Chaque type a besoin de son contenu : une photo, une vidéo YouTube, un fichier ou un lien audio
        $erreur = match ($media->type) {
            'photo' => $media->photo ? null : ['photo' => 'Choisissez la photo à publier.'],
            'video' => $media->youtubeId() ? null : ['url' => 'Indiquez le lien d’une vidéo YouTube (ex. https://www.youtube.com/watch?v=…).'],
            'podcast' => $media->fichier || $media->url ? null : ['fichier' => 'Envoyez le fichier audio ou indiquez le lien d’écoute.'],
        };

        if ($erreur) {
            throw ValidationException::withMessages($erreur);
        }

        $media->save();
    }
}
