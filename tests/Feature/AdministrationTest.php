<?php

namespace Tests\Feature;

use App\Models\Actualite;
use App\Models\Commande;
use App\Models\DemandeService;
use App\Models\Ferme;
use App\Models\MessageContact;
use App\Models\Offre;
use App\Models\Produit;
use App\Models\Projet;
use App\Models\User;
use Database\Seeders\ContenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdministrationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ContenuSeeder::class);
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_l_administration_est_protegee(): void
    {
        $this->get(route('admin.tableau'))->assertRedirect(route('admin.connexion'));

        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get(route('admin.tableau'))->assertRedirect(route('admin.connexion'));
    }

    public function test_connexion_administrateur(): void
    {
        $this->post(route('admin.connexion'), ['email' => $this->admin->email, 'password' => 'password'])
            ->assertRedirect(route('admin.tableau'));
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_tous_les_ecrans_s_affichent(): void
    {
        $commande = $this->commande();
        $demande = DemandeService::create(['nom' => 'A', 'telephone' => '90000000', 'service' => 'Autre besoin', 'objet' => 'Objet', 'besoin' => 'Texte']);
        $message = MessageContact::create(['nom' => 'B', 'email' => 'b@example.com', 'objet' => 'Soutien', 'message' => 'Bonjour']);

        $ecrans = [
            route('admin.tableau'),
            route('admin.commandes.index'), route('admin.commandes.show', $commande),
            route('admin.produits.index'), route('admin.produits.create'), route('admin.produits.edit', Produit::first()),
            route('admin.demandes.index'), route('admin.demandes.show', $demande),
            route('admin.messages.index'), route('admin.messages.show', $message),
            route('admin.actualites.index'), route('admin.actualites.create'), route('admin.actualites.edit', Actualite::first()),
            route('admin.offres.index'), route('admin.offres.create'), route('admin.offres.edit', Offre::first()),
            route('admin.fermes.index'), route('admin.fermes.create'), route('admin.fermes.edit', Ferme::first()),
            route('admin.projets.index'), route('admin.projets.create'), route('admin.projets.edit', Projet::first()),
            route('admin.utilisateurs.index'), route('admin.utilisateurs.create'), route('admin.utilisateurs.edit', $this->admin),
        ];

        foreach ($ecrans as $ecran) {
            $this->actingAs($this->admin)->get($ecran)->assertOk();
        }

        $this->assertNotNull($message->fresh()->lu_le);
    }

    public function test_creer_un_article_avec_photo(): void
    {
        Storage::fake('uploads');

        $this->actingAs($this->admin)->post(route('admin.actualites.store'), [
            'title' => 'Nouvelle formation à Kara',
            'date' => '2026-10-08',
            'categories' => ['evenements'],
            'corps' => '<h2>Programme</h2><p>Premier <strong>paragraphe</strong>.</p><p>Second paragraphe.</p><script>alert(1)</script>',
            'tags' => 'Kara, Formation',
            'photo' => UploadedFile::fake()->image('photo.jpg'),
            'publie' => '1',
        ])->assertRedirect(route('admin.actualites.index'));

        $article = Actualite::where('slug', 'nouvelle-formation-a-kara')->sole();
        $this->assertSame(['Programme', 'Premier paragraphe.', 'Second paragraphe.'], $article->content);
        $this->assertStringContainsString('<strong>paragraphe</strong>', $article->corps);
        $this->assertStringNotContainsString('script', $article->corps);
        $this->assertSame(['Kara', 'Formation'], $article->tags);
        Storage::disk('uploads')->assertExists($article->photo);

        $this->get(route('actualites.show', $article->slug))->assertOk()->assertSee('<h2>Programme</h2>', false);
    }

    public function test_envoi_d_une_image_depuis_l_editeur(): void
    {
        Storage::fake('uploads');

        $reponse = $this->actingAs($this->admin)->postJson(route('admin.actualites.image'), ['file' => UploadedFile::fake()->image('photo.png')]);

        $reponse->assertOk()->assertJsonPath('location', fn ($url) => str_starts_with($url, '/uploads/actualites/contenu/'));
    }

    public function test_gerer_produit_offre_ferme_et_projet(): void
    {
        $this->actingAs($this->admin);

        $this->post(route('admin.produits.store'), [
            'nom' => 'Miel de Kpété', 'categorie' => 'transformes', 'prix' => 3500, 'stock' => 12,
            'resume' => 'Miel local', 'description' => 'Miel récolté à la ferme Ma Joie.', 'actif' => '1',
        ])->assertRedirect(route('admin.produits.index'));
        $this->get(route('boutique.show', 'miel-de-kpete'))->assertOk()->assertSee('12 en stock');

        $this->post(route('admin.offres.store'), ['titre' => 'Appui conseil', 'texte' => 'Conseil', 'actif' => '1'])->assertRedirect();
        $this->get(route('demande'))->assertSee('Appui conseil');

        $ferme = Ferme::first();
        $this->put(route('admin.fermes.update', $ferme), [
            'nom' => $ferme->nom, 'slug' => $ferme->slug, 'specialite' => 'Apiculture', 'modules' => "Module A\nModule B",
            'latitude' => '8.5', 'longitude' => '0.97', 'adresse' => 'Kpété', 'publie' => '1',
        ])->assertRedirect(route('admin.fermes.index'));
        $this->assertSame(['lat' => 8.5, 'lng' => 0.97, 'adresse' => 'Kpété'], $ferme->fresh()->carte);

        $projet = Projet::first();
        $this->put(route('admin.projets.update', $projet), [
            'titre' => $projet->titre, 'titre_court' => $projet->titre_court, 'slug' => $projet->slug, 'icone' => 'icon-farmer',
            'categories' => ['formation'], 'date' => '2026-01-01', 'resume' => 'Résumé', 'description' => 'Texte',
            'actions' => "Action 1 | Détail 1", 'publie' => '0',
        ])->assertRedirect(route('admin.projets.index'));
        $this->assertSame([['titre' => 'Action 1', 'texte' => 'Détail 1']], $projet->fresh()->actions);
        $this->get(route('projets.show', $projet->slug))->assertNotFound();
    }

    public function test_annuler_une_commande_remet_le_stock(): void
    {
        $commande = $this->commande();
        $produit = $commande->lignes->first()->produit;
        $stock = $produit->stock;

        $this->actingAs($this->admin)->put(route('admin.commandes.update', $commande), [
            'statut' => 'annulee', 'statut_paiement' => 'en_attente',
        ])->assertRedirect();

        $this->assertSame($stock + 2, $produit->fresh()->stock);
    }

    private function commande(): Commande
    {
        $produit = Produit::first();
        $produit->update(['stock' => 5]);

        $commande = Commande::create([
            'numero' => Commande::nouveauNumero(), 'nom' => 'Client', 'telephone' => '90000000', 'mode_livraison' => 'retrait',
            'sous_total' => $produit->prix * 2, 'total' => $produit->prix * 2, 'mode_paiement' => 'livraison',
        ]);
        $commande->lignes()->create([
            'produit_id' => $produit->id, 'nom_produit' => $produit->nom, 'prix_unitaire' => $produit->prix, 'quantite' => 2, 'total' => $produit->prix * 2,
        ]);

        return $commande->load('lignes.produit');
    }
}
