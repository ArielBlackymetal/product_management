<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => 'product_stats',
            'attributes' => [
                'name' => $this->name,
                'total_orders' => $this->total_orders,
                'average_price' => $this->average_price,
                'total_revenue' => $this->total_revenue
            ]
        ];
    }
}
