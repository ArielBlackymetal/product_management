<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;


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

    public function testItCanFilterProducts()
    {
        Product::factory()->create([
            'name' => 'Producto 1',
            'price' => 100_000,
            'description' => 'Descripción del producto 1',
            'category_id' => 1,
            'image' => 'http://example.com/image.jpg'
        ]);

        $response = $this->getJson('/api/products?name=Producto&category_id=1&price[min]=1000&price[max]=200000');

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
            ->assertJsonCount(1, 'data');
    }

    public function testItCanShowAProduct()
    {
        $product = Product::factory()->create();

        $response = $this->getJson('/api/products/' . $product->id);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
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
            ])
            ->assertJson([
                'data' => [
                    'id' => $product->id,
                    'attributes' => [
                        'name' => $product->name
                    ]
                ]
            ]);
    }

    public function testItCanCreateAProduct()
    {
        Storage::fake('products');

        $data = [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->paragraph,
            'image' => UploadedFile::fake()->image('avatar.jpg'),
            'category_id' => '1'
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
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
            ]);

        $this->assertDatabaseHas('products', [
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'],
            'category_id' => $data['category_id']
        ]);
    }

    public function testItCanCreateAProductWithPlaceholderImage()
    {
        Storage::fake('products');

        $data = [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->paragraph,
            'category_id' => '1'
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
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
            ]);

        $this->assertDatabaseHas('products', [
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'],
            'category_id' => $data['category_id']
        ]);
    }

    public function testItCanCreateAProductExceptionImageService()
    {
        Storage::fake('products');

        Storage::shouldReceive('disk')->andThrow(new Exception('Image service error'));

        $data = [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->paragraph,
            'category_id' => '1'
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
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
            ]);

        $this->assertDatabaseHas('products', [
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'],
            'category_id' => $data['category_id']
        ]);
    }

    public function testItValidatesRequiredFieldsWhenCreatingAProduct()
    {
        $response = $this->postJson('/api/products', []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name', 'price', 'description', 'category_id']);
    }

    public function testItCanUpdateAProduct()
    {
        Storage::fake('products');

        $product = Product::factory()->create();

        $data = [
            'name' => 'Producto actualizado',
            'image' => UploadedFile::fake()->image('avatar.jpg'),
            'price' => 150.00,
        ];

        $response = $this->patchJson('/api/products/' . $product->id, $data);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'attributes' => [
                        'name' => 'Producto actualizado',
                        'price' => 150.00,
                    ]
                ]
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Producto actualizado',
            'price' => 150.00,
        ]);
    }

    public function testItCanDeleteAProduct()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson('/api/products/' . $product->id);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function testItShowsProductsStats()
    {
        $product = Product::factory()->create(['name' => 'Producto #1', 'price' => 100.00]);
        for ($i = 0; $i < 51; $i++) {
            Order::factory()->create([
                'product_id' => $product->id,
                'quantity' => 2
            ]);
        }

        $response = $this->getJson('/api/orders/stats');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'type',
                        'attributes' => [
                            'name',
                            'total_orders',
                            'average_price',
                            'total_revenue'
                        ]
                    ]
                ]
            ])->assertJson([
                'data' => [
                    [
                        'attributes' => [
                            'name' => 'Producto #1',
                            'total_orders' => 51,
                            'average_price' => '100.00',
                            'total_revenue' => '10200.00'
                        ]
                    ]
                ]
            ]);
    }
}
