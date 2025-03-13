<?php

declare(strict_types=1);

namespace App\Domain\Product\Repositories;

use App\Domain\Product\Models\Product;
use App\Domain\Product\DTOs\ProductDTO;
use App\Domain\Product\QueryBuilders\ProductQueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ProductRepository
{
    public function __construct(
        private readonly Product $model,
        private readonly ProductQueryBuilder $queryBuilder
    ) {}

    public function create(ProductDTO $productDTO): Product
    {
        $product = $this->model->create($productDTO->toArray());
        
        if (!empty($productDTO->categoryIds)) {
            $product->categories()->sync($productDTO->categoryIds);
        }

        return $product->load('categories');
    }

    public function paginate(
        int $perPage = 15,
        string $sortBy = 'created_at',
        string $direction = 'desc',
        ?int $categoryId = null
    ): LengthAwarePaginator {
        $cacheKey = "products:list:{$sortBy}:{$direction}:{$categoryId}:{$perPage}";

        return Cache::remember($cacheKey, 60, function () use ($perPage, $sortBy, $direction, $categoryId) {
            return $this->queryBuilder
                ->onlyActive()
                ->withCategories()
                ->withCategory($categoryId)
                ->sortBy($sortBy, $direction)
                ->paginate($perPage);
        });
    }

    public function findById(int $id): ?Product
    {
        return $this->model->with('categories')->find($id);
    }

    public function update(Product $product, ProductDTO $productDTO): Product
    {
        $product->update($productDTO->toArray());
        
        if (isset($productDTO->categoryIds)) {
            $product->categories()->sync($productDTO->categoryIds);
        }

        return $product->refresh()->load('categories');
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function toggleTop(Product $product): Product
    {
        $product->update(['is_top' => !$product->is_top]);
        return $product->refresh();
    }

    public function bulkInsert(array $products, int $chunkSize = 1000): void
    {
        collect($products)->chunk($chunkSize)->each(function ($chunk) {
            $this->model->insert($chunk->toArray());
        });
    }

    public function updateRating(Product $product, int $rating): Product
    {
        $product->update(['rating' => max(0, min(100, $rating))]);
        return $product->refresh();
    }
}
