<?php

namespace Tests\Feature;

use App\Models\Actualite;
use App\Models\Commande;
use App\Models\DemandePartenariat;
use App\Models\DemandeService;
use App\Models\Evenement;
use App\Models\Ferme;
use App\Models\Lettre;
use App\Models\Media;
use App\Models\Membre;
use App\Models\MessageContact;
use App\Models\Offre;
use App\Models\Opportunite;
use App\Models\Partenaire;
use App\Models\Produit;
use App\Models\Projet;
use App\Models\Region;
use App\Models\Ressource;
use App\Models\Temoignage;
use App\Models\User;
use Database\Seeders\ContenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdministrationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ContenuSeeder::class);
        $this->admin = User::factory()->superAdmin()->create();
    }

    public function test_l_administration_est_protegee(): void
    {
        $this->get(route('admin.tableau'))->assertRedirect(route('connexion'));

        $client = User::factory()->create();
        $this->actingAs($client)->get(route('admin.tableau'))
            ->assertRedirect(route('compte.index'))
            ->assertSessionHas('erreur', 'Votre compte n’a pas accès à l’administration.');
        $this->assertAuthenticatedAs($client);
    }

    public function test_connexion_administrateur(): void
    {
        $this->post(route('connexion'), ['email' => $this->admin->email, 'password' => 'password'])
            ->assertRedirect(route('admin.tableau'));
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_tous_les_ecrans_s_affichent(): void
    {
        $commande = $this->commande();
        $demande = DemandeService::create(['nom' => 'A', 'telephone' => '90000000', 'service' => 'Autre besoin', 'objet' => 'Objet', 'besoin' => 'Texte']);
        $message = MessageContact::create(['nom' => 'B', 'email' => 'b@example.com', 'objet' => 'Soutien', 'message' => 'Bonjour']);
        $partenariat = DemandePartenariat::create(['organisation' => 'ONG', 'nom' => 'C', 'email' => 'c@example.com', 'type' => 'technique', 'message' => 'Bonjour']);
        $membre = Membre::create(['slug' => 'afi', 'nom' => 'Afi', 'filiere' => 'maraichage', 'presentation' => ['Texte']]);
        $opportunite = Opportunite::create(['slug' => 'bourse', 'titre' => 'Bourse', 'type' => 'bourse', 'description' => ['Texte']]);
        $ressource = Ressource::create(['titre' => 'Guide', 'categorie' => 'guide', 'fichier' => 'ressources/guide.pdf']);
        $evenement = Evenement::create(['slug' => 'foire', 'titre' => 'Foire', 'type' => 'foire', 'debut' => now()->addWeek(), 'lieu' => 'Sokodé', 'description' => ['Texte']]);
        $media = Media::create(['titre' => 'Vidéo', 'type' => 'video', 'date' => today(), 'url' => 'https://youtu.be/0bCiARbU0R4']);
        $lettre = Lettre::create(['sujet' => 'Nouvelles', 'contenu' => '<p>Bonjour</p>']);

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
            route('admin.profil'),
            route('admin.commandes.facture', $commande),
            route('admin.regions.index'), route('admin.regions.create'), route('admin.regions.edit', Region::first()),
            route('admin.partenaires.index'), route('admin.partenaires.create'), route('admin.partenaires.edit', Partenaire::first()),
            route('admin.partenariats.index'), route('admin.partenariats.show', $partenariat),
            route('admin.membres.index'), route('admin.membres.create'), route('admin.membres.edit', $membre),
            route('admin.temoignages.index'), route('admin.temoignages.create'), route('admin.temoignages.edit', Temoignage::first()),
            route('admin.opportunites.index'), route('admin.opportunites.create'), route('admin.opportunites.edit', $opportunite),
            route('admin.ressources.index'), route('admin.ressources.create'), route('admin.ressources.edit', $ressource),
            route('admin.evenements.index'), route('admin.evenements.create'), route('admin.evenements.edit', $evenement),
            route('admin.medias.index'), route('admin.medias.create'), route('admin.medias.edit', $media),
            route('admin.abonnes.index'), route('admin.lettres.index'), route('admin.lettres.create'), route('admin.lettres.edit', $lettre),
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

    public function test_une_tres_grande_photo_envoyee_est_reduite(): void
    {
        Storage::fake('uploads');

        $reponse = $this->actingAs($this->admin)->postJson(route('admin.actualites.image'), ['file' => UploadedFile::fake()->image('grande.jpg', 3000, 2000)]);

        $chemin = Str::after($reponse->json('location'), '/uploads/');
        $this->assertSame([1920, 1280], array_slice(getimagesize(Storage::disk('uploads')->path($chemin)), 0, 2));
    }

    public function test_une_photo_de_telephone_est_remise_a_l_endroit(): void
    {
        Storage::fake('uploads');

        // Photo 400 x 200 prise téléphone tourné : orientation EXIF 6 (à faire pivoter de 90° vers la droite)
        $image = imagecreatetruecolor(400, 200);
        ob_start();
        imagejpeg($image);
        $jpeg = ob_get_clean();
        $tiff = "MM\x00\x2A".pack('N', 8).pack('n', 1).pack('nnNn', 0x0112, 3, 1, 6)."\x00\x00".pack('N', 0);
        $exif = "\xFF\xE1".pack('n', strlen($tiff) + 8)."Exif\x00\x00".$tiff;
        $fichier = UploadedFile::fake()->createWithContent('telephone.jpg', substr($jpeg, 0, 2).$exif.substr($jpeg, 2));

        $reponse = $this->actingAs($this->admin)->postJson(route('admin.actualites.image'), ['file' => $fichier]);

        $chemin = Str::after($reponse->json('location'), '/uploads/');
        $this->assertSame([200, 400], array_slice(getimagesize(Storage::disk('uploads')->path($chemin)), 0, 2));
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
            'categories' => ['formation'], 'date' => '2026-01-01', 'statut' => 'termine', 'resume' => 'Résumé', 'description' => 'Texte',
            'actions' => 'Action 1 | Détail 1', 'publie' => '0',
        ])->assertRedirect(route('admin.projets.index'));
        $this->assertSame([['titre' => 'Action 1', 'texte' => 'Détail 1']], $projet->fresh()->actions);
        $this->assertSame('termine', $projet->fresh()->statut);
        $this->get(route('projets.show', $projet->slug))->assertNotFound();
    }

    public function test_photos_supplementaires_d_un_produit(): void
    {
        Storage::fake('uploads');
        $produit = Produit::first();
        $donnees = [
            'nom' => $produit->nom, 'slug' => $produit->slug, 'categorie' => $produit->categorie, 'prix' => $produit->prix,
            'resume' => $produit->resume, 'description' => 'Description', 'actif' => '1',
        ];

        $this->actingAs($this->admin)->put(route('admin.produits.update', $produit), $donnees + [
            'photos' => [0 => UploadedFile::fake()->image('a.jpg'), 2 => UploadedFile::fake()->image('c.jpg')],
        ])->assertRedirect(route('admin.produits.index'));

        $photos = $produit->fresh()->photos;
        $this->assertCount(2, $photos);
        Storage::disk('uploads')->assertExists($photos);
        $this->get(route('boutique.show', $produit->slug))->assertOk()->assertSee('uploads/'.$photos[0])->assertSee('uploads/'.$photos[1]);
        $this->get(route('admin.produits.edit', $produit))->assertOk()->assertSee('uploads/'.$photos[1]);

        // Retirer la première photo
        $this->put(route('admin.produits.update', $produit), $donnees + ['retirer_photos' => ['0']]);

        $this->assertSame([$photos[1]], $produit->fresh()->photos);
        Storage::disk('uploads')->assertMissing($photos[0]);
    }

    public function test_un_produit_ne_peut_pas_avoir_plus_de_trois_photos_supplementaires(): void
    {
        $produit = Produit::first();

        $this->actingAs($this->admin)->put(route('admin.produits.update', $produit), [
            'nom' => $produit->nom, 'categorie' => $produit->categorie, 'prix' => $produit->prix,
            'resume' => $produit->resume, 'description' => 'Description',
            'photos' => array_map(fn ($i) => UploadedFile::fake()->image("p{$i}.jpg"), range(1, 4)),
        ])->assertSessionHasErrors('photos');

        $this->assertEmpty($produit->fresh()->photos);
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
