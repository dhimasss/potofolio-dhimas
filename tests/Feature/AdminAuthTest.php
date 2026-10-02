<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
        $this->get('/admin/projects')->assertRedirect(route('admin.login'));
    }

    public function test_login_page_renders(): void
    {
        $this->get(route('admin.login'))->assertOk()->assertSee('Masuk');
    }

    public function test_admin_can_login_and_see_dashboard(): void
    {
        $user = User::factory()->create(['password' => 'rahasia-123']);
        Project::create(['title' => 'Aplikasi Kasir', 'the_challenge' => 'a', 'the_solution' => 'b']);

        $this->post(route('admin.login.store'), [
            'email' => $user->email,
            'password' => 'rahasia-123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Total Projects')
            ->assertSee('Aplikasi Kasir');
    }

    public function test_wrong_password_is_rejected(): void
    {
        $user = User::factory()->create(['password' => 'rahasia-123']);

        $this->from(route('admin.login'))
            ->post(route('admin.login.store'), ['email' => $user->email, 'password' => 'salah'])
            ->assertRedirect(route('admin.login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_is_rate_limited(): void
    {
        foreach (range(1, 5) as $_) {
            $this->post(route('admin.login.store'), ['email' => 'x@example.com', 'password' => 'salah']);
        }

        $this->post(route('admin.login.store'), ['email' => 'x@example.com', 'password' => 'salah'])
            ->assertStatus(429);
    }

    public function test_logged_in_admin_skips_login_page(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.login'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_can_logout(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }
}
