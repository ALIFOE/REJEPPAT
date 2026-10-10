<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Opportunite;
use App\Models\Region;
use App\Models\Ressource;
use App\Models\Temoignage;
use App\Support\Photo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Espace Jeunes / Membres : annuaire des membres, témoignages et success stories.
 * Les opportunités et les ressources ont leurs propres contrôleurs.
 */
class EspaceJeunesController extends Controller
{
    private const PAR_PAGE = 12;

    public function index(): View
    {
        return view('espace-jeunes.index', [
            'membres' => Membre::publie()->count(),
            'opportunites' => Opportunite::publie()->ouverte()->latest()->take(3)->get(),
            'ressources' => Ressource::publie()->count(),
            'story' => Temoignage::publie()->where('type', 'success_story')->first(),
        ]);
    }

    public function annuaire(Request $request): View
    {
        $membres = Membre::publie()->with('region')
            ->when($request->query('q'), fn ($q, $recherche) => $q->where(fn ($q) => $q
                ->where('nom', 'like', "%{$recherche}%")
                ->orWhere('organisation', 'like', "%{$recherche}%")
                ->orWhere('localite', 'like', "%{$recherche}%")))
            ->when($request->query('region'), fn ($q, $region) => $q->whereRelation('region', 'slug', $region))
            ->when(array_key_exists((string) $request->query('filiere'), Membre::FILIERES), fn ($q) => $q->where('filiere', $request->query('filiere')))
            ->orderBy('nom')
            ->paginate(self::PAR_PAGE)->withQueryString();

        return view('espace-jeunes.annuaire', [
            'membres' => $membres,
            'regions' => Region::publie()->get(),
        ]);
    }

    public function membre(string $slug): View
    {
        return view('espace-jeunes.membre', [
            'membre' => Membre::publie()->with('region')->where('slug', $slug)->firstOrFail(),
        ]);
    }

    public function rejoindre(): View
    {
        return view('espace-jeunes.rejoindre', [
            'regions' => Region::publie()->get(),
        ]);
    }

    /** Profil proposé par un membre : il apparaît dans l'annuaire après validation par l'équipe. */
    public function inscrire(Request $request): RedirectResponse
    {
        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:160'],
            'organisation' => ['nullable', 'string', 'max:160'],
            'region_id' => ['nullable', 'integer', Rule::exists(Region::class, 'id')->where('publie', true)],
            'localite' => ['nullable', 'string', 'max:120'],
            'filiere' => ['required', Rule::in(array_keys(Membre::FILIERES))],
            'presentation' => ['required', 'string', 'max:3000'],
            'telephone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'accord' => ['accepted'],
        ], $this->messages(self::MESSAGES_FORMULAIRE + ['accord.accepted' => 'Vous devez accepter la publication de votre profil.']));

        $photo = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store('membres', 'uploads');
            Photo::optimiser($photo);
        }

        $membre = Membre::create([
            'nom' => $donnees['nom'],
            'slug' => $this->slugDisponible($donnees['nom']),
            'organisation' => $donnees['organisation'] ?? null,
            'region_id' => $donnees['region_id'] ?? null,
            'localite' => $donnees['localite'] ?? null,
            'filiere' => $donnees['filiere'],
            'presentation' => collect(preg_split('/\R/', $donnees['presentation']))->map(fn ($l) => trim($l))->filter()->values()->all(),
            'telephone' => $donnees['telephone'] ?? null,
            'email' => $donnees['email'] ?? null,
            'photo' => $photo,
            'publie' => false,
        ]);

        rescue(fn () => Mail::raw(
            "Nouveau profil proposé pour l'annuaire des membres du REJEPPAT\n\n"
                ."Nom : {$membre->nom}\nOrganisation : ".($membre->organisation ?: '-')."\n"
                .'Filière : '.Membre::FILIERES[$membre->filiere]."\n\n"
                .'Valider ou modifier le profil : '.route('admin.membres.edit', $membre),
            fn ($message) => $message->to(config('rejeppat.emails.0'))->subject('[Site REJEPPAT] Profil à valider : '.$membre->nom)
        ));

        return redirect()->route('espace-jeunes.rejoindre')
            ->with('succes', __('Merci ! Votre profil a bien été envoyé. Il apparaîtra dans l’annuaire après validation par notre équipe.'));
    }

    public function temoignages(): View
    {
        $temoignages = Temoignage::publie()->with('ferme')->get();

        return view('espace-jeunes.temoignages', [
            'stories' => $temoignages->where('type', 'success_story')->values(),
            'temoignages' => $temoignages->where('type', '!=', 'success_story')->values(),
        ]);
    }

    private function slugDisponible(string $nom): string
    {
        $base = Str::slug($nom) ?: Str::lower(Str::random(6));
        $slug = $base;
        $i = 2;

        while (Membre::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
