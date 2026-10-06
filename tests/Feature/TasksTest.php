<?php

namespace Tests\Feature;

use App\Livewire\Tasks\Index;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_tasks()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('tasks.index'))
            ->assertSuccessful();
    }

    public function test_can_create_and_cycle_task_states()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Index::class)
            ->set('newTitle', 'Nueva Tarea')
            ->set('newPriority', 'NORMAL')
            ->call('addTask');

        $this->assertDatabaseHas('tasks', [
            'titulo' => 'Nueva Tarea',
            'estado' => 'pendiente',
        ]);

        $task = Task::first();

        // Cycle through states (pendiente -> en_proceso -> completada -> bloqueada -> pospuesta)
        Livewire::actingAs($user)
            ->test(Index::class)
            ->call('toggleTask', $task->id);

        $this->assertEquals('en_proceso', $task->fresh()->estado);

        Livewire::actingAs($user)
            ->test(Index::class)
            ->call('toggleTask', $task->id);

        $this->assertEquals('completada', $task->fresh()->estado);
    }
}
