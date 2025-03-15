<?php

namespace Tests\Feature\Product\Controllers\ProductControllerTest;

use App\Domain\Product\Models\Product;
use Tests\Feature\Traits\WithAuthUser;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class UpdateTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    public function test_can_update_product()
    {
        $this->signIn();
        $product = Product::factory()->create();

        $updateData = [
            Product::NAME => 'Updated Name',
            Product::DESCRIPTION => 'Updated Description',
            Product::PRICE => 199.99,
            Product::RATING => 4,
            Product::CATEGORY_IDS => []
        ];

        $response = $this->putJson("/api/v1/products/{$product->getId()}", $updateData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    Product::ID => $product->getId(),
                    Product::NAME => 'Updated Name',
                    Product::DESCRIPTION => 'Updated Description',
                    Product::PRICE => 199.99,
                    Product::RATING => 4
                ]
            ]);

        $this->assertDatabaseHas('products', [
            Product::ID => $product->getId(),
            Product::NAME => 'Updated Name',
            Product::DESCRIPTION => 'Updated Description',
            Product::PRICE => 199.99,
            Product::RATING => 4
        ]);
    }
}
