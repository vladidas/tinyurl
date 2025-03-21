<?php

declare(strict_types=1);

namespace App\Domain\Product\Services;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Repositories\ProductRepository;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

final readonly class ProductSearchService
{
    public function __construct(
        private Client $elasticsearch,
        private ProductRepository $repository
    ) {}

    public function searchAndPaginate(
        string $query,
        int $page = 1,
        int $perPage = 15
    ): LengthAwarePaginator {
        try {
            $searchResults = $this->performSearch($query);

            if ($searchResults->isEmpty()) {
                return $this->repository->paginateEmpty($perPage, $page);
            }

            return $this->repository->paginateByIds(
                $searchResults->pluck('_source.id')->toArray(),
                $perPage,
                $page
            );
        } catch (ClientResponseException $e) {
            Log::error('Elasticsearch search failed', [
                'query' => $query,
                'error' => $e->getMessage()
            ]);

            // Fallback to database search
            return $this->repository->paginate($page, $perPage, Product::NAME, 'asc', $query);
        }
    }

    /**
     * @throws ClientResponseException
     */
    private function performSearch(string $query): Collection
    {
        $response = $this->elasticsearch->search([
            'index' => 'products',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['name^3', 'description'],
                        'fuzziness' => 'AUTO'
                    ]
                ]
            ]
        ]);

        return collect($response['hits']['hits']);
    }
}
