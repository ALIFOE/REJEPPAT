<?php

namespace App\Http\Controllers\Admin;

use App\Mail\LettreInformation;
use App\Models\Abonne;
use App\Models\Lettre;
use App\Support\TexteRiche;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class LettreController extends Controller
{
    public function index(): View
    {
        return view('admin.newsletter.lettres', [
            'lettres' => Lettre::latest()->paginate(self::PAR_PAGE),
            'actifs' => Abonne::actif()->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.newsletter.lettre', ['lettre' => new Lettre]);
    }

    public function store(Request $request): RedirectResponse
    {
        $lettre = Lettre::create($this->donnees($request));

        return redirect()->route('admin.lettres.edit', $lettre)->with('succes', 'Brouillon enregistré. Relisez-le puis envoyez-le aux abonnés.');
    }

    public function edit(Lettre $lettre): View
    {
        return view('admin.newsletter.lettre', [
            'lettre' => $lettre,
            'actifs' => Abonne::actif()->count(),
        ]);
    }

    public function update(Request $request, Lettre $lettre): RedirectResponse
    {
        abort_if($lettre->estEnvoyee(), 403, 'Cette lettre a déjà été envoyée.');

        $lettre->update($this->donnees($request));

        return back()->with('succes', 'Brouillon mis à jour.');
    }

    public function destroy(Lettre $lettre): RedirectResponse
    {
        $lettre->delete();

        return redirect()->route('admin.lettres.index')->with('succes', 'Lettre supprimée.');
    }

    /** Envoie la lettre à chaque abonné actif (un e-mail par abonné, avec son lien de désabonnement). */
    public function envoyer(Lettre $lettre): RedirectResponse
    {
        abort_if($lettre->estEnvoyee(), 403, 'Cette lettre a déjà été envoyée.');

        $envoyes = 0;
        Abonne::actif()->each(function (Abonne $abonne) use ($lettre, &$envoyes) {
            Mail::to($abonne->email)->queue(new LettreInformation($lettre, $abonne));
            $envoyes++;
        });

        $lettre->update(['envoyee_le' => now(), 'destinataires' => $envoyes]);

        return redirect()->route('admin.lettres.index')->with('succes', "Lettre envoyée à {$envoyes} abonné".($envoyes > 1 ? 's' : '').'.');
    }

    private function donnees(Request $request): array
    {
        $donnees = $request->validate([
            'sujet' => ['required', 'string', 'max:200'],
            'contenu' => ['required', 'string', 'max:200000'],
        ], self::MESSAGES);

        return ['sujet' => $donnees['sujet'], 'contenu' => TexteRiche::nettoyer($donnees['contenu'])];
    }
}
