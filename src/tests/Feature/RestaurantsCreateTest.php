<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RestaurantsCreateTest extends TestCase
{
    use RefreshDatabase;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            \Database\Seeders\RoleSeeder::class,
            \Database\Seeders\RegionsTableSeeder::class,
            \Database\Seeders\GenresTableSeeder::class,
            \Database\Seeders\UsersTableSeeder::class,
        ]);

        $this->user = User::where('role_id', 2)->first();
        $this->actingAs($this->user);
    }
    /**
     * バリデーションメッセージが表示されるか
     */
    public function test_validation_errors()
    {
        $response = $this->post(route('restaurants.store'), []);

        $response->assertSessionHasErrors([
            'name',
            'description',
            'region_id',
        ]);
    }

    /**
     * 画像ではないファイルをアップロードするとバリデーションエラーが表示されるか
     */
    public function test_restaurant_image_must_be_valid_image_file()
    {
        Storage::fake('public');

        $region = Region::first();

        $response = $this->post(route('restaurants.store'), [
            'name' => 'Test Restaurant',
            'description' => 'Thi is test',
            'region_id' => $region->id,
            'image_url' => UploadedFile::fake()->create('document.txt', 100, 'text/plain'),
        ]);

        $response->assertSessionHasErrors(['image_url']);
    }

    /**
     * 作成したデータがデータベースに保存され、店舗一覧ページに表示できるか
     */
    public function test_restaurant_can_be_created_and_stored_in_databases()
    {
        Storage::fake('public');

        $region = Region::first();
        $genre = Genre::first();
        $image = UploadedFile::fake()->image('store.jpg');

        $response = $this->post(route('restaurants.store'), [
            'name' => 'Test Restaurant',
            'description' => 'Thi is test',
            'region_id' => $region->id,
            'genre_ids' => [$genre->id],
            'image_url' => $image,
        ]);

        $response->assertRedirect(route('representative.index'));

        $this->assertDatabaseHas('restaurants', [
            'name' => 'Test Restaurant',
            'description' => 'Thi is test',
            'region_id' => $region->id,
        ]);

        Storage::disk('public')->assertExists('restaurants/' . $image->hashName());
    }

    /**
     * 新しいジャンル欄で入力されたジャンルがジャンルテーブルに追加され、店舗と正しく紐づけられているか
     */
    public function test_new_genres_can_be_created_and_attached_to_restaurant()
    {
        $region = Region::first();
        $image = UploadedFile::fake()->image('store.jpg');

        $response = $this->post(route('restaurants.store'), [
            'name' => 'Test Restaurant',
            'description' => 'Thi is test',
            'region_id' => $region->id,
            'new_genres' => 'カレー,バル',
            'image_url' => $image,
        ]);

        $response->assertRedirect(route('representative.index'));

        $this->assertDatabaseHas('genres', ['name' => 'カレー']);
        $this->assertDatabaseHas('genres', ['name' => 'バル']);

        $restaurantId = \App\Models\Restaurant::where('name', 'Test Restaurant')->first()->id;
        $curry = \App\Models\Genre::where('name', 'カレー')->first();
        $bar = \App\Models\Genre::where('name', 'バル')->first();

        $this->assertDatabaseHas('genre_restaurant', [
            'restaurant_id' => $restaurantId,
            'genre_id' => $curry->id,
        ]);

        $this->assertDatabaseHas('genre_restaurant', [
            'restaurant_id' => $restaurantId,
            'genre_id' => $bar->id,
        ]);
    }
}
