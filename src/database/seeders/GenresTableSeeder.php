<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('genres')->insert([
            ['id' => 1, 'name' => '和食'],
            ['id' => 2, 'name' => '洋食'],
            ['id' => 3, 'name' => '中華'],
            ['id' => 4, 'name' => 'イタリアン'],
            ['id' => 5, 'name' => '寿司'],
            ['id' => 6, 'name' => '焼肉'],
            ['id' => 7, 'name' => '居酒屋'],
            ['id' => 8, 'name' => 'ラーメン'],
        ]);
    }
}
