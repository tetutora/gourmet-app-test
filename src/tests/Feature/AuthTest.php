<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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

        $response->assertRedirect('/thanks');

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

    /**
     *ログイン時にバリデーションメッセージが表示されるか
     */
    public function test_login_validation()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください。']);
        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください。']);
    }

    /**
     *会員登録時にバリデーションメッセージが表示されるか
     */
    public function test_register_validation()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $response->assertSessionHasErrors(['name' => '名前を入力してください。']);
        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください。']);
        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください。']);
    }

    /**
     *認証が失敗した際にエラーメッセージが表示されるか
     */
    public function test_login_with_invalid_password()
    {
        $user = new \App\Models\User();
        $user->name = 'Test User';
        $user->email = 'testuser@example.com';
        $user->password = \Illuminate\Support\Facades\Hash::make('password123');
        $user->save();

        $response = $this->post('/login', [
            'email' => 'testuser@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email' => '認証が失敗しました。']);
    }
}