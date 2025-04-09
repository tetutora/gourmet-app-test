<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['id' => 1, 'name' => '管理者', 'guard_name' => 'web']);
        Role::create(['id' => 2, 'name' => '店舗代表者', 'guard_name' => 'web']);
        Role::create(['id' => 3, 'name' => '利用者', 'guard_name' => 'web']);
    }
}
