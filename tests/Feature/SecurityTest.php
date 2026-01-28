<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_access_another_users_cart_items()
    {
        // 1. Create two users
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // 2. User A creates a cart item
        $product = Product::factory()->create();
        $cartItemA = Cart::create([
            'user_id' => $userA->id,
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        // 3. Authenticate as User B
        Sanctum::actingAs($userB, ['*']);

        // 4. Try to Update User A's cart item
        $response = $this->patchJson("/api/cart/{$cartItemA->id}", [
            'quantity' => 5
        ]);

        // 5. Assert Forbidden
        $response->assertStatus(403);

        // 6. Try to Delete User A's cart item
        $response = $this->deleteJson("/api/cart/{$cartItemA->id}");
        
        // 7. Assert Forbidden
        $response->assertStatus(403);
    }

    public function test_user_cannot_access_another_users_cart_index()
    {
        $userA = User::factory()->create(['is_active' => true]);
        $userB = User::factory()->create(['is_active' => true]);

        $product = Product::factory()->create();
        Cart::create([
            'user_id' => $userA->id,
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        // Authenticate as User B
        Sanctum::actingAs($userB, ['*']);

        // User B requests THEIR cart
        $response = $this->getJson('/api/cart');

        $response->assertStatus(200);
        
        // User B's cart should be empty, NOT containing User A's item
        $response->assertJsonCount(0, 'data.cart_items');
    }

    public function test_sanctum_authentication_works()
    {
        $user = User::factory()->create();
        
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/user');

        $response->assertStatus(200)
                 ->assertJson(['email' => $user->email]);
    }
}
