<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Row>
 */
class RowFactory extends Factory
{
    public function definition(): array
    {
        return [
            'rak_id' => \App\Models\Bookshelf::factory(),
            'baris_ke' => fake()->numberBetween(1,5),
            'keterangan' => fake()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
