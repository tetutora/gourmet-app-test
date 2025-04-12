<?php

namespace Database\Seeders;

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
            ['id' => 1, 'name' => '予約済み'],
            ['id' => 2, 'name' => '来店済み'],
        ]);
    }
}
