<?php

declare(strict_types=1);

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Repositories\ProductRepository;
use Illuminate\Support\Facades\Cache;

final class ShowProductService
{
    private const CACHE_TTL = 3600; // 1 hour

    public function __construct(
        private readonly ProductRepository $repository,
        private readonly ProductViewHistoryService $historyService
    ) {}

    public function execute(Product $product, int $userId): array
    {
        $this->historyService->addToHistory($userId, $product);

        return [
            'product' => $this->getProductData($product),
            'related_products' => $this->getRelatedProducts($product),
            'recently_viewed' => $this->getRecentlyViewed($userId),
        ];
    }

    private function getProductData(Product $product): array
    {
        $cacheKey = "product:data:{$product->getId()}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($product) {
            return $product->load('categories')->toArray();
        });
    }

    private function getRelatedProducts(Product $product): array
    {
        $cacheKey = "product:related:{$product->getId()}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($product) {
            return $this->repository->findRelatedProducts($product)->toArray();
        });
    }

    private function getRecentlyViewed(int $userId): array
    {
        return $this->historyService->getHistory($userId);
    }
} 