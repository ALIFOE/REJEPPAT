<?php

namespace Tests\Feature;

use App\Models\Evenement;
use App\Models\Media;
use App\Models\User;
use Database\Seeders\ContenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AgendaMediathequeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ContenuSeeder::class);
    }

    public function test_l_agenda_separe_les_rendez_vous_a_venir_et_passes(): void
    {
        Evenement::create(['slug' => 'foire-sokode', 'titre' => 'Foire agroécologique de Sokodé', 'type' => 'foire', 'debut' => now()->addWeek(), 'lieu' => 'Sokodé', 'description' => ['Exposants']]);
        Evenement::create(['slug' => 'atelier-2025', 'titre' => 'Atelier compost 2025', 'type' => 'atelier', 'debut' => now()->subMonth(), 'lieu' => 'Blitta', 'description' => ['Atelier']]);
        Evenement::create(['slug' => 'brouillon', 'titre' => 'Panel non publié', 'type' => 'panel', 'debut' => now()->addDay(), 'lieu' => 'Lomé', 'description' => ['Panel'], 'publie' => false]);

        $this->get(route('agenda.index'))->assertOk()
            ->assertSeeInOrder(['Prochains rendez-vous', 'Foire agroécologique de Sokodé', 'Rendez-vous passés', 'Atelier compost 2025'])
            ->assertDontSee('Panel non publié');
        $this->get(route('agenda.index', ['type' => 'atelier']))->assertDontSee('Foire agroécologique de Sokodé');
        $this->get(route('agenda.show', 'foire-sokode'))->assertOk()->assertSee('Sokodé');
        $this->get(route('agenda.show', 'brouillon'))->assertNotFound();

        // Les prochains rendez-vous sont annoncés sur l'accueil
        $this->get(route('home'))->assertSee('Foire agroécologique de Sokodé')->assertDontSee('Atelier compost 2025');
    }

    public function test_un_evenement_qui_dure_plusieurs_jours_reste_a_venir_jusqu_a_sa_fin(): void
    {
        Evenement::create(['slug' => 'formation', 'titre' => 'Formation de 10 jours', 'type' => 'formation', 'debut' => now()->subDays(3), 'fin' => now()->addDays(7), 'lieu' => 'Sokodé', 'description' => ['Texte']]);

        $this->assertSame(1, Evenement::aVenir()->count());
        $this->assertSame(0, Evenement::passe()->count());
    }

    public function test_mediatheque_photos_videos_et_podcasts(): void
    {
        Media::create(['titre' => 'Récolte à Ma Joie', 'type' => 'photo', 'date' => today(), 'photo' => 'medias/recolte.jpg']);
        Media::create(['titre' => 'Reportage vidéo', 'type' => 'video', 'date' => today(), 'url' => 'https://youtu.be/0bCiARbU0R4']);

        $this->get(route('mediatheque'))->assertOk()
            ->assertSee('Récolte à Ma Joie')
            ->assertSee('https://img.youtube.com/vi/0bCiARbU0R4/hqdefault.jpg', false);
        $this->get(route('mediatheque', ['type' => 'video']))->assertSee('Reportage vidéo')->assertDontSee('Récolte à Ma Joie');
    }

    public function test_une_video_doit_avoir_un_lien_youtube(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.medias.store'), [
            'titre' => 'Vidéo', 'type' => 'video', 'date' => '2026-10-10', 'url' => 'https://example.com/video',
        ])->assertSessionHasErrors('url');

        $this->post(route('admin.medias.store'), [
            'titre' => 'Vidéo', 'type' => 'video', 'date' => '2026-10-10', 'url' => 'https://www.youtube.com/watch?v=0bCiARbU0R4', 'publie' => '1',
        ])->assertRedirect(route('admin.medias.index'));
        $this->assertSame('0bCiARbU0R4', Media::sole()->youtubeId());
    }

    public function test_la_video_de_l_accueil_est_lue_par_un_lien_signe_sans_adresse_publique(): void
    {
        Storage::disk(Media::DISQUE_VIDEOS)->put('videos/journal.mp4', str_repeat('0', 2048));
        $video = Media::create(['titre' => 'Journal vidéo du REJEPPAT', 'type' => 'video', 'date' => today(), 'fichier' => 'videos/journal.mp4', 'accueil' => true]);

        $accueil = $this->get(route('home'))->assertOk()
            ->assertSeeInOrder(['Nos sections régionales', 'Le REJEPPAT en vidéo'])
            ->assertSee('Journal vidéo du REJEPPAT')
            ->assertSee('controlslist="nodownload', false)
            ->assertDontSee('journal.mp4', false);

        preg_match('/<video[^>]+src="([^"#]+)/', $accueil->getContent(), $lien);
        $lien = html_entity_decode($lien[1]);

        $this->get($lien, ['Range' => 'bytes=0-99'])->assertStatus(206)->assertHeader('Content-Length', '100');
        $this->get($lien)->assertOk()->assertHeader('Content-Disposition', 'inline');

        // Lien modifié ou ouvert directement dans un onglet : refusé
        $this->get(route('video.lecture', $video))->assertForbidden();
        $this->get($lien, ['Sec-Fetch-Dest' => 'document'])->assertForbidden();

        // Vidéo masquée : plus lisible par les visiteurs
        $video->update(['publie' => false]);
        $this->get($lien)->assertNotFound();
    }

    public function test_une_video_envoyee_depuis_l_administration_est_stockee_hors_du_dossier_public(): void
    {
        Storage::fake('uploads');
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.medias.store'), [
            'titre' => 'Reportage', 'type' => 'video', 'date' => '2026-10-10', 'accueil' => '1', 'publie' => '1',
            'fichier' => UploadedFile::fake()->create('reportage.mp4', 500, 'video/mp4'),
        ])->assertRedirect(route('admin.medias.index'));

        $video = Media::sole();
        $this->assertTrue($video->accueil);
        Storage::disk(Media::DISQUE_VIDEOS)->assertExists($video->fichier);
        $this->assertSame([], Storage::disk('uploads')->allFiles());

        $this->delete(route('admin.medias.destroy', $video));
        Storage::disk(Media::DISQUE_VIDEOS)->assertMissing($video->fichier);
    }

    public function test_la_fin_d_un_evenement_suit_son_debut(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.evenements.store'), [
            'titre' => 'Panel', 'type' => 'panel', 'debut' => '2026-11-10T09:00', 'fin' => '2026-11-09T09:00',
            'lieu' => 'Sokodé', 'description' => 'Texte',
        ])->assertSessionHasErrors(['fin' => 'La date de fin doit suivre la date de début.']);
    }
}
