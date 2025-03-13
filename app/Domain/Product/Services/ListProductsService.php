<?php

declare(strict_types=1);

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Repositories\ProductRepository;
use Illuminate\Pagination\LengthAwarePaginator;

final class ListProductsService
{
    public function __construct(
        private readonly ProductRepository $repository
    ) {}

    public function execute(
        int $perPage = 15,
        string $sortBy = Product::CREATED_AT,
        string $direction = 'desc',
        ?int $categoryId = null
    ): LengthAwarePaginator {
        return $this->repository->paginate($perPage, $sortBy, $direction, $categoryId);
    }
} 