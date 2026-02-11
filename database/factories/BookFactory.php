<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kode_buku' => fake()->unique()->bothify('BK###'),
            'judul' => fake()->sentence(3),
            'pengarang' => fake()->name(),
            'tahun_terbit' => fake()->year(),
            'kategori_buku' => fake()->randomElement(['fiksi','nonfiksi']),
            'id_baris' => \App\Models\Row::factory(),
            'cover' => 'cover.jpg',
            'deskripsi' => fake()->paragraph(),
            'status' => fake()->randomElement(['tersedia','dipinjam']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}