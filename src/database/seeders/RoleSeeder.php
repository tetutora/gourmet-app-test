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
        Role::create(['name' => '管理者']);
        Role::create(['name' => '店舗代表者']);
        Role::create(['name' => '利用者']);
    }
}
