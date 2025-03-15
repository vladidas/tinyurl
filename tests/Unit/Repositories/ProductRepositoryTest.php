<?php

namespace Tests\Unit\Repositories;

use App\Domain\Product\DTOs\ProductDTO;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Repositories\ProductRepository;
use App\Domain\Product\QueryBuilders\ProductQueryBuilder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ProductRepository $repository;
    private Product $model;
    private ProductQueryBuilder $queryBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = new Product();
        $this->queryBuilder = new ProductQueryBuilder();
        $this->repository = new ProductRepository($this->model, $this->queryBuilder);
    }

    public function test_can_get_all_products()
    {
        Product::factory()->count(3)->create();

        $products = $this->repository->paginate(cacheResults: false);

        $this->assertEquals(3, $products->total());
        $this->assertInstanceOf(Product::class, $products->first());
    }

    public function test_can_find_product_by_id()
    {
        $product = Product::factory()->create();

        $found = $this->repository->findById($product->{Product::ID});

        $this->assertInstanceOf(Product::class, $found);
        $this->assertEquals($product->{Product::ID}, $found->{Product::ID});
    }

    public function test_can_create_product()
    {
        $productDTO = new ProductDTO(
            name: 'New Product',
            description: 'New product description',
            price: 149.99,
            rating: 5,
            categoryIds: []
        );

        $product = $this->repository->create($productDTO);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertDatabaseHas('products', [
            Product::NAME => 'New Product',
            Product::PRICE => 149.99,
            Product::DESCRIPTION => 'New product description',
            Product::RATING => 5
        ]);
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create();
        $productDTO = new ProductDTO(
            name: 'Updated Name',
            description: $product->description,
            price: $product->price,
            rating: $product->rating
        );

        $updated = $this->repository->update($product, $productDTO);

        $this->assertInstanceOf(Product::class, $updated);
        $this->assertEquals('Updated Name', $updated->{Product::NAME});
    }

    public function test_can_delete_product()
    {
        $product = Product::factory()->create();

        $this->repository->delete($product);

        $this->assertSoftDeleted('products', [
            Product::ID => $product->{Product::ID}
        ]);
    }
}
