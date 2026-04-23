<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RendezVousTest extends TestCase
{
    use RefreshDatabase;

    public function test_rendezvous_list_requires_auth()
    {
        $response = $this->get('/rendezvous');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_see_rendezvous()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($user)->get('/rendezvous');
        $response->assertStatus(200);
    }
}