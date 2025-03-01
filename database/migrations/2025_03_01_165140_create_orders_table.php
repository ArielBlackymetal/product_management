<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->integer('user_id');
            $table->integer('quantity');
            $table->timestamp('order_date');
            $table->timestamps();

            // Index to retreive all products that belongs to an order
            $table->index('product_id');
            // Index just in case
            $table->index('user_id');
            // Index just in case
            $table->index('order_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
