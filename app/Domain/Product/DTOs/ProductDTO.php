<?php

declare(strict_types=1);

namespace App\Domain\Product\DTOs;

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
            name: $data['name'],
            description: $data['description'] ?? null,
            price: (float) $data['price'],
            rating: (int) ($data['rating'] ?? 0),
            categoryIds: $data['category_ids'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'rating' => $this->rating,
        ];
    }
}
