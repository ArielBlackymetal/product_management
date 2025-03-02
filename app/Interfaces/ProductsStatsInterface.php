<?php

namespace App\Interfaces;

interface ProductsStatsInterface
{
    /**
     * Get products stats
     *
     * @return array
     */
    public function stats(): array;
}
