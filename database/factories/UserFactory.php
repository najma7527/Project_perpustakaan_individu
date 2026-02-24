<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'nis_nisn' => fake()->numerify('########'),
            'telephone' => fake()->phoneNumber(),
            'role' => fake()->randomElement(['anggota']),
            'status' => fake()->randomElement(['aktif','nonaktif','menunggu','ditolak']),
            'password' => bcrypt('password'),
            'kelas' => fake()->randomElement(['XI RPL 1','XI RPL 2','XI RPL 3']),
            'profile_photo' => 'default.jpg',
            'tanggal_pengajuan' => now(),
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}