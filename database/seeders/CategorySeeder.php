<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Category\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::factory()
            ->count(100)
            ->create();
    }
}
