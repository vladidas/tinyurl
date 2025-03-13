<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Product\DTOs\ProductDTO;
use App\Domain\Product\Services\ProductService;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Domain\Product\Http\Resources\ProductResource;
use App\Domain\Product\Http\Resources\ProductCollection;
use Illuminate\Http\Response;
use App\Domain\Product\Services\ProductPreviewService;
use App\Domain\Product\Services\ProductViewHistoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly ProductPreviewService $previewService,
        private readonly ProductViewHistoryService $historyService
    ) {}

    public function store(CreateProductRequest $request): ProductResource
    {
        $product = $this->productService->createProduct(
            ProductDTO::fromArray($request->validated())
        );

        return new ProductResource($product);
    }

    public function index(): ProductCollection
    {
        $products = $this->productService->getProducts();

        return new ProductCollection($products);
    }

    public function update(UpdateProductRequest $request, int $id): ProductResource
    {
        $product = $this->productService->updateProduct(
            $id,
            ProductDTO::fromArray($request->validated())
        );

        return new ProductResource($product);
    }

    public function destroy(int $id): Response
    {
        $this->productService->deleteProduct($id);

        return response()->noContent();
    }

    public function toggleTop(int $id): ProductResource
    {
        $product = $this->productService->toggleTop($id);
        return new ProductResource($product);
    }

    public function show(int $id, Request $request): JsonResponse
    {
        $product = $this->productService->findById($id);
        
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $preview = $this->previewService->getPreview($product, $request->user()->id);
        
        return response()->json([
            'data' => $preview,
            'recently_viewed' => $this->historyService->getHistory($request->user()->id),
        ]);
    }
}
