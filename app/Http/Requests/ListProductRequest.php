<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Domain\Product\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListProductRequest extends FormRequest
{
    private const ALLOWED_SORT_DIRECTIONS = ['asc', 'desc'];
    private const DEFAULT_PER_PAGE = 15;
    private const MAX_PER_PAGE = 100;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:' . self::MAX_PER_PAGE],
            'sort_by' => [
                'sometimes',
                'string',
                Rule::in([
                    Product::NAME,
                    Product::PRICE,
                    Product::RATING,
                    Product::CREATED_AT,
                    Product::UPDATED_AT
                ])
            ],
            'direction' => ['sometimes', 'string', Rule::in(self::ALLOWED_SORT_DIRECTIONS)],
            'search' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }

    public function validated($key = null, $default = null): mixed
    {
        $validated = parent::validated();

        $data = [
            'page' => (int) ($validated['page'] ?? 1),
            'per_page' => (int) ($validated['per_page'] ?? self::DEFAULT_PER_PAGE),
            'sort_by' => $validated['sort_by'] ?? Product::CREATED_AT,
            'direction' => $validated['direction'] ?? 'desc',
            'search' => $validated['search'] ?? null,
        ];

        return $key
            ? $data[$key]
            : $data;
    }
}
