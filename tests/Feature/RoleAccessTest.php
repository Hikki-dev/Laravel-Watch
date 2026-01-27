<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    // --- Web Route Tests (Redirects via RoleMiddleware) ---

    // Note: 'role' middleware must be registered in bootstrap/app.php aliases

    public function test_admin_can_access_admin_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_seller_cannot_access_admin_dashboard()
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $response = $this->actingAs($seller)->get('/admin/dashboard');
        
        // It redirects back or to home/dashboard usually
        $response->assertStatus(302);
    }

    public function test_customer_cannot_access_seller_dashboard()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $response = $this->actingAs($customer)->get('/seller/dashboard');
        
        $response->assertStatus(302);
    }

    // --- API Ability Tests (Sanctum) ---

    public function test_admin_api_access()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Grant '*' ability to mock Admin Token
        Sanctum::actingAs($admin, ['*']);

        $response = $this->getJson('/api/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_seller_api_access_denied_to_admin_routes()
    {
        $seller = User::factory()->create(['role' => 'seller']);
        
        // Grant specific seller abilities (NOT *)
        Sanctum::actingAs($seller, ['product:create', 'product:update']);

        $response = $this->getJson('/api/admin/dashboard');
        // Admin route requires 'ability:*'
        $response->assertStatus(403);
    }

    public function test_customer_api_access_denied_to_seller_routes()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        
        // Customers usually have no special abilities or just basic ones
        Sanctum::actingAs($customer, []);

        // Use POST because GET /api/seller/products doesn't exist (only POST/PUT/DELETE are protected)
        $response = $this->postJson('/api/seller/products', []);
        
        // Seller route requires 'ability:product:create...'
        $response->assertStatus(403);
    }
}
