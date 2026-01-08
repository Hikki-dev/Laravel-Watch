<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Process the checkout and create the order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
        ]);

        $user = Auth::user();
        $cartItems = $user->cart()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your cart is empty.'
            ], 400);
        }

        // Calculate totals
        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $tax = $subtotal * 0.08;
        $total = $subtotal + $tax;

        DB::beginTransaction();

        try {
            // 1. Create Order (Pending)
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $total,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address . ', ' . $request->city . ', ' . $request->postal_code . ', ' . $request->country,
                'payment_status' => 'unpaid',
                'payment_method' => 'stripe',
            ]);

            // 2. Create Order Items
            foreach ($cartItems as $item) {
                // Check stock
                if ($item->product->stock_quantity < $item->quantity) {
                    throw new \Exception("Insufficient stock for product: " . $item->product->name);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
                
                // Deduct stock
                $item->product->decrement('stock_quantity', $item->quantity);
            }

            // 3. Clear Cart
            Auth::user()->cart()->delete();

            DB::commit();

            // Note: For a real mobile app, we would normally create a Stripe PaymentIntent here
            // and return the client_secret so the app can present the specific payment sheet.
            // For this API implementation, we'll return the success status and order details.
            
            return response()->json([
                'status' => 'success',
                'message' => 'Order placed successfully',
                'data' => $order
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Checkout failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
