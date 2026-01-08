<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class DashboardStats extends Component
{
    public function render()
    {
        $stats = [
            'total_products' => \App\Models\Product::count(),
            'total_customers' => \App\Models\User::where('role', 'customer')->count(),
            'total_orders' => \App\Models\Order::count(),
            'total_accepted_orders' => \App\Models\Order::where('status', '!=', 'pending')->count(), // Assuming non-pending is accepted/processed
            'total_revenue' => \App\Models\Order::where('payment_status', 'paid')->sum('total_amount'),
        ];

        $latest_products = \App\Models\Product::latest()->take(5)->get();
        $latest_orders = \App\Models\Order::with('user')->latest()->take(5)->get();
        $latest_customers = \App\Models\User::where('role', 'customer')->latest()->take(5)->get();
        $latest_reviews = \App\Models\Review::with(['user', 'product'])->latest()->take(5)->get();

        return view('livewire.admin.dashboard-stats', compact(
            'stats', 
            'latest_products', 
            'latest_orders', 
            'latest_customers', 
            'latest_reviews'
        ));
    }
}
