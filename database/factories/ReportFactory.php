<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'transactions_id' => \App\Models\Transaction::factory(),
            'tanggal_ganti' => fake()->date(),
            'jenis_transaksi' => fake()->randomElement(['dipinjam','dikembalikan']),
            'status' => fake()->randomElement([
                'pending',
                'sudah_dikembalikan',
                'belum_dikembalikan',
                'approved',
                'rejected'
            ]),
            'keterangan' => fake()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}