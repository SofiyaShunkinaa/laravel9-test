<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Role::firstOrCreate(['name' => 'admin']);
        \App\Models\Role::firstOrCreate(['name' => 'editor']);
        \App\Models\Role::firstOrCreate(['name' => 'viewer']);
    }
}
