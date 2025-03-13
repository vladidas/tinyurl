<?php

namespace App\Domain\Product\Services;

use App\Domain\Product\DTOs\ProductDTO;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Repositories\ProductRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $repository
    ) {}

    public function createProduct(ProductDTO $productDTO): Product
    {
        return $this->repository->create($productDTO);
    }

    public function getProducts(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function updateProduct(int $id, ProductDTO $productDTO): Product
    {
        $product = $this->repository->findById($id);

        if (!$product) {
            throw new NotFoundHttpException('Product not found');
        }

        return $this->repository->update($product, $productDTO);
    }

    public function deleteProduct(int $id): void
    {
        $product = $this->repository->findById($id);

        if (!$product) {
            throw new NotFoundHttpException('Product not found');
        }

        $this->repository->delete($product);
    }
} 