<?php

namespace Tests;

use App\Models\User;

trait UserLogin
{
    public $user;

    public function setUpUser()
    {
        // create user
        $this->user = User::factory()->create();

        // // login user
        // $response = $this->post('login', [
        //     'email' => $this->user->email,
        //     'password' => 'password',
        // ]);

        // $this->assertAuthenticated();


        // same as above ⬆️ but shorter
        $this->actingAs($this->user);
    }
}
