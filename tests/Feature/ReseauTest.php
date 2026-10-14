<?php

namespace Tests\Feature;

use App\Models\DemandePartenariat;
use App\Models\Evenement;
use App\Models\Ferme;
use App\Models\Partenaire;
use App\Models\Region;
use App\Models\Ressource;
use App\Models\User;
use Database\Seeders\ContenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReseauTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ContenuSeeder::class);
    }

    public function test_les_fermes_sont_rattachees_a_leur_region_d_apres_leur_prefecture(): void
    {
        $this->assertSame('plateaux', Ferme::where('slug', 'cadete')->sole()->region->slug);
        $this->assertSame('centrale', Ferme::where('slug', 'ma-joie')->sole()->region->slug);
        // « préfecture des Lacs », « préfecture d’Avé », « préfecture de Tône »
        $this->assertSame('maritime', Ferme::where('slug', 'groupe-avidja')->sole()->region->slug);
        $this->assertSame('maritime', Ferme::where('slug', 'academie-paysanne-nenonene')->sole()->region->slug);
        $this->assertSame('savanes', Ferme::where('slug', 'lampougue')->sole()->region->slug);
        $this->assertSame(0, Ferme::whereNull('region_id')->count());
    }

    public function test_pages_des_sections_regionales(): void
    {
        $centrale = Region::where('slug', 'centrale')->sole();

        $this->get(route('regions.index'))->assertOk()->assertSee('Région Centrale')->assertSee('Région des Savanes')
            // Pluriel en français (sans clé française, trans_choice() utiliserait la traduction anglaise)
            ->assertSee('12 fermes écoles');
        $this->get(route('regions.show', 'centrale'))->assertOk()
            ->assertSee($centrale->presentation[0])
            ->assertSee('Ma Joie');

        // Région sans présentation : la page invite la section à transmettre ses informations
        $this->get(route('regions.show', 'savanes'))->assertOk()->assertSee('en cours de rédaction');

        $this->get(route('regions.index', ['lang' => 'en']))->assertSee('12 farm schools');
    }

    public function test_les_sections_regionales_et_les_axes_strategiques_sont_dans_le_menu_le_reseau(): void
    {
        $this->get(route('contact'))->assertOk()->assertSeeInOrder([
            '>Le Réseau</a>',
            'href="'.route('axes').'">Axes stratégiques',
            'href="'.route('regions.index').'">Sections régionales',
            'href="'.route('partenaires.index').'">Partenaires',
        ], false);

        $this->get(route('axes'))->assertOk()
            ->assertSee('Nos axes stratégiques')
            ->assertSeeInOrder([
                'Axe stratégique 1', 'Faciliter l’accès aux facteurs de production et aux financements adaptés',
                'Axe stratégique 4', 'Développer des stratégies de valorisation des produits agroécologiques et la résilience des jeunes agriculteurs',
            ]);
        $this->get(route('axes', ['lang' => 'en']))->assertSee('Strategic focus area 2');
    }

    public function test_la_presentation_du_colloque_far_est_conservee_dans_les_ressources(): void
    {
        $presentation = Ressource::where('fichier', Ressource::PRESENTATION_REJEPPAT)->sole();
        $this->assertFileExists(public_path('uploads/'.Ressource::PRESENTATION_REJEPPAT));

        $this->get(route('espace-jeunes.ressources', ['categorie' => 'rapport']))->assertOk()
            ->assertSee('Présentation du REJEPPAT – Colloque FAR sur l’insertion des jeunes (2014)');
        $this->get(route('axes'))->assertSee(route('espace-jeunes.ressources.telecharger', $presentation));
        $this->get(route('espace-jeunes.ressources.telecharger', $presentation))->assertOk()->assertDownload();
    }

    public function test_les_sections_regionales_sont_mises_en_avant_sur_l_accueil(): void
    {
        // Section sans ferme école rattachée
        Ferme::where('region_id', Region::where('slug', 'savanes')->value('id'))->update(['region_id' => null]);

        $kara = Region::where('slug', 'kara')->sole();
        $kara->update([
            'gouverneurs' => [['nom' => 'Essozimna Pali', 'fonction' => 'Gouverneur de la section Kara', 'telephone' => null, 'photo' => null]],
            'activites' => ['Assemblée générale de la section en novembre'],
        ]);
        Evenement::create(['slug' => 'foire-kara', 'titre' => 'Foire régionale de Kara', 'type' => 'foire', 'region_id' => $kara->id, 'debut' => now()->addWeek(), 'lieu' => 'Kara', 'description' => ['Texte']]);
        Evenement::create(['slug' => 'atelier-passe', 'titre' => 'Atelier déjà passé', 'type' => 'atelier', 'region_id' => $kara->id, 'debut' => now()->subMonth(), 'lieu' => 'Kara', 'description' => ['Texte']]);

        $this->get(route('home'))->assertOk()
            ->assertSee('Nos sections régionales')
            ->assertSee('Essozimna Pali')
            ->assertSee('Assemblée générale de la section en novembre')
            ->assertSee('Foire régionale de Kara')
            ->assertDontSee('Atelier déjà passé')
            // Région sans gouverneur renseigné
            ->assertSee('Le gouverneur de la section sera bientôt présenté.')
            // Fermes écoles de chaque section, à jour d'après leur rattachement
            ->assertSeeInOrder(['panneau-region-plateaux', 'Fermes écoles de la section', 'C.A.DE.T.E', 'panneau-region-centrale'], false)
            ->assertSee('Aucune ferme école n’est encore rattachée à cette section.');

        $this->get(route('regions.show', 'kara'))->assertSee('Essozimna Pali')->assertSee('Foire régionale de Kara');
    }

    public function test_gouverneurs_de_la_section_saisis_dans_l_administration(): void
    {
        Storage::fake('uploads');
        $admin = User::factory()->admin()->create();
        $region = Region::where('slug', 'plateaux')->sole();
        $donnees = ['nom' => $region->nom, 'slug' => $region->slug, 'publie' => '1', 'activites' => "Formation en compostage\n"];

        $this->actingAs($admin)->put(route('admin.regions.update', $region), $donnees + [
            'gouverneurs' => [['nom' => 'Afi Kossi', 'fonction' => 'Gouverneure'], ['nom' => ''], ['nom' => 'Komlan Ayi']],
            'photos_gouverneurs' => [0 => UploadedFile::fake()->image('afi.jpg')],
        ])->assertRedirect(route('admin.regions.index'));

        $gouverneurs = $region->fresh()->gouverneurs;
        $this->assertSame(['Afi Kossi', 'Komlan Ayi'], array_column($gouverneurs, 'nom'));
        Storage::disk('uploads')->assertExists($gouverneurs[0]['photo']);
        $this->assertSame(['Formation en compostage'], $region->fresh()->activites);

        // Vider le nom retire le gouverneur et supprime sa photo
        $this->put(route('admin.regions.update', $region), $donnees + ['gouverneurs' => [['nom' => ''], ['nom' => 'Komlan Ayi']]]);

        $this->assertSame(['Komlan Ayi'], array_column($region->fresh()->gouverneurs, 'nom'));
        Storage::disk('uploads')->assertMissing($gouverneurs[0]['photo']);
    }

    public function test_une_region_masquee_n_est_pas_accessible(): void
    {
        Region::where('slug', 'kara')->update(['publie' => false]);

        $this->get(route('regions.show', 'kara'))->assertNotFound();
        $this->get(route('regions.index'))->assertDontSee('Région de la Kara');
    }

    public function test_page_partenaires_par_type(): void
    {
        Partenaire::where('nom', 'AFDI')->update(['publie' => false]);

        $this->get(route('partenaires.index'))->assertOk()
            ->assertSee('Partenaires institutionnels')
            ->assertSee('CTOP')
            ->assertDontSee('AFDI');
    }

    public function test_proposition_de_partenariat(): void
    {
        Mail::fake();

        $this->post(route('partenaires.store'), [
            'organisation' => 'Coopérative Agri Plus',
            'nom' => 'Komi Ayivi',
            'email' => 'komi@example.com',
            'type' => 'commercial',
            'message' => 'Nous souhaitons distribuer vos produits.',
        ])->assertRedirect(route('partenaires.index').'#devenir-partenaire')->assertSessionHas('succes');

        $demande = DemandePartenariat::sole();
        $this->assertSame('nouvelle', $demande->statut);
        $this->assertSame('commercial', $demande->type);
    }

    public function test_proposition_de_partenariat_incomplete(): void
    {
        $this->post(route('partenaires.store'), ['type' => 'inconnu'])
            ->assertSessionHasErrors([
                'organisation' => 'Ce champ est obligatoire.',
                'email' => 'Ce champ est obligatoire.',
                'type' => 'Veuillez choisir un élément dans la liste.',
            ]);

        $this->assertSame(0, DemandePartenariat::count());
    }

    public function test_gestion_des_regions_et_des_partenaires_dans_l_administration(): void
    {
        $admin = User::factory()->admin()->create();
        $savanes = Region::where('slug', 'savanes')->sole();

        $this->actingAs($admin)->put(route('admin.regions.update', $savanes), [
            'nom' => 'Région des Savanes', 'slug' => 'savanes', 'chef_lieu' => 'Dapaong',
            'presentation' => "Premier paragraphe.\nSecond paragraphe.", 'filieres' => "Sorgho\nArachide",
            'nb_cooperatives' => 24, 'responsable' => 'Lamboni', 'publie' => '1',
            'traductions' => ['en' => ['presentation' => "First paragraph.\n", 'filieres' => '']],
        ])->assertRedirect(route('admin.regions.index'));

        $savanes->refresh();
        $this->assertSame(['Premier paragraphe.', 'Second paragraphe.'], $savanes->presentation);
        $this->assertSame(['Sorgho', 'Arachide'], $savanes->filieres);
        $this->assertSame(['en' => ['presentation' => ['First paragraph.']]], $savanes->traductions);

        $this->post(route('admin.partenaires.store'), [
            'nom' => 'GIZ', 'type' => 'technique', 'site' => 'https://www.giz.de', 'publie' => '1',
        ])->assertRedirect(route('admin.partenaires.index'));
        $this->get(route('partenaires.index'))->assertSee('GIZ');

        $demande = DemandePartenariat::create(['organisation' => 'ONG', 'nom' => 'A', 'email' => 'a@example.com', 'type' => 'technique', 'message' => 'Bonjour']);
        $this->put(route('admin.partenariats.update', $demande), ['statut' => 'en_cours'])->assertRedirect();
        $this->assertSame('en_cours', $demande->fresh()->statut);
    }
}
