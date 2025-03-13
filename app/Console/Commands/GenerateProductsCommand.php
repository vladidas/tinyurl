<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Product\Models\Product;
use Illuminate\Console\Command;

class GenerateProductsCommand extends Command
{
    protected $signature = 'products:generate {count=1000000}';
    protected $description = 'Generate large number of test products';

    public function handle(): int
    {
        $count = (int) $this->argument('count');
        $chunk = 1000;
        $bar = $this->output->createProgressBar($count);

        $this->info("Generating {$count} products...");

        for ($i = 0; $i < $count; $i += $chunk) {
            Product::factory()
                ->count(min($chunk, $count - $i))
                ->create();

            $bar->advance($chunk);
        }

        $bar->finish();
        $this->newLine();
        $this->info('Products generated successfully!');

        return Command::SUCCESS;
    }
} 