<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(): JsonResponse
    {
        $products = $this->productService->getAllProducts();
        return response()->json(ProductResource::collection($products));
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct($request->validated());
        return response()->json(new ProductResource($product), 201);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);
        return response()->json(new ProductResource($product));
    }

    public function update(ProductRequest $request, int $id): JsonResponse
    {
        $product = $this->productService->updateProduct($request->validated(), $id);
        return response()->json(new ProductResource($product));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->productService->deleteProduct($id);
        return response()->json(null, 204);
    }
}