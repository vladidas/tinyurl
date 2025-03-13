<?php

declare(strict_types=1);

namespace App\Domain\Product\DTOs;

use App\Domain\Product\Models\Product;

/**
 * @property-read string $name
 * @property-read string|null $description
 * @property-read float $price
 * @property-read int $rating
 * @property-read array<int> $categoryIds
 */
class ProductDTO
{
    /**
     * @param array<int> $categoryIds
     */
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly float $price,
        public readonly int $rating = 0,
        public readonly array $categoryIds = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data[Product::NAME],
            description: $data[Product::DESCRIPTION] ?? null,
            price: (float) $data[Product::PRICE],
            rating: (int) ($data[Product::RATING] ?? 0),
            categoryIds: $data[Product::CATEGORY_IDS] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            Product::NAME => $this->name,
            Product::DESCRIPTION => $this->description,
            Product::PRICE => $this->price,
            Product::RATING => $this->rating,
        ];
    }
}
