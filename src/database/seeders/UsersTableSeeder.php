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
        // 管理者
        User::create([
            'name' => 'Admin User1',
            'email' => 'admin1@example.com',
            'password' => bcrypt('adminpass1'),
            'role_id' => 1,
        ]);

        // 店舗代表者
        User::create([
            'name' => 'Admin User2',
            'email' => 'admin2@example.com',
            'password' => bcrypt('adminpass2'),
            'role_id' => 2,
        ]);

        // 利用者
        User::create([
            'name' => 'Test User1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password1'),
            'role_id' => 3,
        ]);
    }
}
