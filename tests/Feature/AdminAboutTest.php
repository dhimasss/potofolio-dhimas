<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAboutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'full_name' => 'Dhimas Jayakusuma Sarma',
            'headline' => 'Web Development & Graphic Designer',
            'bio' => "Paragraf pertama.\n\nParagraf kedua.",
            'photo' => UploadedFile::fake()->image('foto.jpg', 800, 1000),
            'birth_place' => 'Yogyakarta',
            'birth_date' => '2000-07-29',
            'location' => 'Yogyakarta, Indonesia',
            'education' => 'S1 Informatika',
            'email' => 'halo@example.com',
            'phone' => '+62 812-3456-7890',
            'skills' => ' Laravel, Figma , ,Laravel, Illustrator ',
        ], $overrides);
    }

    private function admin(): static
    {
        return $this->actingAs(User::factory()->create());
    }

    public function test_guest_cannot_access_about_admin(): void
    {
        $this->get(route('admin.about.show'))->assertRedirect(route('admin.login'));
        $this->delete(route('admin.about.destroy'))->assertRedirect(route('admin.login'));
    }

    public function test_show_page_offers_create_when_empty(): void
    {
        $this->admin()->get(route('admin.about.show'))
            ->assertOk()
            ->assertSee('Belum ada biodata')
            ->assertSee(route('admin.about.create'), false);

        // Edit tanpa biodata -> diarahkan ke create
        $this->get(route('admin.about.edit'))->assertRedirect(route('admin.about.create'));
    }

    public function test_admin_can_create_biodata_with_photo(): void
    {
        $this->admin()->post(route('admin.about.store'), $this->payload())
            ->assertRedirect(route('admin.about.show'))
            ->assertSessionHas('success');

        $profile = Profile::current();

        $this->assertSame('Dhimas Jayakusuma Sarma', $profile->full_name);
        $this->assertSame(['Laravel', 'Figma', 'Illustrator'], $profile->skills);
        $this->assertSame('Yogyakarta, 29 Juli 2000', $profile->birth_info);
        $this->assertSame('DJ', $profile->initials);
        Storage::disk('public')->assertExists($profile->photo);

        $this->get(route('admin.about.show'))->assertOk()->assertSee('Dhimas Jayakusuma Sarma')->assertSee('Hapus biodata');
        $this->get(route('admin.about.edit'))->assertOk()->assertSee('Simpan perubahan');
    }

    public function test_only_one_biodata_can_exist(): void
    {
        $this->admin()->post(route('admin.about.store'), $this->payload());

        $this->get(route('admin.about.create'))->assertRedirect(route('admin.about.edit'));
        $this->post(route('admin.about.store'), $this->payload(['full_name' => 'Orang Lain']))
            ->assertRedirect(route('admin.about.edit'));

        $this->assertDatabaseCount('profiles', 1);
    }

    public function test_validation_rules(): void
    {
        $this->admin()->post(route('admin.about.store'), $this->payload([
            'full_name' => '',
            'bio' => '',
            'email' => 'bukan-email',
            'phone' => 'telepon saya',
            'birth_date' => now()->addDay()->toDateString(),
            'photo' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ]))->assertSessionHasErrors(['full_name', 'bio', 'email', 'phone', 'birth_date', 'photo']);

        $this->assertDatabaseCount('profiles', 0);
    }

    public function test_update_replaces_photo_and_deletes_old_one(): void
    {
        $this->admin()->post(route('admin.about.store'), $this->payload());
        $oldPhoto = Profile::current()->photo;

        $this->put(route('admin.about.update'), $this->payload([
            'headline' => 'Designer',
            'photo' => UploadedFile::fake()->image('baru.png'),
        ]))->assertRedirect(route('admin.about.show'));

        $profile = Profile::current();
        $this->assertSame('Designer', $profile->headline);
        $this->assertNotSame($oldPhoto, $profile->photo);
        Storage::disk('public')->assertMissing($oldPhoto);
        Storage::disk('public')->assertExists($profile->photo);
    }

    public function test_update_can_remove_photo_only(): void
    {
        $this->admin()->post(route('admin.about.store'), $this->payload());
        $oldPhoto = Profile::current()->photo;

        $this->put(route('admin.about.update'), $this->payload(['photo' => null, 'remove_photo' => '1']));

        $this->assertNull(Profile::current()->photo);
        Storage::disk('public')->assertMissing($oldPhoto);
    }

    public function test_admin_can_delete_biodata_and_photo(): void
    {
        $this->admin()->post(route('admin.about.store'), $this->payload());
        $photo = Profile::current()->photo;

        $this->delete(route('admin.about.destroy'))
            ->assertRedirect(route('admin.about.show'))
            ->assertSessionHas('success');

        $this->assertDatabaseCount('profiles', 0);
        Storage::disk('public')->assertMissing($photo);
    }

    public function test_public_home_shows_about_section_and_nav_link(): void
    {
        $this->admin()->post(route('admin.about.store'), $this->payload());

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('id="about"', false)
            ->assertSee(route('home').'#about', false)
            ->assertSee('01 — About Me')
            ->assertSee('02 — My Journey')
            ->assertSee('<p>Paragraf kedua.</p>', false)
            ->assertSee('Yogyakarta, 29 Juli 2000')
            ->assertSee('Illustrator');
    }

    public function test_public_home_hides_about_when_deleted(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('id="about"', false)
            ->assertDontSee(route('home').'#about', false)
            ->assertSee('01 — My Journey')
            ->assertSee('02 — Selected Works');
    }
}
