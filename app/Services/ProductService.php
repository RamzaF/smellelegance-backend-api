<?php

namespace App\Services;

use App\DTOs\ProductData;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * Create a new product
     */
    public function createProduct(ProductData $data, ?UploadedFile $image = null): Product
    {
        $productData = $data->toArray();

        // Handle image upload
        if ($image) {
            $productData['image_url'] = $this->storeImage($image);
        }

        // Generate slug
        $productData['slug'] = Str::slug($data->name);

        // Create product
        $product = Product::create($productData);

        // Load relationships
        $product->load(['brand', 'category']);

        return $product;
    }

    /**
     * Update an existing product
     */
    public function updateProduct(Product $product, ProductData $data, ?UploadedFile $image = null): Product
    {
        $productData = $data->toArray();

        // Handle image upload
        if ($image) {
            // Delete old image if exists
            if ($product->image_url) {
                $this->deleteImage($product->image_url);
            }

            $productData['image_url'] = $this->storeImage($image);
        }

        // Update slug if name changed
        if ($data->name !== $product->name) {
            $productData['slug'] = Str::slug($data->name);
        }

        // Update product
        $product->update($productData);

        // Reload relationships
        $product->load(['brand', 'category']);

        return $product;
    }

    /**
     * Soft delete a product
     */
    public function deleteProduct(Product $product): bool
    {
        // Optionally delete image (uncomment if needed)
        // if ($product->image_url) {
        //     $this->deleteImage($product->image_url);
        // }

        return $product->delete();
    }

    /**
     * Store image and return path
     */
    private function storeImage(UploadedFile $image): string
    {
        return $image->store('products', 'public');
    }

    /**
     * Delete image from storage
     */
    private function deleteImage(string $imagePath): bool
    {
        if (Storage::disk('public')->exists($imagePath)) {
            return Storage::disk('public')->delete($imagePath);
        }

        return false;
    }
}