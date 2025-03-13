<?php

declare(strict_types=1);

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductPreviewService
{
    public function __construct(
        private readonly ProductViewHistoryService $historyService
    ) {}

    public function getPreview(Product $product, int $userId): array
    {
        // Add to user's view history
        $this->historyService->addToHistory($userId, $product);

        // Get cached preview data
        $cacheKey = "product:preview:{$product->id}";
        
        return Cache::remember($cacheKey, 3600, function () use ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'rating' => $product->rating,
                'categories' => $product->categories->map(fn($cat) => [
                    'id' => $cat->id,
                    'name' => $cat->name,
                ])->toArray(),
                'related_products' => $this->getRelatedProducts($product),
            ];
        });
    }

    private function getRelatedProducts(Product $product): array
    {
        $cacheKey = "product:related:{$product->id}";

        return Cache::remember($cacheKey, 3600, function () use ($product) {
            return Product::query()
                ->whereHas('categories', function ($query) use ($product) {
                    $query->whereIn('categories.id', $product->categories->pluck('id'));
                })
                ->where('id', '!=', $product->id)
                ->orderBy('rating', 'desc')
                ->limit(5)
                ->get(['id', 'name', 'price', 'rating'])
                ->toArray();
        });
    }
} 