<?php

namespace Tests\Feature\Product\Controllers\ProductControllerTest;

use App\Domain\Product\Models\Product;
use Tests\Feature\Traits\WithAuthUser;
use Tests\Feature\Traits\WithPerformanceChecks;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class ShowTest extends TestCase
{
    use RefreshDatabase, WithAuthUser, WithPerformanceChecks;

    public function test_can_get_single_product()
    {
        $this->signIn();
        $product = Product::factory()->create();

        $response = $this->getJson("/api/v1/products/{$product->getId()}");

        $response->assertStatus(Response::HTTP_OK);
        $this->assertResponseTimeIsAcceptable($response);
    }

    public function test_returns_404_for_non_existent_product()
    {
        $this->signIn();
        $response = $this->getJson('/api/v1/products/99999');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertResponseTimeIsAcceptable($response);
    }

    public function test_product_show_performance_with_related_data()
    {
        $this->signIn();
        $product = Product::factory()->create();

        $response = $this->getJson("/api/v1/products/{$product->getId()}");

        $response->assertStatus(Response::HTTP_OK);
        $this->assertResponseTimeIsAcceptable($response);
    }
}
