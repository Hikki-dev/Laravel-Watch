<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Get list of user's favorite products (IDs only usually, or full products).
     * For now, returning product objects to match Frontend needs.
     */
    public function index(Request $request)
    {
        // Return list of favorite product IDs as a simple array for easy checking
        // Or return full objects if you want a "Wishlist Page"
        
        // Let's return both: IDs for checking, Objects for display
        $favorites = $request->user()->favorites()->with('product')->get();
        
        $productIds = $favorites->pluck('product_id');
        $products = $favorites->pluck('product');

        return response()->json([
            'ids' => $productIds,
            'products' => $products
        ]);
    }

    /**
     * Toggle or Add favorite
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = $request->user();
        $productId = $request->product_id;

        // Check if exists
        $existing = Favorite::where('user_id', $user->id)
                            ->where('product_id', $productId)
                            ->first();

        if ($existing) {
            // If exists, remove it (Toggle behavior)
            $existing->delete();
            return response()->json(['message' => 'Removed from favorites', 'status' => 'removed']);
        } else {
            // Create
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            return response()->json(['message' => 'Added to favorites', 'status' => 'added']);
        }
    }

    /**
     * Specific removal if needed
     */
    public function destroy(Request $request, $productId)
    {
        $deleted = Favorite::where('user_id', $request->user()->id)
                           ->where('product_id', $productId)
                           ->delete();
        
        if ($deleted) {
             return response()->json(['message' => 'Removed']);
        }
        return response()->json(['message' => 'Not found'], 404);
    }
}
