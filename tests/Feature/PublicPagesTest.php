<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    private function makeProject(string $title, array $overrides = []): Project
    {
        return Project::create(array_merge([
            'title' => $title,
            'the_challenge' => "Paragraf tantangan pertama.\n\nParagraf tantangan kedua.",
            'the_solution' => 'Solusi <script>alert(1)</script> yang aman.',
            'tech_stack' => ['Laravel', 'MySQL'],
        ], $overrides));
    }

    public function test_home_shows_all_sections(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Crafting visual stories')
            ->assertSee('My Journey')
            ->assertSee('Selected Works')
            ->assertSee('Cerita proyek sedang ditulis')
            ->assertSee(config('portfolio.email'));
    }

    public function test_theme_toggle_only_on_public_pages(): void
    {
        $this->get(route('home'))->assertSee('data-theme-toggle', false);
        $this->get(route('admin.login'))->assertDontSee('data-theme-toggle', false);
    }

    public function test_footer_links_to_social_profiles(): void
    {
        $this->get(route('home'))
            ->assertSee(config('portfolio.linkedin'), false)
            ->assertSee(config('portfolio.github'), false)
            ->assertSee(config('portfolio.behance'), false);
    }

    public function test_home_lists_projects_linking_to_detail(): void
    {
        $project = $this->makeProject('Sistem Kasir');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Sistem Kasir')
            ->assertSee(route('projects.show', $project), false);
    }

    public function test_detail_page_tells_the_story_safely(): void
    {
        $project = $this->makeProject('Sistem Kasir', ['project_url' => 'https://github.com/contoh/kasir']);

        $this->get('/projects/sistem-kasir')
            ->assertOk()
            ->assertSee('The Challenge')
            ->assertSee('The Solution')
            ->assertSee('<p>Paragraf tantangan pertama.</p>', false)
            ->assertSee('<p>Paragraf tantangan kedua.</p>', false)
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('https://github.com/contoh/kasir', false);
    }

    public function test_detail_page_links_to_next_project(): void
    {
        $older = $this->makeProject('Proyek Lama');
        $newer = $this->makeProject('Proyek Baru');

        $this->get(route('projects.show', $newer))->assertSee('Proyek Lama');
        // Proyek paling lama berputar kembali ke yang terbaru
        $this->get(route('projects.show', $older))->assertSee('Proyek Baru');
    }

    public function test_unknown_slug_returns_404(): void
    {
        $this->get('/projects/tidak-ada')->assertNotFound();
    }
}
