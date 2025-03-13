<?php

declare(strict_types=1);

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Repositories\ProductRepository;

final class DeleteProductService
{
    public function __construct(
        private readonly ProductRepository $repository
    ) {}

    public function execute(Product $product): void
    {
        $this->repository->delete($product);
    }
} 