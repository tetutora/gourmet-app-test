<?php

namespace Tests\Feature;

use App\Constants\Constants;
use App\Mail\NotificationMail;
use App\Models\User;
use Database\Seeders\GenresTableSeeder;
use Database\Seeders\RegionsTableSeeder;
use Database\Seeders\RestaurantSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StatusSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RoleSeeder::class,
            RegionsTableSeeder::class,
            GenresTableSeeder::class,
            RestaurantSeeder::class,
            UsersTableSeeder::class,
            StatusSeeder::class
        ]);

        $this->user = User::first();
        $this->actingAs($this->user);
        $this->withoutMiddleware();
    }

    public function test_it_validates_required_message_field()
    {
        $admin = User::factory()->create(['role_id' => Constants::ROLE_ADMIN]);
        $this->actingAs($admin);

        $response = $this->post(route('administrator.notify.send'), [
            'message' => '',
        ]);

        $response->assertSessionHasErrors('message');
    }

    public function test_it_sends_notification_email_to_users()
    {
        $admin = User::factory()->create(['role_id' => Constants::ROLE_ADMIN]);
        $this->actingAs($admin);

        $targetUser = User::factory()->create(['role_id' => Constants::ROLE_USER]);

        Mail::fake();

        $response = $this->post(route('administrator.notify.send'), [
            'message' => 'テストメッセージ',
        ]);

        Mail::assertSent(NotificationMail::class, function ($mail) use ($targetUser) {
            return $mail->hasTo($targetUser->email);
        });

        $response->assertRedirect(route('administrator.mail'));
        $response->assertSessionHas('success', 'お知らせメールを送信しました');
    }
}

