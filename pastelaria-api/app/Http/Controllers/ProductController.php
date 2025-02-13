<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(): JsonResponse
    {
        return response()->json($this->productService->getAllProducts(), 200);
    }

    public function show($id): JsonResponse
    {
        return response()->json($this->productService->getProductById($id), 200);
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct($request->validated());
        return response()->json($product, 201);
    }

    public function update(ProductRequest $request, $id): JsonResponse
    {
        $product = $this->productService->updateProduct($id, $request->validated());
        return response()->json($product, 200);
    }

    public function destroy($id): JsonResponse
    {
        $this->productService->deleteProduct($id);
        return response()->json(null, 204);
    }
}
