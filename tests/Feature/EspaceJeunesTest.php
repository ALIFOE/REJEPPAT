<?php

namespace Tests\Feature;

use App\Models\Ferme;
use App\Models\Membre;
use App\Models\Opportunite;
use App\Models\Region;
use App\Models\Ressource;
use App\Models\Temoignage;
use App\Models\User;
use Database\Seeders\ContenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EspaceJeunesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ContenuSeeder::class);
    }

    public function test_un_profil_propose_attend_la_validation_avant_d_apparaitre(): void
    {
        Mail::fake();
        $region = Region::where('slug', 'centrale')->sole();

        $this->post(route('espace-jeunes.inscrire'), [
            'nom' => 'Essohana Pali',
            'organisation' => 'Coopérative Espoir',
            'region_id' => $region->id,
            'filiere' => 'apiculture',
            'presentation' => "Apiculteur depuis 2019.\nJe produis du miel à Sotouboua.",
            'telephone' => '90 11 22 33',
            'accord' => '1',
        ])->assertRedirect(route('espace-jeunes.rejoindre'))->assertSessionHas('succes');

        $membre = Membre::sole();
        $this->assertFalse($membre->publie);
        $this->assertSame(['Apiculteur depuis 2019.', 'Je produis du miel à Sotouboua.'], $membre->presentation);
        $this->get(route('espace-jeunes.annuaire'))->assertDontSee('Essohana Pali');
        $this->get(route('espace-jeunes.membre', $membre->slug))->assertNotFound();

        $membre->update(['publie' => true]);
        $this->get(route('espace-jeunes.annuaire', ['region' => 'centrale', 'filiere' => 'apiculture']))->assertOk()->assertSee('Essohana Pali');
        $this->get(route('espace-jeunes.annuaire', ['filiere' => 'elevage']))->assertDontSee('Essohana Pali');
        $this->get(route('espace-jeunes.membre', $membre->slug))->assertOk()->assertSee('Je produis du miel à Sotouboua.');
    }

    public function test_la_publication_du_profil_doit_etre_acceptee(): void
    {
        $this->post(route('espace-jeunes.inscrire'), [
            'nom' => 'Essohana Pali', 'filiere' => 'apiculture', 'presentation' => 'Apiculteur.',
        ])->assertSessionHasErrors(['accord' => 'Vous devez accepter la publication de votre profil.']);

        $this->assertSame(0, Membre::count());
    }

    public function test_temoignages_success_stories_et_temoignages_d_apprenants(): void
    {
        $ferme = Ferme::where('slug', 'ma-joie')->sole();
        Temoignage::create(['type' => 'success_story', 'nom' => 'Afi', 'titre' => 'De la formation à mon exploitation', 'texte' => 'Mon histoire.']);
        Temoignage::create(['type' => 'apprenant', 'nom' => 'Kodjo', 'texte' => 'J’ai appris l’apiculture.', 'ferme_id' => $ferme->id]);

        $this->get(route('espace-jeunes.temoignages'))->assertOk()
            ->assertSee('De la formation à mon exploitation')
            ->assertSee('Kossi Mensah');
        $this->get(route('fermes.show', 'ma-joie'))->assertOk()->assertSee('J’ai appris l’apiculture.', false);
        $this->get(route('fermes.show', 'cadete'))->assertDontSee('J’ai appris l’apiculture.', false);
    }

    public function test_les_opportunites_cloturees_passent_dans_les_archives(): void
    {
        Opportunite::create(['slug' => 'bourse-2027', 'titre' => 'Bourse jeunes agripreneurs', 'type' => 'bourse', 'date_limite' => today()->addMonth(), 'description' => ['Texte']]);
        Opportunite::create(['slug' => 'ancien-appel', 'titre' => 'Ancien appel à projets', 'type' => 'appel_projets', 'date_limite' => today()->subDay(), 'description' => ['Texte']]);
        Opportunite::create(['slug' => 'aujourdhui', 'titre' => 'Dernier jour pour postuler', 'type' => 'emploi', 'date_limite' => today(), 'description' => ['Texte']]);

        $this->get(route('espace-jeunes.opportunites'))->assertOk()
            ->assertSee('Bourse jeunes agripreneurs')
            ->assertSee('Dernier jour pour postuler')
            ->assertDontSee('Ancien appel à projets');
        $this->get(route('espace-jeunes.opportunites', ['type' => 'bourse']))->assertDontSee('Dernier jour pour postuler');
        $this->get(route('espace-jeunes.opportunites', ['archives' => 1]))->assertSee('Ancien appel à projets')->assertDontSee('Bourse jeunes agripreneurs');
        $this->get(route('espace-jeunes.opportunite', 'ancien-appel'))->assertOk()->assertSee('Clôturée');
    }

    public function test_telechargement_d_une_ressource_compte_les_telechargements(): void
    {
        Storage::fake('uploads');
        Storage::disk('uploads')->put('ressources/guide.pdf', '%PDF-1.4 guide');
        $ressource = Ressource::create(['titre' => 'Guide du compost', 'categorie' => 'guide', 'fichier' => 'ressources/guide.pdf']);

        $this->get(route('espace-jeunes.ressources'))->assertOk()->assertSee('Guide du compost')->assertSee('PDF');
        $this->get(route('espace-jeunes.ressources.telecharger', $ressource))
            ->assertOk()
            ->assertDownload('guide-du-compost.pdf');

        $this->assertSame(1, $ressource->fresh()->telechargements);
    }

    public function test_une_ressource_masquee_ne_se_telecharge_pas(): void
    {
        Storage::fake('uploads');
        Storage::disk('uploads')->put('ressources/guide.pdf', '%PDF-1.4');
        $ressource = Ressource::create(['titre' => 'Brouillon', 'categorie' => 'guide', 'fichier' => 'ressources/guide.pdf', 'publie' => false]);

        $this->get(route('espace-jeunes.ressources.telecharger', $ressource))->assertNotFound();
        $this->assertSame(0, $ressource->fresh()->telechargements);
    }

    public function test_ajout_d_une_ressource_et_d_une_opportunite_dans_l_administration(): void
    {
        Storage::fake('uploads');
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.ressources.store'), [
            'titre' => 'Manuel de maraîchage', 'categorie' => 'manuel',
            'fichier' => UploadedFile::fake()->create('manuel.pdf', 200, 'application/pdf'), 'publie' => '1',
        ])->assertRedirect(route('admin.ressources.index'));
        Storage::disk('uploads')->assertExists(Ressource::where('titre', 'Manuel de maraîchage')->sole()->fichier);

        $this->post(route('admin.ressources.store'), [
            'titre' => 'Script', 'categorie' => 'guide', 'fichier' => UploadedFile::fake()->create('virus.exe', 10),
        ])->assertSessionHasErrors(['fichier' => 'Formats acceptés : PDF, Word, Excel, PowerPoint, ODT ou ZIP.']);

        $this->post(route('admin.opportunites.store'), [
            'titre' => 'Stage en agroécologie', 'type' => 'emploi', 'date_limite' => today()->addWeek()->format('Y-m-d'),
            'description' => "Stage de 3 mois.\nÀ Sokodé.", 'publie' => '1',
        ])->assertRedirect(route('admin.opportunites.index'));
        $this->assertSame(['Stage de 3 mois.', 'À Sokodé.'], Opportunite::sole()->description);
    }

    public function test_un_temoignage_d_apprenant_doit_indiquer_sa_ferme(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.temoignages.store'), [
            'type' => 'apprenant', 'nom' => 'Kodjo', 'texte' => 'Merci.',
        ])->assertSessionHasErrors(['ferme_id' => 'Choisissez la ferme école de l’apprenant.']);
    }
}
