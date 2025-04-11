<?php

namespace Tests\Feature;

use App\Models\Restaurant;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\RegionsTableSeeder;
use Database\Seeders\GenresTableSeeder;
use Database\Seeders\RestaurantSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestaurantListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * シードメソッド
     */
    protected function seedDatabase()
    {
        $this->seed([
            \Database\Seeders\RoleSeeder::class,
            \Database\Seeders\RegionsTableSeeder::class,
            \Database\Seeders\GenresTableSeeder::class,
            \Database\Seeders\RestaurantSeeder::class,
            \Database\Seeders\UsersTableSeeder::class,
        ]);
    }

    /**
     * ユーザー作成とログイン
     */
    protected function createUserLogin($email = 'testuser@example.com', $password = 'password123')
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => $email,
            'password' => bcrypt($password),
            'role_id' => 3,
        ]);
        $this->actingAs($user);

        return $user;
    }
    /**
     * 飲食店一覧が表示されるか
     */
    public function test_can_display_restaurant_list()
    {
        $this->seedDatabase();

        $restaurants = Restaurant::all();
        $response = $this->get('/');

        $response->assertStatus(200);

        if ($restaurants->isNotEmpty())
        {
            $response->assertSee($restaurants->first()->name);
        }
    }

    /**
     * 詳しく見るボタンで店舗詳細が表示されるか
     */
    public function test_can_view_restaurant_details()
    {
        $this->seedDatabase();

        $restaurant = Restaurant::first();

        $response = $this->get(route('restaurants.detail', $restaurant->id));

        $response->assertStatus(200);
        $response->assertSee($restaurant->name);
        $response->assertSee($restaurant->description);
    }

    /**
     * お気に入り登録ができるか
     */
    public function test_can_favorite_restaurant()
    {
        $this->seedDatabase();

        $user = $this->createUserLogin();
        $restaurant = Restaurant::first();

        $response = $this->post(route('favorites.add', $restaurant->id));
        $response->assertStatus(200);
        $this->assertTrue($user->favorites()->where('restaurant_id', $restaurant->id)->exists());
    }

    /**
     * お気に入り解除ができるか
     */
    public function test_can_unfavorite_restaurant()
    {
        $this->seedDatabase();

        $user = $this->createUserLogin();
        $restaurant = Restaurant::first();

        $this->post(route('favorites.add', $restaurant->id));
        $response = $this->post(route('favorites.remove', $restaurant->id));
        $response->assertStatus(200);

        $this->assertFalse($user->favorites()->where('restaurant_id', $restaurant->id)->exists());
    }
}
