<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function dashboard()
    {
        $sellerId = Auth::id();
        
        $stats = [
            'total_products' => Product::where('seller_id', $sellerId)->count(),
            'approved_products' => Product::where('seller_id', $sellerId)->where('status', 'approved')->count(),
            'pending_products' => Product::where('seller_id', $sellerId)->where('status', 'pending')->count(),
            // Calculate revenue from order items for this seller's products
            'total_revenue' => \DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('products.seller_id', $sellerId)
                ->where('orders.payment_status', 'paid')
                ->sum('order_items.price'),
        ];

        $recent_products = Product::where('seller_id', $sellerId)->latest()->take(5)->get();

        return view('seller.dashboard', compact('stats', 'recent_products'));
    }

    public function products()
    {
        $products = Product::where('seller_id', Auth::id())->latest()->paginate(10);
        return view('seller.products.index', compact('products'));
    }
}
