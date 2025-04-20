<?php

namespace Tests\Feature;

use App\Constants\Constants;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Reservation;
use App\Notifications\ReservationReminder;
use Database\Seeders\GenresTableSeeder;
use Database\Seeders\RegionsTableSeeder;
use Database\Seeders\RestaurantSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StatusSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Carbon;
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
            RoleSeeder::class,
            RegionsTableSeeder::class,
            GenresTableSeeder::class,
            RestaurantSeeder::class,
            UsersTableSeeder::class,
            StatusSeeder::class,
        ]);

        $this->user = User::where('role_id', Constants::ROLE_USER)->first();
        $this->actingAs($this->user);
        $this->restaurant = Restaurant::first();
    }

    /**
     * 店舗詳細ページが正しく表示されることを確認する
     */
    public function test_restaurant_details_are_displayed()
    {
        $response = $this->get(route('restaurants.detail', $this->restaurant));

        $response->assertStatus(200);
        $response->assertSee($this->restaurant->name);
        $response->assertSee($this->restaurant->description);
        $response->assertSee($this->restaurant->image_url);
        $response->assertSee($this->restaurant->region->name);
        foreach ($this->restaurant->genres as $genre) {
            $response->assertSee($genre->name);
        }
    }

    /**
     * 予約入力エラー時にバリデーションエラーが表示されることを確認する
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
     * 予約情報が正しく保存されることを確認する
     */
    public function test_reservation_is_successfully_stored()
    {
        $response = $this->post(route('reservation.store'), [
            'restaurant_id' => $this->restaurant->id,
            'reservation_date' => '2026-10-10',
            'reservation_time' => '12:30:00',
            'num_people' => 2,
            'payment_method' => 'card',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('reservations', [
            'user_id' => $this->user->id,
            'restaurant_id' => $this->restaurant->id,
            'reservation_date' => '2026-10-10',
            'reservation_time' => '12:30:00',
            'num_people' => 2,
        ]);
    }

    /**
     * リマインダーメール通知が送信されることを確認する
     */
    public function test_reservation_reminder()
    {
        Notification::fake();

        $reservation = Reservation::create([
            'user_id' => $this->user->id,
            'restaurant_id' => $this->restaurant->id,
            'reservation_date' => Carbon::today(),
            'reservation_time' => '18:00:00',
            'num_people' => 2,
            'status_id' => Constants::RESERVATION_STATUS_BOOKED,
        ]);

        $this->user->notify(new ReservationReminder($reservation));

        Notification::assertSentTo(
            $this->user,
            ReservationReminder::class,
            function ($notification, $channels) use ($reservation) {
                return $notification->reservation->id === $reservation->id &&
                    in_array('mail', $channels);
            }
        );
    }

    /**
     * 予約に対してQRコードが生成されて保存されることを確認する
     */
    public function test_qrcode_is_generated_for_reservation()
    {
        $reservation = Reservation::create([
            'user_id' => $this->user->id,
            'restaurant_id' => $this->restaurant->id,
            'reservation_date' => Carbon::today(),
            'reservation_time' => '18:00:00',
            'num_people' => 2,
            'status_id' => Constants::RESERVATION_STATUS_BOOKED,
        ]);

        $qrCode = new QrCode("reservation/{$reservation->id}");
        $writer = new PngWriter();
        $path = "qrcodes/qr_{$reservation->id}.png";

        $writer->write($qrCode)->saveToFile(storage_path("app/public/{$path}"));

        $this->assertTrue(Storage::disk('public')->exists($path));
    }

    /**
     * 生成されたQRコードのデータが正しいことを確認する
     */
    public function test_qr_code_data_is_valid()
    {
        $reservation = Reservation::create([
            'user_id' => $this->user->id,
            'restaurant_id' => $this->restaurant->id,
            'reservation_date' => Carbon::today(),
            'reservation_time' => '18:00:00',
            'num_people' => 2,
            'status_id' => Constants::RESERVATION_STATUS_BOOKED,
        ]);

        $expectedText = "reservation/{$reservation->id}";

        $qrCode = new QrCode($expectedText);
        $writer = new PngWriter();
        $path = "qrcodes/qr_{$reservation->id}.png";
        $writer->write($qrCode)->saveToFile(storage_path("app/public/{$path}"));

        $this->assertTrue(Storage::disk('public')->exists($path));
        $this->assertEquals($expectedText, $qrCode->getData());
    }
}