<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GenresTableSeeder::class,
            RegionsTableSeeder::class,
            RestaurantSeeder::class,
            RoleSeeder::class,
            UsersTableSeeder::class,
            StatusSeeder::class,
        ]);
    }
}
