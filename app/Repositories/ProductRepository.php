<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
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
}
