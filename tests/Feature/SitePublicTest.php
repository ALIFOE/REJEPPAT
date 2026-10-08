<?php

namespace Tests\Feature;

use App\Models\Actualite;
use App\Models\DemandeService;
use App\Models\Ferme;
use App\Models\MessageContact;
use App\Models\Produit;
use App\Models\Projet;
use Database\Seeders\ContenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitePublicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ContenuSeeder::class);
    }

    public function test_les_pages_du_site_s_affichent_depuis_la_base(): void
    {
        $pages = [
            route('home'),
            route('offres'),
            route('demande'),
            route('fermes.index'),
            route('fermes.show', Ferme::first()->slug),
            route('projets.index'),
            route('projets.show', Projet::first()->slug),
            route('actualites.index'),
            route('actualites.show', Actualite::first()->slug),
            route('boutique.index'),
            route('boutique.show', Produit::first()->slug),
            route('panier.index'),
            route('commande.suivi'),
            route('contact'),
        ];

        foreach ($pages as $page) {
            $this->get($page)->assertOk();
        }
    }

    public function test_un_contenu_masque_n_est_plus_visible(): void
    {
        $actualite = Actualite::first();
        $actualite->update(['publie' => false]);

        $this->get(route('actualites.show', $actualite->slug))->assertNotFound();
    }

    public function test_la_lecture_d_un_article_est_comptee(): void
    {
        $actualite = Actualite::first();

        $this->get(route('actualites.show', $actualite->slug));

        $this->assertSame(1, $actualite->fresh()->vues);
    }

    public function test_une_demande_de_service_est_enregistree(): void
    {
        $this->post(route('demande.envoyer'), [
            'nom' => 'Ama Dossou',
            'telephone' => '90 00 00 00',
            'service' => 'Agriculture et élevage',
            'objet' => 'Formation en maraîchage',
            'besoin' => 'Nous souhaitons former 20 jeunes.',
            'accord' => '1',
        ])->assertRedirect()->assertSessionHas('succes');

        $this->assertDatabaseHas('demandes_services', ['nom' => 'Ama Dossou', 'statut' => 'nouvelle']);
    }

    public function test_un_message_de_contact_est_enregistre(): void
    {
        $this->post(route('contact.envoyer'), [
            'nom' => 'Kossi',
            'email' => 'kossi@example.com',
            'objet' => 'Soutien',
            'message' => 'Bonjour',
        ])->assertRedirect()->assertSessionHas('succes');

        $this->assertSame(1, MessageContact::nonLu()->count());
        $this->assertSame(0, DemandeService::count());
    }
}
