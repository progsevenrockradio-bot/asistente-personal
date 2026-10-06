<?php

namespace Tests\Feature;

use App\Livewire\Calendar\Create;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_calendar()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('calendar.index'))
            ->assertSuccessful();
    }

    public function test_can_create_event_and_detect_conflict()
    {
        $user = User::factory()->create();
        $date = Carbon::today()->format('Y-m-d');

        // Create first event
        Event::create([
            'user_id' => $user->id,
            'titulo' => 'Reunión A',
            'fecha' => $date,
            'hora_inicio' => '10:00:00',
            'duracion' => 60,
            'prioridad' => 'NORMAL',
            'estado' => 'confirmado',
            'origen' => 'manual',
        ]);

        // Create conflicting event
        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('titulo', 'Reunión B')
            ->set('fecha', $date)
            ->set('hora_inicio', '10:30:00')
            ->set('duracion', 60)
            ->set('prioridad', 'NORMAL')
            ->call('save');

        $this->assertDatabaseHas('events', [
            'titulo' => 'Reunión B',
        ]);
    }
}
