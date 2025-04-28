<?php

namespace Database\Seeders;

use App\Constants\Constants;
use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::insert([
            ['id' => Constants::RESERVATION_STATUS_BOOKED, 'name' => '予約済み'],
            ['id' => Constants::RESERVATION_STATUS_COMPLETED, 'name' => '来店済み'],
            ['id' => Constants::RESERVATION_STATUS_CANCELLED, 'name' => 'キャンセル'],
        ]);
    }
}
