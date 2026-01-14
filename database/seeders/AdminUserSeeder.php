<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create or update admin account and ensure password is hashed
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'kelas' => null,
            ]
        );
    }
}
