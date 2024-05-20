<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_login_view_load(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_user_login_with_email_and_password()
    {
        // create user
        $user = User::factory()->create();

        // login user
        $this->post('login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
    }
}
