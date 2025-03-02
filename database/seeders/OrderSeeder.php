<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $date = Carbon::create(2025,2,1);
        $randomDate = $date->adddays(rand(1, 28));
        for ($i = 0; $i < 300; $i++) {
            Order::create([
                'quantity' => rand(1, 10),
                'product_id' => rand(1, 30),
                'user_id' => 1,
                'order_date' => $randomDate->format('Y-m-d H:i:s'),
                'created_at' => $randomDate->format('Y-m-d H:i:s')
            ]);
        }
    }
}
