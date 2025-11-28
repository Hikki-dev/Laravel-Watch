<?php

namespace App\Http\Controllers;

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

        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'total'));
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

        if ($request->has('buy_now')) {
            return redirect()->route('checkout.index');
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $cart->update(['quantity' => $request->quantity]);

        return response()->json(['success' => true]);
    }

    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}
