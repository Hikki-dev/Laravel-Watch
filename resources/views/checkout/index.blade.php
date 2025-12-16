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

                            <div class="mt-6">
                                <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded hover:bg-indigo-700 transition flex justify-center items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.895-1.352 2.222-1.352 1.242 0 2.657.492 3.369.92l.738-1.551c-1.285-.92-3.132-1.254-4.567-1.254-2.551 0-4.015 1.325-4.015 3.363 0 2.592 2.809 3.196 4.706 3.844 1.832.618 2.308.97 2.308 1.848 0 .914-1.094 1.481-2.586 1.481-1.36 0-3.32-.781-4.053-1.3l-.758 1.574c1.238.996 3.203 1.464 5.093 1.464 2.895 0 4.29-1.554 4.29-3.52 0-2.613-2.613-3.08-4.391-3.693Z"/>
                                    </svg>
                                    Pay with Stripe (${{ number_format($total, 2) }})
                                </button>
                                <p class="mt-4 text-center text-sm text-gray-500">
                                    <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Your payment is secure and processed by Stripe.
                                </p>
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
