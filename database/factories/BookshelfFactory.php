<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bookshelf>
 */
class BookshelfFactory extends Factory
{
    public function definition(): array
    {
        return [
            'no_rak' => fake()->numberBetween(100,200),
            'keterangan' => fake()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
