<?php

declare(strict_types=1);

namespace App\Domain\Product\Services;

use App\Domain\Product\DTOs\ProductDTO;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Repositories\ProductRepository;

final readonly class UpdateProductService
{
    public function __construct(
        private readonly ProductRepository $repository
    ) {}

    public function execute(Product $product, ProductDTO $productDTO): Product
    {
        return $this->repository->update($product, $productDTO);
    }
}
