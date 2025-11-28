<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if($orders->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No orders</h3>
                        <p class="mt-1 text-sm text-gray-500">You haven't placed any orders yet.</p>
                        <div class="mt-6">
                            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-luxury-gold hover:bg-luxury-darkGold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-luxury-gold">
                                Start Shopping
                            </a>
                        </div>
                    </div>
                @else
                    <div class="space-y-8">
                        @foreach($orders as $order)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                                <div class="bg-gray-50 px-4 py-4 sm:px-6 flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Order Placed</p>
                                            <p class="text-sm font-bold text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Total</p>
                                            <p class="text-sm font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Order #</p>
                                            <p class="text-sm font-bold text-gray-900">{{ $order->id }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        <!-- <a href="{{ route('orders.show', $order) }}" class="text-luxury-gold hover:text-luxury-darkGold text-sm font-medium">View Invoice</a> -->
                                    </div>
                                </div>
                                <div class="px-4 py-4 sm:px-6">
                                    <ul role="list" class="divide-y divide-gray-200">
                                        @foreach($order->items as $item)
                                            <li class="py-4 flex items-center">
                                                <div class="flex-shrink-0 w-16 h-16 border border-gray-200 rounded-md overflow-hidden">
                                                    @if($item->product && $item->product->image_url)
                                                        <img src="{{ asset($item->product->image_url) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                                    @endif
                                                </div>
                                                <div class="ml-4 flex-1">
                                                    <div class="flex items-center justify-between">
                                                        <div>
                                                            <h4 class="text-sm font-bold text-gray-900">{{ $item->product->name ?? 'Product Unavailable' }}</h4>
                                                            <p class="text-sm text-gray-500">{{ $item->product->brand ?? '' }}</p>
                                                        </div>
                                                        <p class="text-sm font-medium text-gray-900">${{ number_format($item->price, 2) }}</p>
                                                    </div>
                                                    <p class="mt-1 text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
