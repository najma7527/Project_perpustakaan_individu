<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visit>
 */
class VisitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'transactions_id' => \App\Models\Transaction::factory(),
            'tanggal_datang' => fake()->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}