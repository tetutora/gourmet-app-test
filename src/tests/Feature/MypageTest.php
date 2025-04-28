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

class MypageTest extends TestCase
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

        $this->restaurant = Restaurant::first();
    }

    /**
     * 予約状況が確認できるか
     */
    public function test_reservation_status_is_displayed_correctly()
    {
        Reservation::create([
            'user_id' => $this->user->id,
            'restaurant_id' => $this->restaurant->id,
            'reservation_date' => now()->addDays(1)->toDateString(),
            'reservation_time' => '18:00',
            'num_people' => 4,
            'payment_method' => 'credit_card',
        ]);

        $response = $this->get(route('mypage'));

        $response->assertStatus(200)
            ->assertSee($this->restaurant->name)
            ->assertSee('18:00')
            ->assertSee('4');
    }

    /**
     * 予約が変更できるか
     */
    public function test_reservation_can_be_updated()
    {
        $reservation = Reservation::create([
            'user_id' => $this->user->id,
            'restaurant_id' => $this->restaurant->id,
            'reservation_date' => now()->addDays(1)->toDateString(),
            'reservation_time' => '18:00',
            'num_people' => 4,
            'payment_method' => 'credit_card',
        ]);

        $updatedDate = now()->addDays(2)->toDateString();
        $updatedTime = '19:00';
        $updatedNumPeople = 6;

        $response = $this->post(route('reservations.update', $reservation), [
            'reservation_date' => $updatedDate,
            'reservation_time' => $updatedTime,
            'num_people' => $updatedNumPeople,
            'payment_method' => 'credit_card',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'reservation_date' => $updatedDate,
            'reservation_time' => date('H:i:s', strtotime($updatedTime)),
            'num_people' => $updatedNumPeople,
            'payment_method' => 'credit_card',
        ]);
    }

    /**
     * お気に入り店舗が正しく表示されるか
     */
    public function test_favorite_restaurants_are_displayed_correctly()
    {
        $restaurant = Restaurant::first();
        $this->user->favorites()->create(['restaurant_id' => $restaurant->id]);

        $response = $this->get(route('mypage'));

        $response->assertStatus(200);

        $response->assertSee($restaurant->name);
    }
}
