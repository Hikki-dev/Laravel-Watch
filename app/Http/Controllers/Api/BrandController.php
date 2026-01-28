<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index()
    {
        // Get all unique brands from the products table
        // We only want brands from Active/Approved products to show up globally?
        // Usually yes.
        $brands = Product::where('status', 'approved')
                         ->select('brand')
                         ->distinct()
                         ->orderBy('brand')
                         ->pluck('brand');

        $data = $brands->map(function ($brandName) {
            // Generate a sleek letter icon using UI Avatars
            // We can customize background colors to be 'sleek' (e.g., dark blue, black, gold)
            // For now, let's use a random but deterministic approach or just standard.
            // LuxWatch theme: Black/Gold? Let's try to make it look premium.
            // background=000000&color=d4af37 (Black & Gold)
            
            return [
                'name' => $brandName,
                'slug' => \Illuminate\Support\Str::slug($brandName),
                // 'Letter Icon' generation
                'icon_url' => "https://ui-avatars.com/api/?name=" . urlencode($brandName) . "&background=000000&color=d4af37&size=128&bold=true&font-size=0.5",
                'type' => 'auto-generated'
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
