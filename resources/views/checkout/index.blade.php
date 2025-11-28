<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Checkout Form -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping Information</h3>
                    
                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="shipping_address" class="block text-sm font-medium text-gray-700">Address</label>
                                <input type="text" name="shipping_address" id="shipping_address" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-luxury-gold focus:border-luxury-gold"
                                    value="{{ Auth::user()->address }}">
                                @error('shipping_address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" name="city" id="city" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-luxury-gold focus:border-luxury-gold"
                                        value="{{ Auth::user()->city }}">
                                    @error('city')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                                    <input type="text" name="postal_code" id="postal_code" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-luxury-gold focus:border-luxury-gold"
                                        value="{{ Auth::user()->postal_code }}">
                                    @error('postal_code')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                <input type="text" name="country" id="country" list="countries_list" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-luxury-gold focus:border-luxury-gold"
                                    value="{{ Auth::user()->country }}" placeholder="Select or type your country">
                                <datalist id="countries_list">
                                    @foreach($countries as $country)
                                        <option value="{{ $country }}">
                                    @endforeach
                                </datalist>
                                @error('country')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-4" x-data="{ paymentMethod: 'credit_card' }">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Method</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input id="credit_card" name="payment_method" type="radio" value="credit_card" x-model="paymentMethod"
                                            class="focus:ring-luxury-gold h-4 w-4 text-luxury-gold border-gray-300">
                                        <label for="credit_card" class="ml-3 block text-sm font-medium text-gray-700">
                                            Credit/Debit Card
                                        </label>
                                    </div>
                                    
                                    <!-- Credit Card Details Section -->
                                    <div x-show="paymentMethod === 'credit_card'" class="mt-4 p-4 bg-gray-50 rounded-md border border-gray-200 space-y-4" x-transition>
                                        <div>
                                            <label for="card_holder" class="block text-sm font-medium text-gray-700">Card Holder Name</label>
                                            <input type="text" name="card_holder" id="card_holder" 
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-luxury-gold focus:border-luxury-gold"
                                                placeholder="John Doe">
                                            @error('card_holder')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="card_number" class="block text-sm font-medium text-gray-700">Card Number</label>
                                            <input type="text" name="card_number" id="card_number" maxlength="19"
                                                x-on:input="$el.value = $el.value.replace(/\D/g, '').replace(/(.{4})/g, '$1 ').trim()"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-luxury-gold focus:border-luxury-gold"
                                                placeholder="0000 0000 0000 0000">
                                            @error('card_number')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="card_expiry" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                                <input type="text" name="card_expiry" id="card_expiry" placeholder="MM/YY" maxlength="5"
                                                    x-on:input="
                                                        let v = $el.value.replace(/\D/g, '');
                                                        if (v.length >= 2) v = v.slice(0, 2) + '/' + v.slice(2, 4);
                                                        $el.value = v;
                                                    "
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-luxury-gold focus:border-luxury-gold">
                                                @error('card_expiry')
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="card_cvc" class="block text-sm font-medium text-gray-700">CVC</label>
                                                <input type="text" name="card_cvc" id="card_cvc" maxlength="4"
                                                    x-on:input="$el.value = $el.value.replace(/\D/g, '')"
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-luxury-gold focus:border-luxury-gold"
                                                    placeholder="123">
                                                @error('card_cvc')
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="cash_on_delivery" name="payment_method" type="radio" value="cash_on_delivery" x-model="paymentMethod"
                                            class="focus:ring-luxury-gold h-4 w-4 text-luxury-gold border-gray-300">
                                        <label for="cash_on_delivery" class="ml-3 block text-sm font-medium text-gray-700">
                                            Cash on Delivery
                                        </label>
                                    </div>
                                </div>
                                @error('payment_method')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-6">
                                <button type="submit" class="w-full bg-luxury-gold text-white font-bold py-3 px-4 rounded hover:bg-luxury-darkGold transition">
                                    Place Order (${{ number_format($total, 2) }})
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg p-6 h-fit">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                    <div class="flow-root">
                        <ul role="list" class="-my-6 divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                                <li class="py-6 flex">
                                    <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                        <img src="{{ asset($item->product->image_url) }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover object-center">
                                    </div>
                                    <div class="ml-4 flex flex-1 flex-col">
                                        <div>
                                            <div class="flex justify-between text-base font-medium text-gray-900">
                                                <h3>{{ $item->product->name }}</h3>
                                                <p class="ml-4">${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-500">{{ $item->product->brand }}</p>
                                        </div>
                                        <div class="flex flex-1 items-end justify-between text-sm">
                                            <p class="text-gray-500">Qty {{ $item->quantity }}</p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="border-t border-gray-200 mt-6 pt-6 space-y-4">
                        <div class="flex justify-between text-sm text-gray-600">
                            <p>Subtotal</p>
                            <p>${{ number_format($subtotal, 2) }}</p>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <p>Tax (8%)</p>
                            <p>${{ number_format($tax, 2) }}</p>
                        </div>
                        <div class="flex justify-between text-base font-medium text-gray-900 pt-4 border-t">
                            <p>Total</p>
                            <p>${{ number_format($total, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
