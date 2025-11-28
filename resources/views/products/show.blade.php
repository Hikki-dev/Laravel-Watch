<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-luxury-gold">Watches</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-500">{{ $product->brand }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Product Images Section - Left Side -->
                <div class="space-y-4" x-data="{ mainImage: '{{ $product->images->first()->image_path ?? $product->image_url ?? '' }}' }">
                    <!-- Main Large Image -->
                    <div class="aspect-square bg-white rounded-lg overflow-hidden border border-gray-200 relative">
                        <template x-if="mainImage">
                            <img :src="mainImage.startsWith('http') || mainImage.startsWith('/') ? mainImage : '/'+mainImage" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-contain p-8">
                        </template>
                        <template x-if="!mainImage">
                            <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                <span class="text-gray-400">No image available</span>
                            </div>
                        </template>
                    </div>

                    <!-- Thumbnail Images -->
                    @if($product->images->count() > 0)
                        <div class="grid grid-cols-4 gap-4">
                            @foreach($product->images as $image)
                                <button @click="mainImage = '{{ $image->image_path }}'"
                                    class="aspect-square bg-white rounded-lg overflow-hidden border-2 border-gray-200 hover:border-luxury-gold transition-colors focus:outline-none focus:border-luxury-gold">
                                    <img src="{{ asset($image->image_path) }}" 
                                         alt="Thumbnail" 
                                         class="w-full h-full object-contain p-2">
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <!-- Product Features -->
                    <div class="bg-gray-50 rounded-lg p-6 mt-6">
                        <h3 class="font-semibold text-lg mb-4">Key Features</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-luxury-gold mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm">Authentic Certified</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-luxury-gold mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm">Free Shipping</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-luxury-gold mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm">Secure Payment</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-luxury-gold mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm">14-Day Returns</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Information Section - Right Side -->
                <div>
                    <!-- Status Badges -->
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="inline-block bg-luxury-gold text-white text-xs px-3 py-1 rounded-full">
                            {{ ucfirst($product->condition_type ?? 'Pre-owned') }}
                        </span>
                        @if ($product->is_featured)
                            <span class="inline-block bg-red-500 text-white text-xs px-3 py-1 rounded-full">Featured</span>
                        @endif
                        @if ($product->stock_quantity <= 1)
                            <span class="inline-block bg-orange-500 text-white text-xs px-3 py-1 rounded-full">Limited Stock</span>
                        @endif
                    </div>

                    <!-- Product Name and Brand -->
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    <div class="flex items-center space-x-2 mb-4">
                        <p class="text-xl text-gray-600">{{ $product->brand }}</p>
                        @if ($product->model)
                            <span class="text-gray-400">•</span>
                            <p class="text-xl text-gray-600">{{ $product->model }}</p>
                        @endif
                        @if ($product->reference_number)
                            <span class="text-gray-400">•</span>
                            <p class="text-lg text-gray-500">Ref: {{ $product->reference_number }}</p>
                        @endif
                    </div>

                    <!-- Rating -->
                    <div class="flex items-center mb-6">
                        <div class="flex text-yellow-400">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="ml-2 text-gray-600">(4.8 out of 5 stars)</span>
                    </div>

                    <!-- Price Section -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="flex items-baseline">
                            <span class="text-4xl font-bold text-luxury-gold">${{ number_format($product->price, 2) }}</span>
                            @if ($product->original_price && $product->original_price > $product->price)
                                <span class="ml-3 text-xl text-gray-400 line-through">${{ number_format($product->original_price, 2) }}</span>
                                <span class="ml-2 bg-green-100 text-green-800 text-sm px-2 py-1 rounded">
                                    Save {{ round((($product->original_price - $product->price) / $product->original_price) * 100) }}%
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">Description</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                    </div>

                    <!-- Add to Cart Section -->
                    @if (Auth::check() && Auth::user()->role === 'customer')
                        <form method="POST" action="{{ route('cart.store', $product) }}" class="mb-6" x-data="{ quantity: 1, max: {{ $product->stock_quantity }} }">
                            @csrf
                            <div class="flex items-center space-x-4 mb-6">
                                <label class="text-sm font-medium text-gray-700">Quantity:</label>
                                <div class="flex items-center border border-gray-300 rounded">
                                    <button type="button" @click="if(quantity > 1) quantity--" class="px-3 py-2 hover:bg-gray-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <input type="number" name="quantity" x-model="quantity" min="1" :max="max"
                                        class="w-16 text-center border-0 focus:ring-0 p-0">
                                    <button type="button" @click="if(quantity < max) quantity++" class="px-3 py-2 hover:bg-gray-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                </div>
                                <span class="text-sm text-gray-500">
                                    {{ $product->stock_quantity }} available
                                </span>
                            </div>

                            <div class="flex space-x-4">
                                <button type="submit" class="flex-1 bg-luxury-gold text-white font-bold py-3 px-6 rounded hover:bg-luxury-darkGold transition flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Add to Cart
                                </button>
                                <button type="submit" name="buy_now" value="1" class="flex-1 bg-gray-900 text-white font-bold py-3 px-6 rounded hover:bg-gray-800 transition flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Buy Now
                                </button>
                            </div>
                        </form>
                    @elseif (!Auth::check())
                        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg mb-6">
                            <p class="text-gray-700 mb-3">Please login to purchase this luxury timepiece</p>
                            <a href="{{ route('login') }}" class="inline-block bg-luxury-gold text-white font-bold py-2 px-4 rounded hover:bg-luxury-darkGold">Login to Purchase</a>
                        </div>
                    @endif

                    <!-- Technical Specifications -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold mb-4">Technical Specifications</h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @if ($product->movement_type)
                                <div class="bg-gray-50 p-3 rounded">
                                    <dt class="text-sm text-gray-600 mb-1">Movement</dt>
                                    <dd class="font-medium">{{ ucfirst($product->movement_type) }}</dd>
                                </div>
                            @endif
                            @if ($product->case_material)
                                <div class="bg-gray-50 p-3 rounded">
                                    <dt class="text-sm text-gray-600 mb-1">Case Material</dt>
                                    <dd class="font-medium">{{ $product->case_material }}</dd>
                                </div>
                            @endif
                            @if ($product->dial_color)
                                <div class="bg-gray-50 p-3 rounded">
                                    <dt class="text-sm text-gray-600 mb-1">Dial Color</dt>
                                    <dd class="font-medium">{{ $product->dial_color }}</dd>
                                </div>
                            @endif
                            @if ($product->strap_material)
                                <div class="bg-gray-50 p-3 rounded">
                                    <dt class="text-sm text-gray-600 mb-1">Strap/Bracelet</dt>
                                    <dd class="font-medium">{{ $product->strap_material }}</dd>
                                </div>
                            @endif
                            @if ($product->water_resistance)
                                <div class="bg-gray-50 p-3 rounded">
                                    <dt class="text-sm text-gray-600 mb-1">Water Resistance</dt>
                                    <dd class="font-medium">{{ $product->water_resistance }}</dd>
                                </div>
                            @endif
                            @if ($product->year_manufactured)
                                <div class="bg-gray-50 p-3 rounded">
                                    <dt class="text-sm text-gray-600 mb-1">Year</dt>
                                    <dd class="font-medium">{{ $product->year_manufactured }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    @if ($product->warranty_info)
                        <div class="border-t pt-6 mt-6">
                            <h3 class="text-lg font-semibold mb-4">Warranty Information</h3>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <p class="text-gray-700">{{ $product->warranty_info }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Seller Information -->
                    @if($product->seller)
                        <div class="border-t pt-6 mt-6">
                            <h3 class="text-lg font-semibold mb-4">Seller Information</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-luxury-gold rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">
                                            {{ strtoupper(substr($product->seller->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $product->seller->name }}</p>
                                        <p class="text-sm text-gray-600">
                                            <svg class="w-4 h-4 inline text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Verified Seller • Member since {{ $product->seller->created_at->format('Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Products Section -->
            @if ($related_products->count() > 0)
                <div class="mt-16">
                    <h2 class="text-2xl font-bold mb-8">You May Also Like</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($related_products as $related)
                            <a href="{{ route('products.show', $related) }}" class="group bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition">
                                <div class="aspect-square bg-gray-100 overflow-hidden relative">
                                    @if ($related->image_url)
                                        <img src="{{ asset($related->image_url) }}"
                                            alt="{{ $related->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400">
                                            No Image
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-lg mb-1 truncate text-gray-900">{{ $related->name }}</h3>
                                    <p class="text-gray-600 text-sm mb-2">{{ $related->brand }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xl font-bold text-luxury-gold">${{ number_format($related->price) }}</span>
                                        <span class="text-sm text-luxury-gold font-medium group-hover:underline">View Details →</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
