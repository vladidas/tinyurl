<?php

declare(strict_types=1);

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\Product;
use Illuminate\Support\Facades\Redis;

final readonly class ProductViewHistoryService
{
    private const HISTORY_KEY = 'user:%d:product_history';
    private const MAX_HISTORY = 10;

    public function addToHistory(int $userId, Product $product): void
    {
        $key = sprintf(self::HISTORY_KEY, $userId);

        Redis::pipeline(function ($pipe) use ($key, $product) {
            $pipe->lpush($key, $product->getId());
            $pipe->ltrim($key, 0, self::MAX_HISTORY - 1);
            $pipe->expire($key, 60 * 60 * 24 * 7); // 7 days
        });
    }

    public function getHistory(int $userId): array
    {
        $key = sprintf(self::HISTORY_KEY, $userId);
        $ids = Redis::lrange($key, 0, -1);

        if (empty($ids)) {
            return [];
        }

        return Product::whereIn(Product::ID, $ids)
            ->orderByRaw('FIELD(id, ' . implode(',', $ids) . ')')
            ->get()
            ->toArray();
    }
}
