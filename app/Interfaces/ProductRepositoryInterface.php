<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    /**
     * Get records by 10 and apply given filters
     *
     * @param array $filters default empty array
     * @param int $size page size, default 10
     *
     * @return LengthAwarePaginator
     */
    public function get(array $filters = [], int $size = 10): LengthAwarePaginator;
}
