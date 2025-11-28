<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8 font-luxury">Seller Dashboard</h1>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-luxury-gold">
                    <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">My Revenue</div>
                    <div class="text-2xl font-bold text-gray-900 mt-2">${{ number_format($stats['total_revenue'], 2) }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Products</div>
                    <div class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total_products'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Approved</div>
                    <div class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['approved_products'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Pending</div>
                    <div class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['pending_products'] }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- My Recent Products -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-900">My Recent Products</h2>
                        <a href="{{ route('seller.products.index') }}" class="text-sm text-luxury-gold hover:text-luxury-darkGold">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recent_products as $product)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $product->name }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">${{ number_format($product->price, 2) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $product->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($product->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Quick Actions</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('products.create') }}" class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-lg hover:bg-gray-100 transition border border-gray-200 col-span-2">
                            <svg class="w-8 h-8 text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Add New Product</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
