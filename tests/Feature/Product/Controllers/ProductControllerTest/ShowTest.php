<?php

namespace Tests\Feature\Product\Controllers\ProductControllerTest;

use App\Domain\Product\Models\Product;
use Tests\Feature\Traits\WithAuthUser;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class ShowTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    public function test_can_get_single_product()
    {
        $this->signIn();
        $product = Product::factory()->create();

        $response = $this->getJson("/api/v1/products/{$product->getId()}");

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_returns_404_for_non_existent_product()
    {
        $this->signIn();
        $response = $this->getJson('/api/v1/products/99999');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
