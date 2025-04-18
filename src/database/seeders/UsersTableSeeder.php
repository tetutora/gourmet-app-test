<?php

namespace Database\Seeders;

use App\Constants\Constants;
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
            'role_id' => Constants::ROLE_ADMIN,
        ]);

        // 店舗代表者
        User::create([
            'name' => 'Admin User2',
            'email' => 'admin2@example.com',
            'password' => bcrypt('adminpass2'),
            'role_id' => Constants::ROLE_REPRESENTATIVE,
        ]);

        User::create([
            'name' => 'Admin User3',
            'email' => 'admin3@example.com',
            'password' => bcrypt('adminpass3'),
            'role_id' => Constants::ROLE_REPRESENTATIVE,
        ]);

        // 利用者
        User::create([
            'name' => 'Test User1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password1'),
            'role_id' => Constants::ROLE_USER,
        ]);
    }
}
