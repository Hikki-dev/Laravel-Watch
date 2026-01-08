<div wire:poll.10s>
    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Products --}}
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Products</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_products'] }}</p>
                </div>
            </div>
        </div>

        {{-- Total Customers --}}
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Registered Customers</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_customers'] }}</p>
                </div>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-500">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Orders</p>
                    <div class="flex items-baseline">
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_orders'] }}</p>
                        <p class="ml-2 text-xs text-gray-400">({{ $stats['total_accepted_orders'] }} Accepted)</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                    <p class="text-2xl font-semibold text-gray-900">${{ number_format($stats['total_revenue'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity Tables --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Latest Orders --}}
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Latest Orders</h3>
            </div>
            <div class="p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($latest_orders as $order)
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">#{{ $order->id }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->user->name }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">${{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-gray-500">No recent orders.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Latest Products --}}
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Latest Products</h3>
            </div>
            <div class="p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($latest_products as $product)
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap">
                                @if($product->image_url || $product->image)
                                    <img class="h-8 w-8 rounded-full object-cover" 
                                         src="{{ $product->image_url ? (str_starts_with($product->image_url, 'http') ? $product->image_url : asset('storage/'.$product->image_url)) : asset('storage/'.$product->image) }}" 
                                         alt="{{ $product->name }}"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($product->name) }}&color=7F9CF5&background=EBF4FF'">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs text-gray-500">
                                        {{ substr($product->name, 0, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">${{ number_format($product->price, 2) }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $product->brand }}</td>
                        </tr>
                         @empty
                        <tr><td colspan="4" class="text-center py-4 text-gray-500">No products found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Latest Customers --}}
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Latest Customers</h3>
            </div>
            <div class="p-6">
               <table class="min-w-full divide-y divide-gray-200">
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($latest_customers as $customer)
                        <tr>
                             <td class="px-3 py-2 whitespace-nowrap">
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ $customer->profile_photo_url }}" alt="{{ $customer->name }}">
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $customer->name }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $customer->email }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $customer->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-gray-500">No customers found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Latest Reviews --}}
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Latest Feedback</h3>
            </div>
            <div class="p-6">
               <table class="min-w-full divide-y divide-gray-200">
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($latest_reviews as $review)
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900 text-truncate" style="max-width: 150px;" title="{{ $review->product->name ?? 'Unknown item' }}">
                                {{ $review->product->name ?? 'Unknown item' }}
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                                @for($i=0; $i<5; $i++)
                                    <span class="{{ $i < $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                @endfor
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 text-truncate" style="max-width: 200px;">{{ $review->comment }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4 text-gray-500">No reviews yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
