<?php

namespace App\Http\Controllers\Admin;

use App\Models\Abonne;
use App\Models\Lettre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AbonneController extends Controller
{
    public function index(Request $request): View
    {
        $abonnes = Abonne::query()
            ->when($request->query('q'), fn ($q, $recherche) => $q->where('email', 'like', "%{$recherche}%"))
            ->when($request->query('etat') === 'desabonnes', fn ($q) => $q->whereNotNull('desabonne_le'), fn ($q) => $q->actif())
            ->latest()
            ->paginate(self::PAR_PAGE)->withQueryString();

        return view('admin.newsletter.abonnes', [
            'abonnes' => $abonnes,
            'actifs' => Abonne::actif()->count(),
            'desabonnes' => Abonne::whereNotNull('desabonne_le')->count(),
            'lettres' => Lettre::latest()->take(5)->get(),
        ]);
    }

    /** Liste des abonnés actifs au format CSV (Excel), pour un outil d'e-mailing. */
    public function export(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $sortie = fopen('php://output', 'w');
            fwrite($sortie, "\xEF\xBB\xBF");
            fputcsv($sortie, ['E-mail', 'Langue', 'Inscrit le'], ';');

            Abonne::actif()->orderBy('created_at')->each(function (Abonne $abonne) use ($sortie) {
                fputcsv($sortie, [$abonne->email, $abonne->locale, $abonne->created_at->format('d/m/Y')], ';');
            });

            fclose($sortie);
        }, 'abonnes-newsletter-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function destroy(Abonne $abonne): RedirectResponse
    {
        $abonne->delete();

        return back()->with('succes', 'Abonné supprimé.');
    }
}
