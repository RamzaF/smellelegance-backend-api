<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductFeatureTest extends TestCase
{
    use RefreshDatabase;

    // Reset unique faker data before each test
        protected function setUp(): void
    {
        parent::setUp();
        fake()->unique(true); // Reset unique constraint
    }

    /** @test */
    public function guest_cannot_create_product()
    {
        // Arrange
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        $productData = [
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'name' => 'Test Product',
            'price' => 199.99,
            'stock_available' => 50,
        ];

        // Act
        $response = $this->postJson('/api/v1/products', $productData);

        // Assert
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    /** @test */
    public function client_user_cannot_create_product()
    {
        // Arrange
        $client = User::factory()->create(['role' => 'client']);
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        $productData = [
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'name' => 'Test Product',
            'price' => 199.99,
            'stock_available' => 50,
        ];

        // Act
        $response = $this->actingAs($client, 'sanctum')
            ->postJson('/api/v1/products', $productData);

        // Assert
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Forbidden. Admin access required.',
        ]);
    }

    /** @test */
    public function admin_can_create_product_with_valid_data()
    {
        // Arrange
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        $productData = [
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'name' => 'Test Perfume',
            'description' => 'Amazing perfume',
            'price' => 299.99,
            'stock_available' => 100,
        ];

        // Act
        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/products', $productData);

        // Assert
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'slug',
                'price',
                'stock_available',
                'brand',
                'category',
            ],
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Perfume',
            'slug' => 'test-perfume',
            'price' => 299.99,
        ]);
    }

    /** @test */
    public function admin_can_create_product_with_image()
    {
        // Arrange
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        $image = UploadedFile::fake()->image('perfume.jpg', 800, 600);

        // Act
        $response = $this->actingAs($admin, 'sanctum')
            ->post('/api/v1/products', [
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'name' => 'Perfume with Image',
                'description' => 'Test',
                'price' => 399.99,
                'stock_available' => 50,
                'image' => $image,
            ]);

        // Assert
        $response->assertStatus(201);
        $response->assertJsonPath('data.image_url', function ($value) {
            return str_contains($value, 'products/');
        });

        $product = Product::latest()->first();
        Storage::disk('public')->assertExists($product->image_url);
    }

    /** @test */
    public function validation_fails_with_missing_required_fields()
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);

        // Act
        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/products', [
                'name' => 'Incomplete Product',
                // Missing: brand_id, category_id, price, stock_available
            ]);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'brand_id',
            'category_id',
            'price',
            'stock_available',
        ]);
    }

    /** @test */
    public function validation_fails_with_invalid_price()
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        // Act
        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/products', [
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'name' => 'Test Product',
                'price' => -50, // Invalid: negative
                'stock_available' => 10,
            ]);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['price']);
    }

    /** @test */
    public function validation_fails_with_duplicate_name()
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        Product::factory()->create(['name' => 'Existing Product']);

        // Act
        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/products', [
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'name' => 'Existing Product', // Duplicate
                'price' => 100.00,
                'stock_available' => 10,
            ]);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function admin_can_update_product()
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create([
            'name' => 'Original Name',
            'price' => 100.00,
        ]);

        // Act
        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/v1/products/{$product->id}", [
                'brand_id' => $product->brand_id,
                'category_id' => $product->category_id,
                'name' => 'Updated Name',
                'price' => 250.00,
                'stock_available' => $product->stock_available,
            ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJsonPath('data.name', 'Updated Name');
        $response->assertJsonPath('data.price', '250.00');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Name',
        ]);
    }

    /** @test */
    public function client_cannot_update_product()
    {
        // Arrange
        $client = User::factory()->create(['role' => 'client']);
        $product = Product::factory()->create();

        // Act
        $response = $this->actingAs($client, 'sanctum')
            ->putJson("/api/v1/products/{$product->id}", [
                'name' => 'Hacked Name',
            ]);

        // Assert
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_delete_product()
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();

        // Act
        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/v1/products/{$product->id}");

        // Assert
        $response->assertStatus(204);
        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);
    }

    /** @test */
    public function client_cannot_delete_product()
    {
        // Arrange
        $client = User::factory()->create(['role' => 'client']);
        $product = Product::factory()->create();

        // Act
        $response = $this->actingAs($client, 'sanctum')
            ->deleteJson("/api/v1/products/{$product->id}");

        // Assert
        $response->assertStatus(403);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function anyone_can_view_products_catalog()
    {
        // Arrange
        Product::factory()->count(15)->create(['is_active' => true]);

        // Act
        $response = $this->getJson('/api/v1/products');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data',
            'meta',
            'links',
        ]);
        $response->assertJsonPath('meta.per_page', 10);
    }

    /** @test */
    public function anyone_can_view_product_details()
    {
        // Arrange
        $product = Product::factory()->create(['is_active' => true]);

        // Act
        $response = $this->getJson("/api/v1/products/{$product->id}");

        // Assert
        $response->assertStatus(200);
        $response->assertJsonPath('data.id', $product->id);
        $response->assertJsonPath('data.name', $product->name);
    }
}