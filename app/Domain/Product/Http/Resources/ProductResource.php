<?php

declare(strict_types=1);

namespace App\Domain\Product\Http\Resources;

use App\Domain\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int id
 * @property string name
 * @property string description
 * @property float price
 * @property string created_at
 * @property string updated_at
 */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Product $this */
        return [
            Product::ID => $this[Product::ID],
            Product::NAME => $this[Product::NAME],
            Product::DESCRIPTION => $this[Product::DESCRIPTION],
            Product::PRICE => $this[Product::PRICE],
            Product::CREATED_AT => $this[Product::CREATED_AT],
            Product::UPDATED_AT => $this[Product::UPDATED_AT],
        ];
    }
}
