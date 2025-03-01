<?php

namespace App\Interfaces;

use App\Models\Product;

interface ProductStoreInterface
{
    /**
     * Create a product
     *
     * @param array ['name' => 'string', 'description' => '', 'price' => 'decimal', 'category_id' => 'int', 'image' => 'string']
     *
     * @return Product
     */
    public function store(array $data): Product;
}
