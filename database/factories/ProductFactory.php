<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'price' => fake()->numberBetween(100_000, 2_500_000),
            'image' => fake()->imageUrl(200, 200),
            'description' => fake()->paragraph(),
            'category_id' => fake()->numberBetween(1, 4)
        ];
    }
}
