<?php

declare(strict_types=1);

namespace App\Domain\Product\Repositories;

use App\Domain\Category\Models\Category;
use App\Domain\Product\Models\Product;
use App\Domain\Product\DTOs\ProductDTO;
use App\Domain\Product\QueryBuilders\ProductQueryBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository
{
    private const CACHE_TTL = 3600; // 1 hour in seconds

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

        $this->clearProductCache($product->getId());
        return $product->load(Product::CATEGORIES);
    }

    public function paginate(
        int $page,
        int $perPage,
        string $sortBy,
        string $direction,
        ?string $search = null
    ): LengthAwarePaginator {
        return $this->getBaseQuery($search)
            ->orderBy($sortBy, $direction)
            ->paginate(perPage: $perPage, page: $page);
    }

    public function paginateByIds(array $ids, int $perPage, int $page): LengthAwarePaginator
    {
        if (empty($ids)) {
            return $this->paginateEmpty($perPage, $page);
        }

        return Product::query()
            ->whereIn(Product::ID, $ids)
            ->orderByRaw('FIELD(id, ' . implode(',', $ids) . ')')
            ->paginate(perPage: $perPage, page: $page);
    }

    public function paginateEmpty(int $perPage, int $page): LengthAwarePaginator
    {
        return Product::query()
            ->whereRaw('1 = 0')
            ->paginate(perPage: $perPage, page: $page);
    }

    private function getBaseQuery(?string $search = null): Builder
    {
        $query = Product::query();

        if ($search) {
            $query->where(function (Builder $query) use ($search) {
                $query->where(Product::NAME, 'like', "%{$search}%")
                    ->orWhere(Product::DESCRIPTION, 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function findById(int $id): ?Product
    {
        $key = "product:{$id}";

        $cached = Redis::get($key);
        if ($cached) {
            return unserialize($cached);
        }

        $product = $this->model->with(Product::CATEGORIES)->find($id);
        if ($product) {
            Redis::setex($key, self::CACHE_TTL, serialize($product));
        }

        return $product;
    }

    public function update(Product $product, ProductDTO $productDTO): Product
    {
        $product->update($productDTO->toArray());

        if (isset($productDTO->categoryIds)) {
            $product->categories()->sync($productDTO->categoryIds);
        }

        $this->clearProductCache($product->getId());
        return $product->refresh()->load(Product::CATEGORIES);
    }

    public function delete(Product $product): void
    {
        $this->clearProductCache($product->getId());
        $product->delete();
    }

    public function bulkInsert(array $products, int $chunkSize = 1000): void
    {
        collect($products)->chunk($chunkSize)->each(function ($chunk) {
            $this->model->insert($chunk->toArray());
        });
    }

    public function updateRating(Product $product, int $rating): Product
    {
        $rating = max(0, min(100, $rating));
        $product->update([
            Product::RATING => $rating
        ]);

        return $product->refresh();
    }

    public function findByIds(array $ids): Collection
    {
        return $this->model
            ->whereIn(Product::ID, $ids)
            ->orderByRaw(sprintf('FIELD(%s, %s)', Product::ID, implode(',', $ids)))
            ->get();
    }

    public function findRelatedProducts(Product $product, int $limit = 5): Collection
    {
        return $this->model
            ->whereHas(Product::CATEGORIES, function ($query) use ($product) {
                $query->whereIn(
                    sprintf('%s.%s', Product::CATEGORIES, Category::ID),
                    $product->categories->pluck(Category::ID)
                );
            })
            ->where(Product::ID, '!=', $product->getId())
            ->orderBy(Product::RATING, 'desc')
            ->limit($limit)
            ->get([
                Product::ID,
                Product::NAME,
                Product::PRICE,
                Product::RATING
            ]);
    }

    private function clearProductCache(int $productId): void
    {
        Redis::del([
            "product:{$productId}",
            "product:data:{$productId}",
            "product:related:{$productId}"
        ]);

        // Clear list cache using pattern
        $keys = Redis::keys('products:list:*');
        if (!empty($keys)) {
            Redis::del($keys);
        }
    }
}
