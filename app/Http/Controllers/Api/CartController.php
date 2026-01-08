<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Auth::user()->cart()->with('product')->get();
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        $tax = $subtotal * 0.08;
        $total = $subtotal + $tax;

        return response()->json([
            'status' => 'success',
            'data' => [
                'cart_items' => $cartItems,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
            ]
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $cartItem = Auth::user()->cart()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity ?? 1);
        } else {
            Auth::user()->cart()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity ?? 1,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart successfully'
        ]);
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $cart->update(['quantity' => $request->quantity]);

        return response()->json([
            'status' => 'success',
            'message' => 'Cart item updated'
        ]);
    }

    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $cart->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Item removed from cart'
        ]);
    }
}
