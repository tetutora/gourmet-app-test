<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Constants\Constants;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'id' => Constants::ROLE_ADMIN,
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
        Role::create([
            'id' => Constants::ROLE_REPRESENTATIVE,
            'name' => 'representative',
            'guard_name' => 'web'
        ]);
        Role::create([
            'id' => Constants::ROLE_USER,
            'name' => 'user',
            'guard_name' => 'web'
        ]);
    }
}
