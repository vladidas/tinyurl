<?php

declare(strict_types=1);

namespace App\Domain\Product\Observers;

use App\Domain\Product\Models\Product;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    private Client $elasticsearch;

    /**
     * @throws AuthenticationException
     */
    public function __construct()
    {
        $this->elasticsearch = ClientBuilder::create()
            ->setHosts([config('elasticsearch.hosts')])
            ->setBasicAuthentication(
                config('elasticsearch.username'),
                config('elasticsearch.password')
            )
            ->build();
    }

    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->indexProduct($product);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->indexProduct($product);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        try {
            $this->elasticsearch->delete([
                'index' => 'products',
                'id' => $product->getId()
            ]);
        } catch (\Exception $e) {
            Log::error('Elasticsearch delete failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        $this->indexProduct($product);
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        $this->deleted($product);
    }

    /**
     * Index the product in Elasticsearch
     */
    private function indexProduct(Product $product): void
    {
        try {
            $this->elasticsearch->index([
                'index' => 'products',
                'id' => $product->getId(),
                'body' => [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'description' => $product->getDescription(),
                    'price' => $product->getPrice(),
                    'rating' => $product->getRating(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Elasticsearch indexing failed: ' . $e->getMessage());
        }
    }
}
