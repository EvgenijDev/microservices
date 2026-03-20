<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\IndexProductRequest;
use App\Http\Resources\ProductResource;
use App\Jobs\ImportProductsJob;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Events\ProductChanged;
class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(IndexProductRequest $request): JsonResponse
    {
        $products = $this->productService->getAllProducts();
        return ProductResource::collection($products)->response();
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $payload = $request->validated();
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $payload['image_path'] = $path;
        }
        $product = $this->productService->createProduct($payload);
        broadcast(new ProductChanged('created', (new ProductResource($product))->resolve()))->toOthers();
        return response()->json(new ProductResource($product), 201);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);
        return response()->json(new ProductResource($product));
    }

    public function update(ProductRequest $request, int $id): JsonResponse
    {
        $payload = $request->validated();
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $payload['image_path'] = $path;
        }
        $product = $this->productService->updateProduct($payload, $id);
        broadcast(new ProductChanged('updated', (new ProductResource($product))->resolve()))->toOthers();
        return response()->json(new ProductResource($product));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->productService->deleteProduct($id);
        broadcast(new ProductChanged('deleted', null, $id))->toOthers();
        return response()->json(null, 204);
    }

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $path = $request->file('file')->store('imports', 'local');
        $import_id = Str::uuid();
        ImportProductsJob::dispatch($path, $import_id);

        return response()->json([
            'message' => 'Импорт товаров поставлен в очередь.',
            'import_id' => $import_id,
        ], 202);
    }

    public function getImportErrors(string $import_id): JsonResponse
    {
        $errors = Cache::get('import_errors:' . $import_id);
        return response()->json($errors);
    }
}