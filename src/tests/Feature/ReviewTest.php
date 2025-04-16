<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\User;
use Database\Seeders\GenresTableSeeder;
use Database\Seeders\RegionsTableSeeder;
use Database\Seeders\RestaurantSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StatusSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Constants\Constants;

class ReviewTest extends TestCase
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

        $this->user = User::where('role_id', Constants::ROLE_USER)->first();
        $this->restaurant = Restaurant::first();

        $this->actingAs($this->user);

        $this->completedReservation = Reservation::create([
            'user_id' => $this->user->id,
            'restaurant_id' => $this->restaurant->id,
            'reservation_date' => now()->toDateString(),
            'reservation_time' => now()->format('H:i'),
            'num_people' => 2,
            'status_id' => Constants::RESERVATION_STATUS_COMPLETED,
        ]);
    }

    public function test_User_can_access_review_create_page()
    {
        $response = $this->get('/mypage');
        $response->assertStatus(200);
        $response->assertSee('レビューを投稿');

        $reviewUrl = route('review.create', ['reservation' => $this->completedReservation->id]);
        $response = $this->get($reviewUrl);
        $response->assertStatus(200);
        $response->assertSee('レビュー投稿');
    }

    public function test_user_can_create_review_for_completed_reservation()
    {
        $response = $this->post(route('review.store', ['reservation' => $this->completedReservation->id]), [
            'rating' => 5,
            'comment' => '素晴らしい体験でした！'
        ]);

        $response->assertRedirect(route('restaurants.detail', ['restaurant' => $this->restaurant->id]));
        $response->assertSessionHas('success', 'レビューを投稿しました。');

        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->user->id,
            'restaurant_id' => $this->restaurant->id,
            'reservation_id' => $this->completedReservation->id,
            'rating' => 5,
            'comment' => '素晴らしい体験でした！'
        ]);
    }

    public function test_review_requires_rating_and_comment()
    {
        $response = $this->post(route('review.store', ['reservation' => $this->completedReservation->id]), [
            'rating' => '',
            'comment' => '',
        ]);

        $response->assertSessionHasErrors(['rating', 'comment']);
    }
}
