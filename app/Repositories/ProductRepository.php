<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository extends EloquentRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }


    public function allPaginated($perPage = 4)
    {
        return Product::query()->orderBy('id', 'desc')->paginate($perPage);
    }
}
