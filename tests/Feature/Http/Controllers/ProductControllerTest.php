<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Domain\Product\Models\Product;
use App\Domain\Category\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product(): void
    {
        $data = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
        ];

        $response = $this->postJson('/api/v1/products', $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'price' => $data['price'],
                ]
            ]);

        $this->assertDatabaseHas('products', $data);
    }

    public function test_cannot_create_product_with_invalid_data(): void
    {
        $response = $this->postJson('/api/v1/products', [
            'name' => '',
            'price' => -1,
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price']);
    }

    public function test_can_list_products(): void
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/products');

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'price',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'meta' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page',
                ]
            ]);
    }

    public function test_products_are_returned_in_correct_order(): void
    {
        $older = Product::factory()->create([
            'created_at' => now()->subDay(),
        ]);

        $newer = Product::factory()->create([
            'created_at' => now(),
        ]);

        $response = $this->getJson('/api/v1/products');

        $response->assertOk();

        $this->assertEquals(
            $newer->id,
            $response->json('data.0.id')
        );
    }

    public function test_can_update_product(): void
    {
        $product = Product::factory()->create();
        
        $data = [
            'name' => 'Updated Product',
            'description' => 'Updated Description',
            'price' => 199.99,
        ];

        $response = $this->putJson("/api/v1/products/{$product->id}", $data);

        $response
            ->assertOk()
            ->assertJson([
                'data' => [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'price' => $data['price'],
                ]
            ]);

        $this->assertDatabaseHas('products', $data);
    }

    public function test_cannot_update_nonexistent_product(): void
    {
        $response = $this->putJson('/api/v1/products/999999', [
            'name' => 'Updated Product',
        ]);

        $response->assertNotFound();
    }

    public function test_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/v1/products/{$product->id}");

        $response->assertNoContent();
        $this->assertSoftDeleted($product);
    }

    public function test_cannot_delete_nonexistent_product(): void
    {
        $response = $this->deleteJson('/api/v1/products/999999');

        $response->assertNotFound();
    }

    public function test_can_create_product_with_categories(): void
    {
        $categories = Category::factory()->count(2)->create();
        
        $data = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'is_top' => true,
            'category_ids' => $categories->pluck('id')->toArray(),
        ];

        $response = $this->postJson('/api/v1/products', $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => $data['name'],
                    'is_top' => true,
                    'categories' => $categories->map(fn($cat) => [
                        'id' => $cat->id,
                        'name' => $cat->name,
                    ])->toArray(),
                ]
            ]);
    }

    public function test_can_toggle_top_flag(): void
    {
        $product = Product::factory()->create(['is_top' => false]);

        $response = $this->patchJson("/api/v1/products/{$product->id}/toggle-top");

        $response
            ->assertOk()
            ->assertJson([
                'data' => [
                    'is_top' => true,
                ]
            ]);
    }

    public function test_can_sort_products(): void
    {
        Product::factory()->create(['name' => 'Z Product']);
        Product::factory()->create(['name' => 'A Product']);

        $response = $this->getJson('/api/v1/products?sort_by=name&direction=asc');

        $response->assertOk();
        $this->assertEquals(
            'A Product',
            $response->json('data.0.name')
        );
    }

    public function test_top_products_appear_first(): void
    {
        $regular = Product::factory()->create(['is_top' => false]);
        $top = Product::factory()->create(['is_top' => true]);

        $response = $this->getJson('/api/v1/products');

        $response->assertOk();
        $this->assertEquals(
            $top->id,
            $response->json('data.0.id')
        );
    }
}
