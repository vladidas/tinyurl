<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Category\Models\Category;
use App\Domain\Product\Models\Product;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        Product::factory(50)->create()->each(function ($product) {
            $product->categories()->attach(
                Category::all()->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
