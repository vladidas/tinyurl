<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Product\Models\Product;
use Illuminate\Console\Command;

class GenerateProductsCommand extends Command
{
    private const CHUNK = 1000;

    protected $signature = 'products:generate {count=1000000}';
    protected $description = 'Generate large number of test products';
    private const PROCESSING_MESSAGE = 'Generating %s products...';
    private const SUCCESS_MESSAGE = 'Products generated successfully!';

    public function handle(): int
    {
        $count = (int) $this->argument('count');
        $bar = $this->output->createProgressBar($count);

        $this->info(sprintf(self::PROCESSING_MESSAGE, $count));

        for ($i = 0; $i < $count; $i += self::CHUNK) {
            Product::factory()
                ->count(min(self::CHUNK, $count - $i))
                ->create();

            $bar->advance(self::CHUNK);
        }

        $bar->finish();
        $this->newLine();
        $this->info(self::SUCCESS_MESSAGE);

        return Command::SUCCESS;
    }
}
