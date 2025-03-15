<?php

declare(strict_types=1);

namespace App\Domain\Product\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

/**
 * @property Collection collection
 * @method int total()
 * @method int perPage()
 * @method int currentPage()
 * @method int lastPage()
 */
class ProductCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
            ],
        ];
    }
}
