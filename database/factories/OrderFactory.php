<?php

namespace Database\Factories;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = Carbon::now()->subMonth();
        $randomDate = $date->adddays(rand(1, 30));
        return [
            'quantity' => rand(1, 10),
            'product_id' => rand(1, 30),
            'user_id' => rand(1, 3),
            'order_date' => $randomDate->format('Y-m-d H:i:s'),
            'created_at' => $randomDate->format('Y-m-d H:i:s')
        ];
    }
}
