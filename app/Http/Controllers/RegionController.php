<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Support\Contenu;
use Illuminate\View\View;

class RegionController extends Controller
{
    public function index(): View
    {
        return view('regions.index', [
            'regions' => Region::publie()->withCount(['fermes' => fn ($q) => $q->publie()])->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $region = Region::publie()->where('slug', $slug)->firstOrFail();

        return view('regions.show', [
            'region' => $region,
            'regions' => Contenu::regions(),
            'fermes' => $region->fermes()->publie()->orderBy('ordre')->get(),
            'membres' => $region->membresAnnuaire()->publie()->orderBy('nom')->take(6)->get(),
        ]);
    }
}
