<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'pending_products' => Product::where('status', 'pending')->count(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }

    public function approvals()
    {
        $pendingProducts = Product::where('status', 'pending')->with('seller')->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $pendingProducts
        ]);
    }

    public function approve(Product $product)
    {
        $product->update([
            'status' => 'approved',
            'is_active' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product approved successfully.'
        ]);
    }

    public function reject(Product $product)
    {
        $product->update([
            'status' => 'rejected',
            'is_active' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product rejected.'
        ]);
    }
}
