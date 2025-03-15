<?php

namespace Tests\Feature\Product\Controllers\ProductControllerTest;

use App\Domain\Product\Models\Product;
use Tests\Feature\Traits\WithAuthUser;
use Tests\Feature\Traits\WithPerformanceChecks;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class IndexTest extends TestCase
{
    use RefreshDatabase, WithAuthUser, WithPerformanceChecks;

    public function test_can_get_products_list()
    {
        $this->signIn();
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        Product::ID,
                        Product::NAME,
                        Product::DESCRIPTION,
                        Product::PRICE,
                        Product::RATING,
                        Product::CREATED_AT,
                        Product::UPDATED_AT,
                    ]
                ],
                'meta' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page'
                ]
            ]);

        $this->assertResponseTimeIsAcceptable($response);
    }

    public function test_products_list_performance_with_large_dataset()
    {
        $this->signIn();
        Product::factory()->count(100)->create();

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(Response::HTTP_OK);
        $this->assertResponseTimeIsAcceptable($response);
    }
}
