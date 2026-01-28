<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_view_product_list()
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data']);
    }

    public function test_anyone_can_view_single_product()
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $category = Category::factory()->create();
        
        $product = Product::factory()->create([
            'seller_id' => $seller->id,
            'category_id' => $category->id
        ]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data' => [
                         'id',
                         'name',
                         'description',
                         'price',
                         'images',
                         'category',
                         'seller'
                     ]
                 ]);
    }

    public function test_seller_can_create_product()
    {
        Storage::fake('public');
        
        $seller = User::factory()->create(['role' => 'seller']);
        $category = Category::factory()->create();
        
        Sanctum::actingAs($seller, ['product:create']);

        $response = $this->postJson('/api/seller/products', [
            'name' => 'Rolex Submariner',
            'description' => 'A classic diver watch.',
            'price' => 12000,
            'stock_quantity' => 5,
            'brand' => 'Rolex',
            'model' => 'Submariner',
            'category_id' => $category->id,
            'images' => [
                UploadedFile::fake()->image('watch1.jpg')
            ]
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', ['name' => 'Rolex Submariner']);
    }

    public function test_seller_can_update_own_product()
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $product = Product::factory()->create(['seller_id' => $seller->id]);
        
        Sanctum::actingAs($seller, ['product:update']);

        $response = $this->putJson("/api/seller/products/{$product->id}", [
            'name' => 'Updated Watch Name',
            'price' => 15000,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Watch Name',
            'status' => 'pending' // Should reset to pending on update
        ]);
    }

    public function test_seller_cannot_update_others_product()
    {
        $seller1 = User::factory()->create(['role' => 'seller']);
        $seller2 = User::factory()->create(['role' => 'seller']);
        $product = Product::factory()->create(['seller_id' => $seller2->id]);
        
        Sanctum::actingAs($seller1, ['product:update']);

        $response = $this->putJson("/api/seller/products/{$product->id}", [
            'name' => 'Hacker Update',
        ]);

        $response->assertStatus(403);
    }

    public function test_seller_can_delete_own_product()
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $product = Product::factory()->create(['seller_id' => $seller->id]);
        
        Sanctum::actingAs($seller, ['product:delete']);

        $response = $this->deleteJson("/api/seller/products/{$product->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
