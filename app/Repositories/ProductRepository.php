<?php

namespace App\Repositories;

use App\Models\Product;
use App\Interfaces\ProductStoreInterface;
use App\Interfaces\ProductUpdateInterface;
use App\Interfaces\ProductDeleteInterface;
use App\Interfaces\ProductsStatsInterface;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductRepository implements
    ProductRepositoryInterface,
    ProductStoreInterface,
    ProductUpdateInterface,
    ProductDeleteInterface,
    ProductsStatsInterface
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

    /**
     * {@inheritdoc}
     */
    public function delete(Product $product): void
    {
        $product->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function stats(): array
    {
        $productsWithStats = DB::table('orders')
            ->select(
                'products.id',
                'products.name',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('ROUND(AVG(products.price), 2) as average_price'),
                DB::raw('SUM(products.price * orders.quantity) as total_revenue')
            )
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->groupBy('products.id', 'products.name')
            ->havingRaw('COUNT(orders.id) > 50')
            ->orderByDesc('total_revenue')
            ->get();
        return $productsWithStats->toArray();
    }
}
