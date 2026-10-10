<?php

namespace Tests\Feature;

use App\Models\Produit;
use App\Models\Projet;
use App\Models\User;
use Database\Seeders\ContenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LangueTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ContenuSeeder::class);
    }

    public function test_le_site_passe_en_anglais_et_revient_en_francais(): void
    {
        $this->get(route('contact'))->assertSee('lang="fr"', false)->assertSee('Accueil');

        $this->from(route('boutique.index'))->get(route('langue', 'en'))->assertRedirect(route('boutique.index'));
        $this->get(route('boutique.index'))->assertSee('lang="en"', false)->assertSee('Home')->assertDontSee('>Accueil<', false);

        $this->get(route('langue', 'fr'));
        $this->get(route('boutique.index'))->assertSee('Accueil');
    }

    public function test_une_langue_inconnue_est_refusee(): void
    {
        $this->get(route('langue', 'de'))->assertNotFound();
    }

    public function test_un_lien_partage_avec_lang_en_ouvre_la_version_anglaise(): void
    {
        $this->get(route('boutique.index', ['lang' => 'en']))->assertSee('lang="en"', false);
        $this->get(route('contact'))->assertSee('lang="en"', false);
    }

    public function test_les_contenus_traduits_s_affichent_en_anglais_avec_repli_sur_le_francais(): void
    {
        $produit = Produit::where('slug', 'carotte-biologique')->sole();
        $produit->update(['traductions' => ['en' => ['nom' => 'Organic carrot']]]);

        $this->get(route('boutique.show', $produit->slug).'?lang=en')->assertSee('Organic carrot')
            // Résumé non traduit : le texte français reste affiché
            ->assertSee(e($produit->resume), false);
        $this->get(route('boutique.show', $produit->slug).'?lang=fr')->assertSee('Carotte biologique')->assertDontSee('Organic carrot');
    }

    public function test_la_version_anglaise_est_saisie_dans_l_administration(): void
    {
        $admin = User::factory()->admin()->create();
        $projet = Projet::first();

        $this->actingAs($admin)->put(route('admin.projets.update', $projet), [
            'titre' => $projet->titre, 'titre_court' => $projet->titre_court, 'slug' => $projet->slug, 'icone' => $projet->icone,
            'categories' => $projet->categories, 'date' => '2026-01-01', 'statut' => 'en_cours', 'resume' => $projet->resume,
            'description' => 'Texte', 'publie' => '1',
            'traductions' => ['en' => ['titre_court' => 'Youth farm schools', 'actions' => "Training | 300 young people trained\n", 'resume' => '  ']],
        ])->assertRedirect(route('admin.projets.index'));

        $this->assertSame([
            'en' => [
                'titre_court' => 'Youth farm schools',
                'actions' => [['titre' => 'Training', 'texte' => '300 young people trained']],
            ],
        ], $projet->fresh()->traductions);
    }

    public function test_l_administration_reste_en_francais(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('langue', 'en'));
        $this->get(route('admin.tableau'))->assertSee('lang="fr"', false);
    }
}
