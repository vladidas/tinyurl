<?php

declare(strict_types=1);

namespace App\Domain\Product\Http\Resources;

use App\Domain\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property array product
 * @property array related_products
 * @property array recently_viewed
 */
class ShowProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'product' => $this['product'],
            'related_products' => $this['related_products'],
            'recently_viewed' => $this['recently_viewed']
        ];
    }
}
