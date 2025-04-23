<?php

namespace Tests\Feature;

use App\Constants\Constants;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\User;
use Database\Seeders\GenresTableSeeder;
use Database\Seeders\RegionsTableSeeder;
use Database\Seeders\RestaurantSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StatusSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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

    /**
     * ユーザーがレビュー作成ページにアクセスできるか
     */
    public function test_User_can_access_review_create_page()
    {
        $reviewUrl = route('review.create', ['reservation' => $this->completedReservation->id]);
        $response = $this->get($reviewUrl);

        $response->assertSee('レビュー投稿');
    }

    /**
     * レビューに評価とコメントが必須であるか
     */
    public function test_review_requires_rating_and_comment()
    {
        $this->withoutMiddleware();
        $response = $this->actingAs($this->user)->post(route('review.store', ['reservation' => $this->completedReservation->id]), [
            'rating' => '',
            'comment' => '',
        ]);

        $response->assertSessionHasErrors(['rating', 'comment']);
    }

    /**
     * レビューを作成し保存できるか
     */
    public function test_user_can_create_and_store_review()
    {
        $this->withoutMiddleware();

        $response = $this->actingAs($this->user)->post(route('review.store', ['reservation' => $this->completedReservation->id]), [
            'user_id' => auth()->id(),
            'restaurant_id' => $this->restaurant->id,
            'reservation_id' => $this->completedReservation->id,
            'rating' => 5,
            'comment' => '素晴らしい体験でした！',
        ]);

        $response->assertRedirect(route('mypage'));

        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->user->id,
            'restaurant_id' => $this->restaurant->id,
            'reservation_id' => $this->completedReservation->id,
            'rating' => 5,
            'comment' => '素晴らしい体験でした！',
        ]);

        $response->assertRedirect(route('mypage'));
        $response->assertSessionHas('success', 'レビューを投稿しました。');
    }
}
