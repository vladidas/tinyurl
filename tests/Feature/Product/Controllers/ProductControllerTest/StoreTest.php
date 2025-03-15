<?php

namespace Tests\Feature\Product\Controllers\ProductControllerTest;

use App\Domain\Product\Models\Product;
use Tests\Feature\Traits\WithAuthUser;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class StoreTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    public function test_can_create_product()
    {
        $this->signIn();
        $productData = [
            Product::NAME => 'Test Product',
            Product::DESCRIPTION => 'Test Description',
            Product::PRICE => 99.99,
            Product::RATING => 5,
            Product::CATEGORY_IDS => []
        ];

        $response = $this->postJson('/api/v1/products', $productData);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => [
                    Product::NAME => 'Test Product',
                    Product::DESCRIPTION => 'Test Description',
                    Product::PRICE => 99.99,
                    Product::RATING => 5
                ]
            ]);

        $this->assertDatabaseHas('products', [
            Product::NAME => 'Test Product',
            Product::DESCRIPTION => 'Test Description',
            Product::PRICE => 99.99,
            Product::RATING => 5
        ]);
    }

    public function test_validates_required_fields_when_creating()
    {
        $this->signIn();
        $response = $this->postJson('/api/v1/products', []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                Product::NAME,
                Product::PRICE
            ]);
    }

    public function test_validates_price_is_numeric()
    {
        $this->signIn();
        $response = $this->postJson('/api/v1/products', [
            Product::NAME => 'Test Product',
            Product::PRICE => 'not-a-number'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                Product::PRICE
            ]);
    }
}
