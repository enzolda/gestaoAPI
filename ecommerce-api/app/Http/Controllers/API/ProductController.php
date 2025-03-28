<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
        ]);

        try {
            $product = $this->productService->createProduct($validated);
            return response()->json($product, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao criar produto'], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $products = $this->productService->getProducts($request->all());
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar produtos'], 500);
        }
    }

    public function show(int $id)
    {
        $product = $this->productService->getProductById($id);

        return $product
            ? response()->json($product)
            : response()->json(['message' => 'Produto não encontrado'], 404);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'category' => 'sometimes|string|max:255',
        ]);

        $product = $this->productService->updateProduct($id, $validated);

        return $product
            ? response()->json($product)
            : response()->json(['message' => 'Produto não encontrado'], 404);
    }

    public function destroy(int $id){
        $success = $this->productService->deleteProduct($id);

        return $success
            ? response()->json(null, 204)
            : response()->json(['message' => 'Produto não encontrado'], 404);
    }
}
