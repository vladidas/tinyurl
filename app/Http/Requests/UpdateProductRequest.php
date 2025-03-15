<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Domain\Product\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            Product::NAME => ['sometimes', 'required', 'string', 'max:255'],
            Product::DESCRIPTION => ['sometimes', 'nullable', 'string'],
            Product::PRICE => ['sometimes', 'required', 'numeric', 'min:0', 'max:999999.99'],
            Product::RATING => ['sometimes', 'integer', 'min:0', 'max:100'],
            Product::CATEGORY_IDS => ['sometimes', 'array'],
            Product::CATEGORY_IDS . '.*' => ['exists:categories,id'],
        ];
    }
}
