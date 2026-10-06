<?php

namespace Tests\Feature;

use App\Models\InboxItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InboxTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_inbox_items()
    {
        $user = User::factory()->create();
        
        InboxItem::create([
            'user_id' => $user->id,
            'content' => 'Comprar pan',
            'status' => 'pendiente_analisis'
        ]);

        $this->actingAs($user)
            ->get(route('inbox.index'))
            ->assertSuccessful()
            ->assertSee('Comprar pan');
    }
}
