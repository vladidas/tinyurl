<?php

namespace Tests\Feature\Product\Controllers\ProductControllerTest;

use App\Domain\Product\Models\Product;
use Tests\Feature\Traits\WithAuthUser;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class DeleteTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    public function test_can_delete_product()
    {
        $this->signIn();
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/v1/products/{$product->getId()}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertSoftDeleted('products', [
            Product::ID => $product->getId()
        ]);
    }
}
