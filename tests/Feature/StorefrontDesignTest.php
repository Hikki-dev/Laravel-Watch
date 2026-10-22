<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontDesignTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_with_theme_toggle_and_brand(): void
    {
        Category::factory()->create(['name' => 'Rolex', 'status' => 'approved']);
        Product::factory()->create(['is_featured' => true, 'status' => 'approved']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Switch colour mode', false)
            ->assertSee(config('app.brand'))
            ->assertSee("localStorage.getItem('lux-theme')", false);
    }

    public function test_products_page_renders(): void
    {
        Product::factory()->count(2)->create(['status' => 'approved']);

        $this->get('/products')->assertOk()->assertSee('Fine watches');
    }

    public function test_product_page_hides_360_viewer_without_frames(): void
    {
        $product = Product::factory()->create(['spin_frames' => null]);

        $this->get("/products/{$product->id}")
            ->assertOk()
            ->assertDontSee('data-spin360', false);
    }

    public function test_product_page_shows_360_viewer_with_frames(): void
    {
        $frames = array_map(fn ($i) => "/frames/watch-{$i}.jpg", range(1, 12));
        $product = Product::factory()->create(['spin_frames' => $frames]);

        $this->get("/products/{$product->id}")
            ->assertOk()
            ->assertSee('data-spin360', false)
            ->assertSee('data-count="12"', false);
    }

    public function test_brand_logo_component_falls_back_to_name(): void
    {
        $html = view('components.brand-logo', ['name' => 'Unknown Maker', 'attributes' => new \Illuminate\View\ComponentAttributeBag()])->render();

        $this->assertStringContainsString('Unknown Maker', $html);
    }
}
