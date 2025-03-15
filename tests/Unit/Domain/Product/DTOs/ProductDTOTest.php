<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Product\DTOs;

use App\Domain\Product\DTOs\ProductDTO;
use PHPUnit\Framework\TestCase;

class ProductDTOTest extends TestCase
{
    public function test_can_create_from_array(): void
    {
        $data = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => '99.99',
        ];

        $dto = ProductDTO::fromArray($data);

        $this->assertEquals($data['name'], $dto->name);
        $this->assertEquals($data['description'], $dto->description);
        $this->assertEquals((float) $data['price'], $dto->price);
    }

    public function test_can_convert_to_array(): void
    {
        $dto = new ProductDTO(
            name: 'Test Product',
            description: 'Test Description',
            price: 99.99
        );

        $array = $dto->toArray();

        $this->assertEquals([
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'rating' => 0,
        ], $array);
    }

    public function test_description_can_be_null(): void
    {
        $dto = new ProductDTO(
            name: 'Test Product',
            description: null,
            price: 99.99
        );

        $this->assertNull($dto->description);
    }
}
