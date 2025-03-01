<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [1 => 'Tecnología', 2 => 'Hogar', 3 => 'Mascotas', 4 => 'Cocina'];

        foreach ($categories as $id => $name) {
            Category::create([
                'id' => $id,
                'name' => $name
            ]);
        }
    }
}
