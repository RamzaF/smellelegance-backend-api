<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CatalogController extends Controller
{
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
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:999999.99',
            'stock_available' => 'required|integer|min:0',
            'image' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);

        // Create product
        $product = Product::create($validated);

        // Load relationships
        $product->load(['brand', 'category']);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'brand_id' => 'sometimes|required|exists:brands,id',
            'category_id' => 'sometimes|required|exists:categories,id',
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($product->id),
            ],
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0|max:999999.99',
            'stock_available' => 'sometimes|required|integer|min:0',
            'image' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Update slug if name changed
        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Update product
        $product->update($validated);

        // Reload relationships
        $product->load(['brand', 'category']);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product,
        ], 200);
    }

    /**
     * Remove the specified product from storage (Soft Delete).
     */
    public function destroy(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        // Soft delete
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
        ], 204);
    }
}