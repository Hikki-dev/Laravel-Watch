<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BrandApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_created_brand_is_pending()
    {
        $seller = User::factory()->create(['role' => 'seller']);

        $response = $this->actingAs($seller)->post(route('categories.store'), [
            'name' => 'Seller Brand',
            'description' => 'A pending brand',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'name' => 'Seller Brand',
            'status' => 'pending',
        ]);
    }

    public function test_admin_created_brand_is_approved()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('categories.store'), [
            'name' => 'Admin Brand',
            'description' => 'An approved brand',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'name' => 'Admin Brand',
            'status' => 'approved',
        ]);
    }

    public function test_admin_can_approve_pending_brand()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $brand = Category::create([
            'name' => 'Pending Brand',
            'slug' => 'pending-brand',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($admin)->post(route('admin.categories.approve', $brand));

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'id' => $brand->id,
            'status' => 'approved',
        ]);
    }

    public function test_admin_can_reject_pending_brand()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $brand = Category::create([
            'name' => 'To Reject Brand',
            'slug' => 'to-reject-brand',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($admin)->post(route('admin.categories.reject', $brand));

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'id' => $brand->id,
            'status' => 'rejected',
        ]);
    }

    public function test_only_approved_brands_are_visible_publicly()
    {
        Category::create(['name' => 'Approved', 'slug' => 'approved', 'status' => 'approved']);
        Category::create(['name' => 'Pending', 'slug' => 'pending', 'status' => 'pending']);
        Category::create(['name' => 'Rejected', 'slug' => 'rejected', 'status' => 'rejected']);

        $response = $this->get(route('categories.index'));

        $response->assertSee('Approved');
        $response->assertDontSee('Pending');
        $response->assertDontSee('Rejected');
    }
}
