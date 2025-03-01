<?php

namespace App\Repositories;

use App\Models\Product;
use App\Interfaces\ProductStoreInterface;
use App\Interfaces\ProductUpdateInterface;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface, ProductStoreInterface, ProductUpdateInterface
{
    /**
     * {@inheritdoc}
     */
    public function get(array $filters = [], int $size = 10): LengthAwarePaginator
    {
        $query = Product::select('*');
        if (isset($filters['name'])) {
            $query = $query->where('products.name', 'like', sprintf('%%%s%%', $filters['name']));
        }
        if (isset($filters['price'], $filters['price']['min'], $filters['price']['max'])) {
            $query = $query->whereBetween('products.price', [$filters['price']['min'], $filters['price']['max']]);
        }
        if (isset($filters['category_id'])) {
            $query = $query->where('products.category_id', $filters['category_id']);
        }
        return $query->paginate($size);
    }

    /**
     * {@inheritdoc}
     */
    public function store(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }
}
