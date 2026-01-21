<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_to_cart_api()
    {
        // 1. Create User & Product
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100]);

        // 2. Act: Call API as User
        $response = $this->actingAs($user, 'sanctum')
                         ->postJson("/api/cart/add/{$product->id}", ['quantity' => 2]);

        // 3. Assert: Check Response & Database
        $response->assertStatus(200)
                 ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);
    }

    public function test_update_cart_api()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        // Create initial cart item
        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        $response = $this->actingAs($user, 'sanctum')
                         ->patchJson("/api/cart/product/{$product->id}", ['quantity' => 5]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 5
        ]);
    }

    public function test_remove_from_cart_api()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        $response = $this->actingAs($user, 'sanctum')
                         ->deleteJson("/api/cart/product/{$product->id}");

        $response->assertStatus(200);
        
        $this->assertDatabaseMissing('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);
    }
}
