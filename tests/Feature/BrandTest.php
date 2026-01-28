<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandTest extends TestCase
{
    use RefreshDatabase;

    public function test_brands_endpoint_returns_unique_brands_with_icons()
    {
        // 1. Create Data
        // Create an approved product with brand "Breitling"
        Product::factory()->create([
            'brand' => 'Breitling',
            'status' => 'approved'
        ]);
        
        // Create another approved product with same brand (should be distinct)
        Product::factory()->create([
            'brand' => 'Breitling',
            'status' => 'approved'
        ]);

        // Create a different brand
        Product::factory()->create([
            'brand' => 'Omega',
            'status' => 'approved'
        ]);

        // Create a pending product (should NOT appear if we filter by status, but my controller currently does filtering)
        // Let's verify that assumption.
        Product::factory()->create([
            'brand' => 'SecretBrand',
            'status' => 'pending'
        ]);

        // 2. Call Endpoint
        $response = $this->getJson('/api/brands');

        // 3. Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data' => [
                         '*' => [
                             'name',
                             'slug',
                             'icon_url',
                             'type'
                         ]
                     ]
                 ]);

        // Verify Data Content
        $data = $response->json('data');
        
        // Should contain Breitling and Omega
        $names = array_column($data, 'name');
        $this->assertContains('Breitling', $names);
        $this->assertContains('Omega', $names);
        
        // Should NOT contain SecretBrand (since it's pending)
        $this->assertNotContains('SecretBrand', $names);

        // Verify Icon URL format
        $breitlingEntry = collect($data)->firstWhere('name', 'Breitling');
        $this->assertStringContainsString('ui-avatars.com', $breitlingEntry['icon_url']);
        $this->assertStringContainsString('Breitling', $breitlingEntry['icon_url']);
    }
}
