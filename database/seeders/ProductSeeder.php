<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [1, 2, 3, 4];

        for ($i = 0; $i < 30; $i++) {
            Product::create([
                'name' => 'Producto ' . ($i + 1),
                'price' => rand(100_000, 2_000_000) / 100,
                'description' => 'Esta es la descripción del producto ' . ($i + 1),
                'category_id' => $categories[array_rand($categories)],
                'image' => 'https://picsum.photos/id/' . ($i + 10) . '/400/300',
            ]);
        }
    }
}
