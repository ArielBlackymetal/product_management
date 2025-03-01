<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => 'product',
            'attributes' => [
                'name' => $this->name,
                'price' => $this->price,
                'description' => $this->description,
                'category_id' => $this->category_id,
                'image' => $this->image
            ],
            'links' => [
                'self' => route('products.show', $this->id)
            ]
        ];
    }
}
