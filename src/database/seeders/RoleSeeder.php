<?php

namespace Database\Seeders;

use App\Constants\RoleType;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['id' => 1, 'name' => RoleType::ADMIN, 'guard_name' => 'web']);
        Role::create(['id' => 2, 'name' => RoleType::REPRESENTATIVE, 'guard_name' => 'web']);
        Role::create(['id' => 3, 'name' => RoleType::USER, 'guard_name' => 'web']);

    }
}
