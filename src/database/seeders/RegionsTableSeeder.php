<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('regions')->insert([
            ['name' => '東京都'],
            ['name' => '大阪府'],
            ['name' => '福岡県'],
            ['name' => '愛知県'],
            ['name' => '北海道'],
            ['name' => '京都府'],
            ['name' => '兵庫県'],
            ['name' => '沖縄県'],
        ]);
    }
}
