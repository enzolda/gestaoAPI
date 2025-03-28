<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function getProducts(array $filters): LengthAwarePaginator
    {
        return Product::when(isset($filters['name']), function ($query) use ($filters) {
            $query->where('name', 'like', "%{$filters['name']}%");
        })
            ->when(isset($filters['category']), function ($query) use ($filters) {
                $query->where('category', $filters['category']);
            })
            ->when(isset($filters['min_price']), function ($query) use ($filters) {
                $query->where('price', '>=', $filters['min_price']);
            })
            ->when(isset($filters['max_price']), function ($query) use ($filters) {
                $query->where('price', '<=', $filters['max_price']);
            })
            ->paginate($filters['per_page'] ?? 10);
    }

    public function getProductById(int $id)
    {
        return Product::find($id);
        //return Product::where('id', $id)->first();
    }

    public function updateProduct(int $id, array $data): ?Product
    {
        $product = Product::find($id);
        if (!$product)
            return null;

        $product->update($data);
        return $product->fresh();
    }

    public function deleteProduct(int $id): bool
    {
        $product = Product::find($id);
        if (!$product)
            return false;

        return $product->delete();
    }
}