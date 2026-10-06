<?php

namespace Tests\Feature;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'juan@test.local',
            'password' => bcrypt('password123'),
        ]);

        Livewire::test(Login::class)
            ->set('email', 'juan@test.local')
            ->set('password', 'password123')
            ->call('login')
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'juan@test.local',
            'password' => bcrypt('password123'),
        ]);

        Livewire::test(Login::class)
            ->set('email', 'juan@test.local')
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_user_can_register(): void
    {
        Livewire::test(Register::class)
            ->set('name', 'Nuevo Usuario')
            ->set('email', 'nuevo@test.local')
            ->set('password', 'SecurePass123!')
            ->set('password_confirmation', 'SecurePass123!')
            ->call('register')
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'nuevo@test.local']);
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }
}
