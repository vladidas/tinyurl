<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Product\Services;

use App\Domain\Product\DTOs\ProductDTO;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Repositories\ProductRepository;
use App\Domain\Product\Services\ProductService;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    private ProductRepository $repository;
    private ProductService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(ProductRepository::class);
        $this->service = new ProductService($this->repository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_can_create_product(): void
    {
        $dto = new ProductDTO(
            name: 'Test Product',
            description: 'Test Description',
            price: 99.99
        );

        $product = new Product();
        $product->fill($dto->toArray());

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->with($dto)
            ->andReturn($product);

        $result = $this->service->createProduct($dto);

        $this->assertInstanceOf(Product::class, $result);
    }

    public function test_can_get_paginated_products(): void
    {
        $paginator = Mockery::mock(LengthAwarePaginator::class);

        $this->repository
            ->shouldReceive('paginate')
            ->once()
            ->with(15)
            ->andReturn($paginator);

        $result = $this->service->getProducts();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }
} 