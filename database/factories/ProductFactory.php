<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            Product::NAME => $this->faker->words(3, true),
            Product::DESCRIPTION => $this->faker->paragraph(),
            Product::PRICE => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}
