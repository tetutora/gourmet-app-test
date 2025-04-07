<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Test User1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password1'),
            'role_id' => 3,
        ]);
    }
}
