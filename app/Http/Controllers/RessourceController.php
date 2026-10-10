<?php

namespace App\Http\Controllers;

use App\Models\Ressource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RessourceController extends Controller
{
    public function index(Request $request): View
    {
        $categorie = array_key_exists((string) $request->query('categorie'), Ressource::CATEGORIES) ? $request->query('categorie') : null;
        $ressources = Ressource::publie()->get();

        return view('espace-jeunes.ressources', [
            'ressources' => $ressources->when($categorie, fn ($liste) => $liste->where('categorie', $categorie))->values(),
            'compteurs' => $ressources->countBy('categorie'),
            'categorie' => $categorie,
        ]);
    }

    /** Téléchargement d'un document publié, avec comptage des téléchargements. */
    public function telecharger(Ressource $ressource): StreamedResponse
    {
        abort_unless($ressource->publie && Storage::disk('uploads')->exists($ressource->fichier), 404);

        Ressource::withoutTimestamps(fn () => $ressource->increment('telechargements'));

        return Storage::disk('uploads')->download(
            $ressource->fichier,
            Str::slug($ressource->titre).'.'.pathinfo($ressource->fichier, PATHINFO_EXTENSION)
        );
    }
}
