<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\Region;
use App\Models\Restaurant;
use App\Models\User;
use Database\Seeders\GenresTableSeeder;
use Database\Seeders\RegionsTableSeeder;
use Database\Seeders\RestaurantSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $restaurant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            \Database\Seeders\RoleSeeder::class,
            \Database\Seeders\RegionsTableSeeder::class,
            \Database\Seeders\GenresTableSeeder::class,
            \Database\Seeders\RestaurantSeeder::class,
            \Database\Seeders\UsersTableSeeder::class,
        ]);

        $this->user = User::where('role_id', 3)->first();
        $this->actingAs($this->user);

        $this->restaurant = Restaurant::first();
    }

    /**
     * 店舗詳細画面に店舗名・説明文・店舗写真・地域・ジャンルが表示されるか
     */
    public function test_restaurant_details_are_displayed()
    {
        $response = $this->get(route('restaurants.detail', $this->restaurant));

        $response->assertStatus(200);
        $response->assertSee($this->restaurant->name);
        $response->assertSee($this->restaurant->description);
        $response->assertSee($this->restaurant->image_url);
        $response->assertSee($this->restaurant->region->name);
        $response->assertSee($this->restaurant->genre->name);
    }

    /**
     * 予約時に未記入があるとバリデーションメッセージが表示されるか
     */
    public function test_validation_errors_are_displayed_when_reservation_fails()
    {
        $response = $this->post(route('reservation.store'),[
            'restaurant_id' => $this->restaurant->id,
            'restaurant_date' => '',
            'restaurant_time' => '',
            'num_people' => '',
        ]);

        $response->assertSessionHasErrors([
            'reservation_date' => '日付を選択してください。',
            'reservation_time' => '予約時間を選択してください。',
            'num_people' => '予約人数を入力してください。',
        ]);
    }

    /**
     * 予約ができるか
     */
    public function test_reservation_is_successfully_stored()
    {
        $response = $this->post(route('reservation.store'), [
            'restaurant_id' => $this->restaurant->id,
            'reservation_date' => '2026-10-10',
            'reservation_time' => '12:30:00',
            'num_people' => 2,
        ]);

        $response->assertRedirect(route('reservation.complete'));

        $this->assertDatabaseHas('reservations', [
            'user_id' => $this->user->id,
            'restaurant_id' => $this->restaurant->id,
            'reservation_date' => '2026-10-10',
            'reservation_time' => '12:30:00',
            'num_people' => 2,
        ]);
    }
}
