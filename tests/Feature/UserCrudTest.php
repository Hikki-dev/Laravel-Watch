<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================================
    // 1. Admin Management Tests (CRUD on Users)
    // =========================================================================

    public function test_admin_can_list_users()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->count(3)->create(['role' => 'customer']);

        Sanctum::actingAs($admin, ['*']);

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data'])
                 ->assertJsonCount(4, 'data'); // 1 Admin + 3 Customers
    }

    public function test_admin_can_create_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin, ['*']);

        $response = $this->postJson('/api/users', [
            'name' => 'New Seller',
            'email' => 'seller@example.com',
            'password' => 'password123',
            'role' => 'seller'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'seller@example.com',
            'role' => 'seller'
        ]);
    }

    public function test_admin_can_update_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $userToUpdate = User::factory()->create(['role' => 'customer']);
        
        Sanctum::actingAs($admin, ['*']);

        $response = $this->putJson("/api/users/{$userToUpdate->id}", [
            'name' => 'Updated Name',
            'email' => $userToUpdate->email, // Keep email same
            'role' => 'admin' // Promote to admin
        ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'name' => 'Updated Name',
            'role' => 'admin'
        ]);
    }

    public function test_admin_can_delete_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $userToDelete = User::factory()->create(['role' => 'customer']);

        Sanctum::actingAs($admin, ['*']);

        $response = $this->deleteJson("/api/users/{$userToDelete->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    // =========================================================================
    // 2. Access Control Tests
    // =========================================================================

    public function test_non_admin_cannot_manage_users()
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $customer = User::factory()->create(['role' => 'customer']);
        
        // Try as Seller
        Sanctum::actingAs($seller, ['product:create']); // Standard seller abilities
        $this->getJson('/api/users')->assertStatus(403);
        $this->postJson('/api/users', [])->assertStatus(403);
        $this->deleteJson("/api/users/{$customer->id}")->assertStatus(403);

        // Try as Customer
        Sanctum::actingAs($customer, ['order:create']);
        $this->getJson('/api/users')->assertStatus(403);
    }

    // =========================================================================
    // 3. Self-Service Tests (Registration & Profile)
    // =========================================================================

    public function test_user_can_register_as_seller()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'I am Seller',
            'email' => 'myseller@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'seller'
        ]);

        $response->assertStatus(201);
        
        $this->assertDatabaseHas('users', [
            'email' => 'myseller@example.com',
            'role' => 'seller'
        ]);
        
        // Verify token abilities
        $abilities = $response->json('data.abilities');
        $this->assertContains('product:create', $abilities);
    }

    public function test_user_can_update_own_profile()
    {
        $user = User::factory()->create(['role' => 'customer']);
        Sanctum::actingAs($user, ['order:create']);

        $response = $this->putJson('/api/profile', [
            'name' => 'New Profile Name',
            'email' => 'newemail@example.com',
            'video' => 'not_allowed' // Should be ignored or fail validation depending on rules? Logic says forceFill(validated). 
        ]);
        
        // The controller uses $request->validate which filters out 'video' if not in rules. 
        // Then forceFill($validated). So 'video' should be ignored.
        
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Profile Name',
            'email' => 'newemail@example.com'
        ]);
    }

    public function test_user_can_upload_profile_photo()
    {
        Storage::fake('local'); // or whatever disk is default
        
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->postJson('/api/profile/photo', [
            'photo' => $file
        ]);

        $response->assertStatus(200)
                 ->assertJson(['status' => 'success']);
                 
        // Note: The controller manually uses file_get_contents and saves to DB (binary),
        // effectively bypassing Storage facade for the content itself, 
        // but might use hashName(). Logic:
        // 'profile_photo_data' => file_get_contents($file->getRealPath())
        
        $user->refresh();
        $this->assertNotNull($user->profile_photo_data);
    }
}
