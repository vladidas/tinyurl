<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Product\DTOs\ProductDTO;
use App\Domain\Product\Http\Resources\ShowProductResource;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Services\CreateProductService;
use App\Domain\Product\Services\ListProductsService;
use App\Domain\Product\Services\UpdateProductService;
use App\Domain\Product\Services\DeleteProductService;
use App\Domain\Product\Services\ShowProductService;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\ListProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Domain\Product\Http\Resources\ProductResource;
use App\Domain\Product\Http\Resources\ProductCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct(
        private readonly CreateProductService $createProduct,
        private readonly ListProductsService $listProducts,
        private readonly UpdateProductService $updateProduct,
        private readonly DeleteProductService $deleteProduct,
        private readonly ShowProductService $showProduct
    ) {}

    public function store(CreateProductRequest $request): ProductResource
    {
        $product = $this->createProduct->execute(
            ProductDTO::fromArray($request->validated())
        );

        return new ProductResource($product);
    }

    public function index(ListProductRequest $request): ProductCollection
    {
        $products = $this->listProducts->execute(
            page: $request->validated('page'),
            perPage: $request->validated('per_page'),
            sortBy: $request->validated('sort_by'),
            direction: $request->validated('direction'),
            search: $request->validated('search'),
        );

        return new ProductCollection($products);
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $product = $this->updateProduct->execute(
            $product,
            ProductDTO::fromArray($request->validated())
        );

        return new ProductResource($product);
    }

    public function destroy(Product $product): Response
    {
        $this->deleteProduct->execute($product);

        return response()->noContent();
    }

    public function show(Product $product, Request $request): ShowProductResource
    {
        $data = $this->showProduct->execute($product, $request->user()->id);

        return new ShowProductResource($data);
    }
}
