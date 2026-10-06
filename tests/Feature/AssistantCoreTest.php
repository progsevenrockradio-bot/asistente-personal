<?php

namespace Tests\Feature;

use App\Livewire\Calendar\Create;
use App\Livewire\Inbox\QuickCapture;
use App\Livewire\Tasks\Index;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AssistantCoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_pwa_manifest_and_offline_page_exist(): void
    {
        $manifest = $this->get('/manifest.json');
        $manifest->assertStatus(200);
        $manifest->assertHeader('content-type', 'application/json');

        $offline = $this->get('/offline.html');
        $offline->assertStatus(200);
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('HOY');
        $response->assertSee('AHORA');
        $response->assertSee('PRÓXIMO');
    }

    public function test_user_can_quick_capture_to_inbox(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(QuickCapture::class)
            ->set('content', 'El viernes tengo médico a las 10')
            ->set('source', 'texto')
            ->call('save')
            ->assertDispatched('captured')
            ->assertDispatched('inbox-updated');

        $this->assertDatabaseHas('inbox_items', [
            'user_id' => $user->id,
            'content' => 'El viernes tengo médico a las 10',
            'status' => 'pendiente_analisis',
        ]);
    }

    public function test_user_can_create_event(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('titulo', 'Concierto en WiZink')
            ->set('fecha', now()->addDays(2)->format('Y-m-d'))
            ->set('hora_inicio', '21:00')
            ->set('duracion', 120)
            ->set('ubicacion', 'Madrid')
            ->set('prioridad', 'NORMAL')
            ->call('save')
            ->assertRedirect(route('calendar.index'));

        $this->assertDatabaseHas('events', [
            'user_id' => $user->id,
            'titulo' => 'Concierto en WiZink',
            'ubicacion' => 'Madrid',
        ]);
    }

    public function test_user_can_add_and_toggle_task(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Index::class)
            ->set('newTitle', 'Comprar entradas')
            ->set('newPriority', 'URGENTE')
            ->call('addTask');

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'titulo' => 'Comprar entradas',
            'estado' => 'pendiente',
        ]);
    }
}
