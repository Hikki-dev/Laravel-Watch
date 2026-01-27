<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_is_redirected_to_admin_dashboard_when_visiting_home()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Act as admin and visit home
        $response = $this->actingAs($admin)->get('/');

        // Logic in web.php should redirect admin from / to admin.dashboard
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_seller_is_redirected_to_seller_dashboard_when_visiting_home()
    {
        $seller = User::factory()->create(['role' => 'seller']);

        $response = $this->actingAs($seller)->get('/');

        $response->assertRedirect(route('seller.dashboard'));
    }

    public function test_customer_stays_on_home_page()
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get('/');

        // Customers see the welcome view, they are NOT redirected
        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    }
}
