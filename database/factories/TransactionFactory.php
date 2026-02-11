<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'buku_id' => \App\Models\Book::factory(),
            'tanggal_peminjaman' => fake()->date(),
            'tanggal_jatuh_tempo' => fake()->date(),
            'tanggal_pengembalian' => fake()->optional()->date(),
            'jenis_transaksi' => fake()->randomElement(['dipinjam','dikembalikan']),
            'status' => fake()->randomElement([
                'buku_hilang',
                'sudah_dikembalikan',
                'belum_dikembalikan',
                'menunggu_konfirmasi'
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}