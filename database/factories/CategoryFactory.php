<?php

namespace Database\Factories;

use App\Domain\Category\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        $name = $this->faker->unique()->words(2, true);
        return [
            Category::NAME => ucwords($name),
            Category::DESCRIPTION => $this->faker->sentence(),
            Category::CREATED_AT => now(),
            Category::UPDATED_AT => now(),
        ];
    }
} 