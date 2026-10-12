<?php

namespace Tests\Feature;

use App\Models\Actualite;
use App\Models\DemandeService;
use App\Models\Ferme;
use App\Models\MessageContact;
use App\Models\Offre;
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

    public function test_les_offres_s_affichent_avec_leur_icone(): void
    {
        $this->get(route('offres'))->assertOk()
            ->assertSee('REJEPPAT-Fina')
            ->assertSee('Le plaidoyer')
            ->assertSee('fas fa-bullhorn')
            ->assertDontSee('Agriculture et élevage');
    }

    public function test_la_mise_a_jour_des_offres_renomme_les_offres_existantes_et_leurs_demandes(): void
    {
        $migration = require database_path('migrations/2026_10_10_192048_mettre_a_jour_les_offres_et_services.php');
        $migration->down();
        DemandeService::create(['nom' => 'Ama', 'telephone' => '90000000', 'service' => 'Agriculture et élevage', 'objet' => 'Appui', 'besoin' => 'Appui']);

        $migration->up();

        $this->assertDatabaseHas('offres', ['titre' => 'Coaching sur le parcours entrepreneurial', 'icone' => 'fas fa-chalkboard-teacher']);
        $this->assertDatabaseHas('offres', ['titre' => 'Le plaidoyer', 'icone' => 'fas fa-bullhorn']);
        $this->assertDatabaseMissing('offres', ['titre' => 'Agriculture et élevage']);
        $this->assertSame('Coaching sur le parcours entrepreneurial', DemandeService::sole()->service);
        $this->assertSame('Advocacy', Offre::where('titre', 'Le plaidoyer')->sole()->traductions['en']['titre']);
    }

    public function test_l_ajout_des_fermes_ecoles_complete_une_base_deja_remplie(): void
    {
        $migration = require database_path('migrations/2026_10_12_135010_ajouter_fermes_ecoles_et_telephones.php');
        $migration->down();
        Ferme::where('slug', 'ma-joie')->update(['telephone' => null]);
        Ferme::where('slug', 'cadete')->update(['telephone' => '+228 91 00 00 00']);

        $migration->up();

        $this->assertSame('+228 90 71 71 76', Ferme::where('slug', 'ma-joie')->sole()->telephone);
        $this->assertSame('+228 91 00 00 00', Ferme::where('slug', 'cadete')->sole()->telephone);
        $this->assertSame('Agro-pastoralism', Ferme::where('slug', 'cata')->sole()->traductions['en']['specialite']);

        // Ferme école sans spécialité renseignée : sa fiche s'affiche avec ses contacts
        $this->get(route('fermes.show', 'lampougue'))->assertOk()
            ->assertSee('Lampougue')
            ->assertDontSee('Spécialité :')
            ->assertSee('https://wa.me/22892127669', false);
        $this->get(route('fermes.index'))->assertSee('Terre Fleurie')->assertSee('3 Mages');
    }

    public function test_un_contenu_masque_n_est_plus_visible(): void
    {
        $actualite = Actualite::first();
        $actualite->update(['publie' => false]);

        $this->get(route('actualites.show', $actualite->slug))->assertNotFound();
    }

    public function test_le_lien_partage_d_un_article_affiche_sa_photo(): void
    {
        $actualite = Actualite::where('slug', 'education-forestiere-mission-suivi-ugp-rome')->sole();

        $this->get(route('actualites.show', $actualite->slug))->assertOk()
            ->assertSee('<meta property="og:image" content="'.$actualite->visuel('-detail').'" />', false)
            ->assertSee('<meta property="og:type" content="article" />', false)
            ->assertSee('<meta property="og:title" content="Projet Éducation forestière', false);

        $this->get(route('contact'))
            ->assertSee('<meta property="og:image" content="'.asset('assets/images/rejeppat/logo/logo-rejeppat-officiel.png').'" />', false);
    }

    public function test_le_lien_partage_d_un_projet_d_une_ferme_ou_d_un_produit_affiche_sa_photo(): void
    {
        $projet = Projet::first();
        $this->get(route('projets.show', $projet->slug))
            ->assertSee('<meta property="og:image" content="'.$projet->visuel('-detail').'" />', false);

        $ferme = Ferme::first();
        $this->get(route('fermes.show', $ferme->slug))
            ->assertSee('<meta property="og:image" content="'.$ferme->visuel('-top').'" />', false);

        $ferme->update(['galerie' => ['fermes/galerie/recolte.jpg']]);
        $this->get(route('fermes.show', $ferme->slug))
            ->assertSee('<meta property="og:image" content="'.asset('uploads/fermes/galerie/recolte.jpg').'" />', false);

        $produit = Produit::first();
        $this->get(route('boutique.show', $produit->slug))
            ->assertSee('<meta property="og:image" content="'.$produit->visuel('-detail').'" />', false);
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
            'service' => 'Coaching sur le parcours entrepreneurial',
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
