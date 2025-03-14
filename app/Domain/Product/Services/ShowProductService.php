<?php

declare(strict_types=1);

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Repositories\ProductRepository;
use Illuminate\Support\Facades\Redis;

final class ShowProductService
{
    private const CACHE_TTL = 604800; // 7 days in seconds

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
        $key = "product:data:{$product->getId()}";

        $cached = Redis::get($key);
        if ($cached) {
            return json_decode($cached, true);
        }

        $data = $product->load(Product::CATEGORIES)->toArray();
        Redis::setex($key, self::CACHE_TTL, json_encode($data));

        return $data;
    }

    private function getRelatedProducts(Product $product): array
    {
        $key = "product:related:{$product->getId()}";

        $cached = Redis::get($key);
        if ($cached) {
            return json_decode($cached, true);
        }

        $data = $this->repository->findRelatedProducts($product)->toArray();
        Redis::setex($key, self::CACHE_TTL, json_encode($data));

        return $data;
    }

    private function getRecentlyViewed(int $userId): array
    {
        return $this->historyService->getHistory($userId);
    }
}
