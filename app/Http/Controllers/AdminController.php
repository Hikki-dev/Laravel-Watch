<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the Admin Dashboard with key statistics.
     */
    public function dashboard()
    {
        // Gather statistics for the dashboard widgets
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'recent_orders' => Order::with('user')->latest()->take(5)->get(),
            'pending_products' => Product::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * List all users for management.
     */
    public function users()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * List all orders.
     */
    public function orders()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show list of products waiting for approval.
     */
    public function approvals()
    {
        // Fetch products where status is 'pending', including seller info
        $pendingProducts = Product::where('status', 'pending')->with('seller')->latest()->paginate(10);
        return view('admin.products.approvals', compact('pendingProducts'));
    }

    /**
     * Approve a pending product.
     */
    public function approve(Product $product)
    {
        $product->update([
            'status' => 'approved',
            'is_active' => true, // Make it visible in the shop
        ]);

        return redirect()->back()->with('success', 'Product approved successfully.');
    }

    /**
     * Reject a pending product.
     */
    public function reject(Product $product)
    {
        $product->update([
            'status' => 'rejected',
            'is_active' => false, // Ensure it stays hidden
        ]);

        return redirect()->back()->with('success', 'Product rejected.');
    }
    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
