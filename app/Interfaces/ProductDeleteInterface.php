<?php

namespace App\Interfaces;

use App\Models\Product;

interface ProductDeleteInterface
{
    /**
     * Delete product
     *
     * @param Product $product
     * @param array $data
     */
    public function delete(Product $product): void;
}
