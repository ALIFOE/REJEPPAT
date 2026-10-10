<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Médiathèque : photos, vidéos et podcasts. */
class MediathequeController extends Controller
{
    public function index(Request $request): View
    {
        $type = array_key_exists((string) $request->query('type'), Media::TYPES) ? $request->query('type') : null;

        return view('mediatheque.index', [
            'medias' => Media::publie()->when($type, fn ($q) => $q->where('type', $type))->paginate(12)->withQueryString(),
            'compteurs' => Media::where('publie', true)->selectRaw('type, COUNT(*) as total')->groupBy('type')->pluck('total', 'type'),
            'type' => $type,
        ]);
    }
}
