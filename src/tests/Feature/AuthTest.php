<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 会員登録ができるか
     */
    public function test_registration()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com'
        ]);
    }

    /**
     *ログインできるか
     */
    public function test_login()
    {
        $user = new \App\Models\User();
        $user->name = 'Test User';
        $user->email = 'testuser@example.com';
        $user->password = \Illuminate\Support\Facades\Hash::make('password123');
        $user->save();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }
}
