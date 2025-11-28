<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 font-luxury">Shopping Cart</h1>
        </div>

        @if ($cartItems->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach ($cartItems as $item)
                        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                            <div class="flex items-center space-x-4">
                                <!-- Product Image -->
                                <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                    @if ($item->product->image_url)
                                        <img src="{{ asset($item->product->image_url) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-200 text-xs text-center p-1">
                                            No Image
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-lg text-gray-900">{{ $item->product->name }}</h3>
                                    <p class="text-gray-600">{{ $item->product->brand }}
                                        @if ($item->product->model)
                                            • {{ $item->product->model }}
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-500">Certified Authentic • Free Shipping</p>
                                </div>

                                <!-- Price and Quantity -->
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-luxury-gold mb-2">
                                        ${{ number_format($item->product->price, 2) }}
                                    </div>

                                    <div class="flex items-center justify-end space-x-2">
                                        <button type="button" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                            class="w-8 h-8 bg-gray-200 rounded-l flex items-center justify-center hover:bg-gray-300 {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <div class="w-16 h-8 flex items-center justify-center border-t border-b border-gray-200 bg-white">
                                            {{ $item->quantity }}
                                        </div>
                                        <button type="button" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                            class="w-8 h-8 bg-gray-200 rounded-r flex items-center justify-center hover:bg-gray-300 {{ $item->quantity >= 10 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $item->quantity >= 10 ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <form action="{{ route('cart.destroy', $item) }}" method="POST" class="inline-block mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm flex items-center" onclick="return confirm('Remove this item from cart?')">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6 sticky top-24 border border-gray-200">
                        <h2 class="text-xl font-semibold mb-6 font-luxury">Order Summary</h2>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-semibold">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipping</span>
                                <span class="text-green-600 font-semibold">Free</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax (8%)</span>
                                <span class="font-semibold">${{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total</span>
                                    <span class="text-luxury-gold">${{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="block w-full bg-luxury-gold text-white font-bold py-3 px-4 rounded hover:bg-luxury-darkGold transition mb-3 text-center">
                            Proceed to Checkout
                        </a>

                        <a href="{{ route('products.index') }}" class="block text-center text-luxury-gold hover:text-luxury-darkGold text-sm">
                            Continue Shopping
                        </a>

                        <div class="mt-6 pt-6 border-t">
                            <p class="text-sm text-gray-500 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.065-6.064A7.065 7.065 0 1119.065 5.2"></path>
                                </svg>
                                Secure checkout powered by SSL
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-16 bg-white rounded-lg shadow">
                <div class="w-24 h-24 bg-gray-100 rounded-full mx-auto mb-6 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 font-luxury">Your cart is empty</h2>
                <p class="text-gray-600 mb-8">Discover our collection of luxury timepieces and add some to your cart.</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-luxury-gold text-white font-bold py-3 px-8 rounded hover:bg-luxury-darkGold transition">
                    Start Shopping
                </a>
            </div>
        @endif
    </div>

    <script>
        function updateQuantity(cartId, quantity) {
            if (quantity < 1 || quantity > 10) return;

            fetch(`/cart/${cartId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-HTTP-Method-Override': 'PATCH'
                },
                body: JSON.stringify({ quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</x-app-layout>
