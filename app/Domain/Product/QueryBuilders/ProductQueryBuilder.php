<?php

declare(strict_types=1);

namespace App\Domain\Product\QueryBuilders;

use App\Domain\Category\Models\Category;
use App\Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilder
{
    private Builder $query;

    public function __construct()
    {
        $this->query = Product::query();
    }

    public function withCategory(?int $categoryId): self
    {
        if ($categoryId) {
            $this->query->whereHas(Product::CATEGORIES, function ($q) use ($categoryId) {
                $q->where(Product::CATEGORIES . '.' . Category::ID, $categoryId);
            });
        }
        return $this;
    }

    public function onlyActive(): self
    {
        $this->query->whereNull(Product::DELETED_AT);
        return $this;
    }

    public function search(?string $keyword): self
    {
        if (empty($keyword)) {
            return $this;
        }

        return $this->query->where(function (Builder $query) use ($keyword) {
            $searchTerm = '%' . strtolower($keyword) . '%';

            $query->where(Product::NAME, 'LIKE', $searchTerm)
                ->orWhere(Product::DESCRIPTION, 'LIKE', $searchTerm);
        });
    }

    public function withCategories(): self
    {
        $this->query->with(sprintf('%s:%s,%s', Product::CATEGORIES, Category::ID, Category::NAME));
        return $this;
    }

    public function paginate(int $perPage = 15): mixed
    {
        return $this->query->paginate($perPage);
    }

    public function sortBy(string $field, string $direction = 'asc'): self
    {
        $this->query->orderBy($field, $direction);

        return $this;
    }

    public function get(): mixed
    {
        return $this->query->get();
    }
}
