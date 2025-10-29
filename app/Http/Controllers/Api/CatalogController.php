<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ProductData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class CatalogController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    /**
     * Display a listing of brands.
     */
    public function brands(): JsonResponse
    {
        $brands = Brand::withCount('products')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $brands,
        ], 200);
    }

    /**
     * Display a paginated listing of products.
     */
    public function products(): JsonResponse
    {
        $products = Product::with(['brand', 'category'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ],
            'links' => [
                'first' => $products->url(1),
                'last' => $products->url($products->lastPage()),
                'prev' => $products->previousPageUrl(),
                'next' => $products->nextPageUrl(),
            ],
        ], 200);
    }

    /**
     * Display the specified product.
     */
    public function show(string $id): JsonResponse
    {
        $product = Product::with(['brand', 'category'])
            ->where('is_active', true)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $product,
        ], 200);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $productData = ProductData::fromArray($request->validated());
        $product = $this->productService->createProduct($productData, $request->file('image'));

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, string $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $productData = ProductData::fromArray($request->validated());
        $updatedProduct = $this->productService->updateProduct($product, $productData, $request->file('image'));

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $updatedProduct,
        ], 200);
    }

    /**
     * Remove the specified product from storage (Soft Delete).
     */
    public function destroy(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $this->productService->deleteProduct($product);

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
        ], 204);
    }
}