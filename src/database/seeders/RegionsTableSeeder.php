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
            ['id' => 1, 'name' => '東京都'],
            ['id' => 2, 'name' => '大阪府'],
            ['id' => 3, 'name' => '福岡県'],
            ['id' => 4, 'name' => '愛知県'],
            ['id' => 5, 'name' => '北海道'],
            ['id' => 6, 'name' => '京都府'],
            ['id' => 7, 'name' => '兵庫県'],
            ['id' => 8, 'name' => '沖縄県'],
        ]);
    }
}
