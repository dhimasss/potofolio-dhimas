<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProjectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->actingAs(User::factory()->create());
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Sistem Kasir UMKM',
            'cover_image' => UploadedFile::fake()->image('cover.jpg', 800, 600),
            'the_challenge' => 'Pencatatan penjualan masih manual.',
            'the_solution' => 'Membangun aplikasi kasir berbasis web.',
            'tech_stack' => ' Laravel, MySQL , ,Laravel, Tailwind CSS ',
            'project_url' => 'https://github.com/contoh/kasir',
        ], $overrides);
    }

    private function storeProject(array $overrides = []): Project
    {
        $this->post(route('admin.projects.store'), $this->validPayload($overrides));

        return Project::latest('id')->firstOrFail();
    }

    public function test_index_create_and_edit_pages_render(): void
    {
        $project = $this->storeProject(['title' => "Proyek \"O'Brien\""]);

        $this->get(route('admin.projects.index'))->assertOk()->assertSee($project->title);
        $this->get(route('admin.projects.create'))->assertOk()->assertSee('Terbitkan proyek');
        $this->get(route('admin.projects.edit', $project))->assertOk()->assertSee('Simpan perubahan');
    }

    public function test_admin_can_create_project_with_cover(): void
    {
        $this->post(route('admin.projects.store'), $this->validPayload())
            ->assertRedirect(route('admin.projects.index'))
            ->assertSessionHas('success');

        $project = Project::firstOrFail();

        $this->assertSame('sistem-kasir-umkm', $project->slug);
        $this->assertSame(['Laravel', 'MySQL', 'Tailwind CSS'], $project->tech_stack);
        $this->assertStringStartsWith('projects/', $project->cover_image);
        Storage::disk('public')->assertExists($project->cover_image);
    }

    public function test_validation_rules(): void
    {
        $this->post(route('admin.projects.store'), $this->validPayload([
            'title' => '',
            'cover_image' => null,
            'project_url' => 'bukan-url',
        ]))->assertSessionHasErrors(['title', 'cover_image', 'project_url']);

        $this->post(route('admin.projects.store'), $this->validPayload([
            'cover_image' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ]))->assertSessionHasErrors('cover_image');

        $this->post(route('admin.projects.store'), $this->validPayload([
            'cover_image' => UploadedFile::fake()->image('besar.jpg')->size(3000),
        ]))->assertSessionHasErrors('cover_image');

        $this->assertDatabaseCount('projects', 0);
    }

    public function test_update_without_new_cover_keeps_old_image(): void
    {
        $project = $this->storeProject();
        $oldCover = $project->cover_image;

        $this->put(route('admin.projects.update', $project), $this->validPayload([
            'title' => 'Aplikasi Kasir Pintar',
            'cover_image' => null,
        ]))->assertRedirect(route('admin.projects.index'));

        $project->refresh();
        $this->assertSame('Aplikasi Kasir Pintar', $project->title);
        $this->assertSame('aplikasi-kasir-pintar', $project->slug);
        $this->assertSame($oldCover, $project->cover_image);
        Storage::disk('public')->assertExists($oldCover);
    }

    public function test_update_with_new_cover_replaces_and_deletes_old_image(): void
    {
        $project = $this->storeProject();
        $oldCover = $project->cover_image;

        $this->put(route('admin.projects.update', $project), $this->validPayload([
            'cover_image' => UploadedFile::fake()->image('baru.png'),
        ]));

        $project->refresh();
        $this->assertNotSame($oldCover, $project->cover_image);
        Storage::disk('public')->assertExists($project->cover_image);
        Storage::disk('public')->assertMissing($oldCover);
    }

    public function test_cover_uses_configured_media_disk(): void
    {
        // Simulasi produksi di Vercel: MEDIA_DISK=s3
        config(['filesystems.media' => 's3']);
        Storage::fake('s3');

        $project = $this->storeProject();

        Storage::disk('s3')->assertExists($project->cover_image);
        Storage::disk('public')->assertMissing($project->cover_image);

        $this->delete(route('admin.projects.destroy', $project));
        Storage::disk('s3')->assertMissing($project->cover_image);
    }

    public function test_delete_removes_project_and_cover(): void
    {
        $project = $this->storeProject();

        $this->delete(route('admin.projects.destroy', $project))
            ->assertRedirect(route('admin.projects.index'));

        $this->assertModelMissing($project);
        Storage::disk('public')->assertMissing($project->cover_image);
    }
}
