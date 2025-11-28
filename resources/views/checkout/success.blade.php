<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 text-center">
                <div class="mb-8">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                        <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h2 class="mt-4 text-3xl font-bold text-gray-900">Order Confirmed!</h2>
                    <p class="mt-2 text-lg text-gray-600">Thank you for your purchase. Your order #{{ $order->id }} has been placed.</p>
                </div>

                <div class="max-w-md mx-auto bg-gray-50 rounded-lg p-6 mb-8 text-left">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Details</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Order Number:</span>
                            <span class="font-medium text-gray-900">#{{ $order->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Date:</span>
                            <span class="font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Amount:</span>
                            <span class="font-medium text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Payment Method:</span>
                            <span class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center space-x-4">
                    <a href="{{ route('products.index') }}" class="bg-luxury-gold text-white font-bold py-2 px-6 rounded hover:bg-luxury-darkGold transition">
                        Continue Shopping
                    </a>
                    <a href="{{ route('orders.index') }}" class="bg-gray-200 text-gray-800 font-bold py-2 px-6 rounded hover:bg-gray-300 transition">
                        View My Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
