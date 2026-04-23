<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_accessible()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_admin_can_login()
    {
        $user = User::factory()->create([
            'role'     => 'admin',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
    }

    public function test_wrong_password_fails()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'mauvais_mot_de_passe',
        ]);

        $this->assertGuest();
    }
}