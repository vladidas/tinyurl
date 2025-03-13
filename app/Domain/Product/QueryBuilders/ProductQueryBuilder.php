<?php

declare(strict_types=1);

namespace App\Domain\Product\QueryBuilders;

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
            $this->query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }
        return $this;
    }

    public function sortBy(string $field, string $direction = 'asc'): self
    {
        $validFields = ['name', 'price', 'rating', 'created_at'];
        $field = in_array($field, $validFields) ? $field : 'created_at';
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'asc';

        if ($field === 'category') {
            $this->query->orderBy(function ($query) use ($direction) {
                $query->select('categories.name')
                    ->from('categories')
                    ->join('category_product', 'categories.id', '=', 'category_product.category_id')
                    ->whereColumn('category_product.product_id', 'products.id')
                    ->orderBy('categories.name', $direction)
                    ->limit(1);
            });
        } else {
            $this->query->orderBy($field, $direction);
        }

        return $this;
    }

    public function onlyActive(): self
    {
        $this->query->whereNull('deleted_at');
        return $this;
    }

    public function withCategories(): self
    {
        $this->query->with('categories:id,name');
        return $this;
    }

    public function paginate(int $perPage = 15): mixed
    {
        return $this->query->paginate($perPage);
    }

    public function get(): mixed
    {
        return $this->query->get();
    }
} 