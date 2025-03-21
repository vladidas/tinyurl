<?php

declare(strict_types=1);

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Repositories\ProductRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class ListProductsService
{
    public function __construct(
        private ProductRepository $repository,
        private ProductSearchService $searchService
    ) {}

    public function execute(
        int $page = 1,
        int $perPage = 15,
        string $sortBy = Product::CREATED_AT,
        string $direction = 'desc',
        ?string $search = null
    ): LengthAwarePaginator {
        if ($search) {
            return $this->searchService->searchAndPaginate($search, $page, $perPage);
        }

        return $this->repository->paginate($page, $perPage, $sortBy, $direction);
    }
}
