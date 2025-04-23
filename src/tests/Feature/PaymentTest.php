<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\Payment;
use Database\Seeders\GenresTableSeeder;
use Database\Seeders\RegionsTableSeeder;
use Database\Seeders\RestaurantSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StatusSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Stripe\Charge;
use Tests\TestCase;

class PaymentTest extends TestCase
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
            StatusSeeder::class
        ]);

        $this->user = User::first();
        $this->actingAs($this->user);
        $this->withoutMiddleware();

        $this->restaurant = Restaurant::first();
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * 支払いフォームが正しく表示されるか
     */
    public function test_payment_form_displayed()
    {
        $response = $this->get(route('payment.form'));
        $response->assertStatus(200);
        $response->assertViewIs('payment.form');
    }

    /**
     * 支払いが成功するか
     */
    public function test_successful_payment()
    {
        $mock = Mockery::mock('alias:' . Charge::class);
        $mock->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return $arg['amount'] == 1000 && $arg['currency'] == 'jpy';
            }))
            ->andReturn((object)['id' => 'ch_test']);

        $response = $this->post(route('payment.process'), [
            'stripeToken' => 'tok_test',
            'amount' => 1000,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', '支払いが成功しました！');
    }

    /**
     * 支払いが失敗するか
     */
    public function test_failed_payment()
    {
        $mock = Mockery::mock('alias:' . Charge::class);
        $mock->shouldReceive('create')
            ->once()
            ->andThrow(new \Exception('カードが拒否されました'));

        $response = $this->post(route('payment.process'), [
            'stripeToken' => 'tok_test',
            'amount' => 1000,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
