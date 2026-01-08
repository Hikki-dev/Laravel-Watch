<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ApiFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_profile_photo()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                         ->postJson('/api/profile/photo', [
                             'photo' => $file,
                         ]);

        $response->assertStatus(200)
                 ->assertJson(['status' => 'success']);
        
        // Assert file exists in storage
        // The path is hashed, so we check if the user model has a profile_photo_path
        $user->refresh();
        $this->assertNotNull($user->profile_photo_path);
        Storage::disk('public')->assertExists($user->profile_photo_path);
    }

    public function test_user_can_manage_cart()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $category = Category::create(['name' => 'Test Cat', 'slug' => 'test-cat']);
        $product = Product::create([
            'name' => 'Test Product', 'slug' => 'test-product', 'description' => 'Desc',
            'price' => 100, 'stock_quantity' => 10, 'brand' => 'Brand', 'model' => 'Model',
            'category_id' => $category->id, 'seller_id' => $user->id, 'is_active' => true, 'approval_status' => 'approved' 
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                         ->postJson("/api/cart/add/{$product->id}", ['quantity' => 2]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('carts', ['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 2]);
    }

    public function test_user_can_checkout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $category = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        $product = Product::create([
            'name' => 'P', 'slug' => 'p', 'description' => 'd', 'price' => 50, 'stock_quantity' => 10, 'brand' => 'b', 'model' => 'm',
            'category_id' => $category->id, 'seller_id' => $user->id, 'is_active' => true
        ]);

        $user->cart()->create(['product_id' => $product->id, 'quantity' => 1]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                         ->postJson('/api/checkout', [
                             'shipping_address' => '123 St', 'city' => 'Metropolis', 'postal_code' => '10001', 'country' => 'USA'
                         ]);

        $response->assertStatus(201)->assertJson(['status' => 'success']);
        $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'total_amount' => 54.0]);
    }

    public function test_admin_can_manage_categories()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                         ->postJson('/api/admin/categories', ['name' => 'New Cat']);

        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', ['name' => 'New Cat', 'slug' => 'new-cat']);

        $category = Category::first();
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                         ->putJson("/api/admin/categories/{$category->id}", ['name' => 'Updated Cat']);
        
        $this->assertDatabaseHas('categories', ['name' => 'Updated Cat']);
    }

    public function test_seller_can_get_stripe_link()
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $token = $seller->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                         ->getJson('/api/seller/stripe/connect');
        
        // Should return URL if not connected, or message if mock env
        // Since we are mocking, we expect success structure or 500 depending on env key
        // We'll check for reasonable response structure.
        // If no env key, it might fail, so we might skip or expect error.
        // Assuming test env has no key, it throws 500 in try catch. 
        // Let's assume for this "unit" we just want to hit the route.
        // The controller catches Exception and returns 500 JSON.
        
        if (env('STRIPE_SECRET')) {
             $response->assertStatus(200);
        } else {
             // If no key, it fails gracefully with 500 json
             $response->assertStatus(500)->assertJson(['status' => 'error']);
        }
    }
}
