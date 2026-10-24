<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_command_refuses_to_run_outside_local_development(): void
    {
        $this->app->detectEnvironment(fn () => 'testing');

        $this->artisan('admin:create')
            ->expectsOutputToContain('can only be run in the local environment')
            ->assertFailed();

        $this->assertDatabaseCount('users', 0);
    }

    public function test_the_command_creates_an_explicit_administrator_in_local_development(): void
    {
        $this->app->detectEnvironment(fn () => 'local');

        $password = 'correct-horse-battery-staple';

        $this->artisan('admin:create')
            ->expectsQuestion('Name', 'Development Administrator')
            ->expectsQuestion('Email address', 'dev-admin@example.test')
            ->expectsQuestion('Password (min. 12 characters)', $password)
            ->expectsQuestion('Confirm password', $password)
            ->expectsOutputToContain('Administrator dev-admin@example.test created.')
            ->assertSuccessful();

        $user = User::where('email', 'dev-admin@example.test')->firstOrFail();

        $this->assertTrue($user->is_admin);
        $this->assertTrue(Hash::check($password, $user->password));
        $this->assertTrue($user->canAccessPanel(Filament::getPanel('admin')));
    }

    public function test_the_command_rejects_mismatched_passwords_without_creating_an_account(): void
    {
        $this->app->detectEnvironment(fn () => 'local');

        $this->artisan('admin:create')
            ->expectsQuestion('Name', 'Development Administrator')
            ->expectsQuestion('Email address', 'dev-admin@example.test')
            ->expectsQuestion('Password (min. 12 characters)', 'correct-horse-battery-staple')
            ->expectsQuestion('Confirm password', 'different-password-value')
            ->expectsOutputToContain('confirmation')
            ->assertFailed();

        $this->assertDatabaseCount('users', 0);
    }

    public function test_the_command_refuses_an_existing_email_instead_of_promoting_that_user(): void
    {
        $this->app->detectEnvironment(fn () => 'local');

        $existing = User::factory()->create(['email' => 'existing@example.test']);

        $this->artisan('admin:create')
            ->expectsQuestion('Name', 'Development Administrator')
            ->expectsQuestion('Email address', 'existing@example.test')
            ->expectsQuestion('Password (min. 12 characters)', 'correct-horse-battery-staple')
            ->expectsQuestion('Confirm password', 'correct-horse-battery-staple')
            ->expectsOutputToContain('already been taken')
            ->assertFailed();

        $this->assertFalse($existing->fresh()->is_admin);
    }
}
