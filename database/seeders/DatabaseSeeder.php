<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@system.com',
            'password' => Hash::make(12341234)
        ])->assignRole('admin');
    }
}
