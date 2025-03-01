<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testItCanListProducts()
    {
        Product::factory(15)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'type',
                        'attributes' => [
                            'name',
                            'price',
                            'description',
                            'category_id',
                            'image',
                        ],
                        'links'
                    ]
                ],
                'meta',
                'links'
            ])
            ->assertJsonCount(10, 'data');
    }
}
