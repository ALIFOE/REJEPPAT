<?php

namespace Tests\Feature;

use App\Models\DemandePartenariat;
use App\Models\Ferme;
use App\Models\Partenaire;
use App\Models\Region;
use App\Models\User;
use Database\Seeders\ContenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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
    }

    public function test_pages_des_sections_regionales(): void
    {
        $centrale = Region::where('slug', 'centrale')->sole();

        $this->get(route('regions.index'))->assertOk()->assertSee('Région Centrale')->assertSee('Région des Savanes')
            // Pluriel en français (sans clé française, trans_choice() utiliserait la traduction anglaise)
            ->assertSee('10 fermes écoles');
        $this->get(route('regions.show', 'centrale'))->assertOk()
            ->assertSee($centrale->presentation[0])
            ->assertSee('Ma Joie');

        // Région sans présentation : la page invite la section à transmettre ses informations
        $this->get(route('regions.show', 'savanes'))->assertOk()->assertSee('en cours de rédaction');

        $this->get(route('regions.index', ['lang' => 'en']))->assertSee('10 farm schools');
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
