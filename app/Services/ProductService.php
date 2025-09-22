<?php

namespace App\Services;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Jobs\GenerateThumbnail;
use Illuminate\Support\Facades\Log;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts()
    {
        return $this->productRepository->allPaginated();
    }

    public function getProductById(int $id)
    {
        return $this->productRepository->find($id);
    }

    public function createProduct(array $data)
    {
        $product = $this->productRepository->create($data);
        Log::info('Dispatching GenerateThumbnail after create', ['product_id' => $product->id]);
        GenerateThumbnail::dispatch($product->id);
        return $product;
    }

    public function updateProduct(array $data, int $id)
    {
        $product = $this->productRepository->update($data, $id);
        Log::info('Dispatching GenerateThumbnail after update', ['product_id' => $product->id]);
        GenerateThumbnail::dispatch($product->id);
        return $product;
    }

    public function deleteProduct(int $id)
    {
        return $this->productRepository->delete($id);
    }
}