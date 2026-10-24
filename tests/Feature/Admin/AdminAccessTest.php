<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    private function ordinaryUser(): User
    {
        return User::factory()->create();
    }

    private function administrator(): User
    {
        $user = User::factory()->create();

        // is_admin is intentionally not mass assignable.
        $user->forceFill(['is_admin' => true])->save();

        return $user;
    }

    public function test_guests_are_redirected_to_the_filament_login_page(): void
    {
        $this->get('/admin')
            ->assertRedirect(route('filament.admin.auth.login'));
    }

    public function test_the_filament_login_page_remains_accessible(): void
    {
        $this->get(route('filament.admin.auth.login'))->assertOk();
    }

    public function test_an_authenticated_non_admin_user_receives_a_403_on_the_dashboard(): void
    {
        $this->actingAs($this->ordinaryUser())
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_an_authenticated_non_admin_user_receives_a_403_on_sensitive_admin_pages(): void
    {
        $user = $this->ordinaryUser();

        foreach (['/admin/settings', '/admin/contact-submissions'] as $url) {
            $this->actingAs($user)->get($url)->assertForbidden();
        }
    }

    public function test_an_admin_user_can_access_the_dashboard(): void
    {
        $this->actingAs($this->administrator())
            ->get('/admin')
            ->assertOk();
    }

    public function test_an_admin_user_can_access_settings(): void
    {
        $this->actingAs($this->administrator())
            ->get('/admin/settings')
            ->assertOk();
    }

    public function test_a_non_admin_user_is_denied_even_when_the_environment_is_local(): void
    {
        config(['app.env' => 'local']);

        $this->actingAs($this->ordinaryUser())
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_is_admin_cannot_be_assigned_through_mass_assignment(): void
    {
        $this->assertNotContains('is_admin', (new User())->getFillable());

        $user = User::create([
            'name' => 'Mass Assignment',
            'email' => 'mass-assignment@example.test',
            'password' => 'a-test-password',
            'is_admin' => true,
        ]);

        $user->refresh();

        $this->assertFalse($user->is_admin);
        $this->assertFalse($user->canAccessPanel(Filament::getPanel('admin')));
    }

    public function test_the_database_seeder_no_longer_creates_a_fixed_password_account(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
        $this->assertDatabaseCount('users', 0);
    }
}
