<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
    public function store(StoreProductRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image_url'] = $imagePath;
        }

        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);

        // Remove 'image' key if it exists (we use 'image_url')
        unset($validated['image']);

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
    public function update(UpdateProductRequest $request, string $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
                Storage::disk('public')->delete($product->image_url);
            }

            // Store new image
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image_url'] = $imagePath;
        }

        // Update slug if name changed
        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Remove 'image' key if it exists
        unset($validated['image']);

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

        // Optionally delete image when soft deleting
        // if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
        //     Storage::disk('public')->delete($product->image_url);
        // }

        // Soft delete
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
        ], 204);
    }
}