<?php

namespace App\Interfaces;

use App\Models\Product;

interface ProductUpdateInterface
{
    /**
     * Update product
     *
     * @param Product $product
     * @param array $data
     *
     * @return Product
     */
    public function update(Product $product, array $data): Product;
}
