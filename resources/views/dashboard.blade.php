<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                <h1 class="text-3xl font-bold text-gray-900 font-luxury mb-4">Welcome back, {{ Auth::user()->name }}</h1>
                <p class="text-gray-600">Manage your profile, view your order history, and track your shipments.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Orders -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Recent Orders</h2>
                            <a href="{{ route('orders.index') }}" class="text-sm text-luxury-gold hover:text-luxury-darkGold">View All History</a>
                        </div>

                        @if($recentOrders->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($recentOrders as $order)
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
                                                <td class="px-4 py-3 text-sm">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm font-medium">
                                                    <a href="{{ route('orders.show', $order) }}" class="text-luxury-gold hover:text-luxury-darkGold">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500 mb-4">You haven't placed any orders yet.</p>
                                <a href="{{ route('products.index') }}" class="inline-block bg-luxury-gold text-white px-6 py-2 rounded-lg hover:bg-luxury-darkGold transition">Start Shopping</a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Account Settings</h2>
                        <div class="space-y-4">
                            <a href="{{ route('profile.show') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div>
                                    <div class="font-medium text-gray-900">Profile Information</div>
                                    <div class="text-sm text-gray-500">Update your account details</div>
                                </div>
                            </a>
                            
                            <a href="{{ route('cart.index') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m2.6 8L6 7m1 6v4a1 1 0 001 1h8a1 1 0 001-1v-4m-2 0L12 9l-3 4m0 0a2 2 0 102 2 2 0 00-2-2z"></path>
                                </svg>
                                <div>
                                    <div class="font-medium text-gray-900">My Cart</div>
                                    <div class="text-sm text-gray-500">View your shopping bag</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
