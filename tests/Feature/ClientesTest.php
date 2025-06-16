<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientesTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_requires_authentication(): void
    {
        $response = $this->get('/clientes');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_index(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/clientes');
        $response->assertStatus(200);
    }
}
