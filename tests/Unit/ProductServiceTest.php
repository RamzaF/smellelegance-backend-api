<?php

namespace Tests\Unit;

use App\DTOs\ProductData;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductService $productService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = new ProductService();
        Storage::fake('public');
    }

    /** @test */
    public function it_creates_a_product_with_valid_data()
    {
        // Arrange
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        $productData = new ProductData(
            brand_id: $brand->id,
            category_id: $category->id,
            name: 'Test Perfume',
            description: 'A test perfume',
            price: 199.99,
            stock_available: 50,
            is_active: true
        );

        // Act
        $product = $this->productService->createProduct($productData, null);

        // Assert
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Test Perfume', $product->name);
        $this->assertEquals('test-perfume', $product->slug);
        $this->assertEquals(199.99, $product->price);
        $this->assertEquals(50, $product->stock_available);
        $this->assertTrue($product->is_active);

        // Verify in database
        $this->assertDatabaseHas('products', [
            'name' => 'Test Perfume',
            'slug' => 'test-perfume',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ]);
    }

    /** @test */
    public function it_creates_a_product_with_image()
    {
        // Arrange
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        $productData = new ProductData(
            brand_id: $brand->id,
            category_id: $category->id,
            name: 'Perfume with Image',
            description: 'Test',
            price: 299.99,
            stock_available: 30
        );

        $image = UploadedFile::fake()->image('product.jpg', 800, 600);

        // Act
        $product = $this->productService->createProduct($productData, $image);

        // Assert
        $this->assertNotNull($product->image_url);
        $this->assertStringContainsString('products/', $product->image_url);
        Storage::disk('public')->assertExists($product->image_url);
    }

    /** @test */
    public function it_generates_unique_slug_from_name()
    {
        // Arrange
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        $productData = new ProductData(
            brand_id: $brand->id,
            category_id: $category->id,
            name: 'Cool Perfume!!!',
            description: 'Test',
            price: 150.00,
            stock_available: 20
        );

        // Act
        $product = $this->productService->createProduct($productData, null);

        // Assert
        $this->assertEquals('cool-perfume', $product->slug);
    }

    /** @test */
    public function it_updates_a_product_successfully()
    {
        // Arrange
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'name' => 'Original Name',
            'price' => 100.00,
        ]);

        $updateData = new ProductData(
            brand_id: $brand->id,
            category_id: $category->id,
            name: 'Updated Name',
            description: 'Updated description',
            price: 250.00,
            stock_available: 75
        );

        // Act
        $updatedProduct = $this->productService->updateProduct($product, $updateData, null);

        // Assert
        $this->assertEquals('Updated Name', $updatedProduct->name);
        $this->assertEquals('updated-name', $updatedProduct->slug);
        $this->assertEquals(250.00, $updatedProduct->price);
        $this->assertEquals(75, $updatedProduct->stock_available);

        // Verify in database
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Name',
            'slug' => 'updated-name',
        ]);
    }

    /** @test */
    public function it_updates_product_image_and_deletes_old_one()
    {
        // Arrange
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        // Create product with image
        $oldImage = UploadedFile::fake()->image('old.jpg');
        $oldImagePath = $oldImage->store('products', 'public');

        $product = Product::factory()->create([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'image_url' => $oldImagePath,
        ]);

        Storage::disk('public')->assertExists($oldImagePath);

        // New data with new image
        $updateData = new ProductData(
            brand_id: $brand->id,
            category_id: $category->id,
            name: $product->name,
            description: $product->description,
            price: $product->price,
            stock_available: $product->stock_available
        );

        $newImage = UploadedFile::fake()->image('new.jpg');

        // Act
        $updatedProduct = $this->productService->updateProduct($product, $updateData, $newImage);

        // Assert
        $this->assertNotEquals($oldImagePath, $updatedProduct->image_url);
        Storage::disk('public')->assertMissing($oldImagePath);
        Storage::disk('public')->assertExists($updatedProduct->image_url);
    }

    /** @test */
    public function it_soft_deletes_a_product()
    {
        // Arrange
        $product = Product::factory()->create();

        // Act
        $result = $this->productService->deleteProduct($product);

        // Assert
        $this->assertTrue($result);
        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);

        // Verify product is not in normal queries
        $this->assertNull(Product::find($product->id));

        // But exists with trashed
        $this->assertNotNull(Product::withTrashed()->find($product->id));
    }

    /** @test */
    public function it_loads_relationships_after_creation()
    {
        // Arrange
        $brand = Brand::factory()->create(['name' => 'Test Brand']);
        $category = Category::factory()->create(['name' => 'Test Category']);

        $productData = new ProductData(
            brand_id: $brand->id,
            category_id: $category->id,
            name: 'Test Product',
            description: 'Test',
            price: 100.00,
            stock_available: 10
        );

        // Act
        $product = $this->productService->createProduct($productData, null);

        // Assert
        $this->assertTrue($product->relationLoaded('brand'));
        $this->assertTrue($product->relationLoaded('category'));
        $this->assertEquals('Test Brand', $product->brand->name);
        $this->assertEquals('Test Category', $product->category->name);
    }
}