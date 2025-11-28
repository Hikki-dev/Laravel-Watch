<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 font-luxury mb-2">Luxury Watches</h1>
            <p class="text-gray-600">
                Showing {{ $products->total() }} premium timepieces
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-24 border border-gray-200">
                    <h3 class="font-semibold text-lg mb-4">Filters</h3>

                    <!-- Search -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <livewire:product-search />
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                        <form method="GET" action="{{ route('products.index') }}" class="space-y-2">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="brand" value="{{ request('brand') }}">

                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="min_price" placeholder="Min"
                                    value="{{ request('min_price') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-luxury-gold focus:ring-luxury-gold text-sm">
                                <input type="number" name="max_price" placeholder="Max"
                                    value="{{ request('max_price') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-luxury-gold focus:ring-luxury-gold text-sm">
                            </div>
                            <button type="submit" class="w-full border-2 border-luxury-gold text-luxury-gold font-semibold py-2 rounded-md hover:bg-luxury-gold hover:text-white transition text-sm">Apply</button>
                        </form>

                        <!-- Quick price filters -->
                        <div class="mt-3 space-y-1">
                            <div class="text-sm text-gray-600 mb-2">Quick filters:</div>
                            <a href="{{ route('products.index', array_merge(request()->query(), ['min_price' => 0, 'max_price' => 5000])) }}"
                                class="block text-sm hover:text-luxury-gold {{ request('max_price') == 5000 ? 'text-luxury-gold font-semibold' : '' }}">
                                Under $5,000
                            </a>
                            <a href="{{ route('products.index', array_merge(request()->query(), ['min_price' => 5000, 'max_price' => 15000])) }}"
                                class="block text-sm hover:text-luxury-gold">
                                $5,000 - $15,000
                            </a>
                            <a href="{{ route('products.index', array_merge(request()->query(), ['min_price' => 15000, 'max_price' => 50000])) }}"
                                class="block text-sm hover:text-luxury-gold">
                                $15,000 - $50,000
                            </a>
                            <a href="{{ route('products.index', array_merge(request()->query(), ['min_price' => 50000])) }}"
                                class="block text-sm hover:text-luxury-gold">
                                Over $50,000
                            </a>
                        </div>
                    </div>

                    <!-- Brand Filter -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                        <div class="space-y-2">
                            <a href="{{ route('products.index') }}"
                                class="block text-sm hover:text-luxury-gold {{ !request('brand') ? 'text-luxury-gold font-semibold' : '' }}">
                                All Brands
                            </a>
                            @foreach(\App\Models\Category::all() as $category)
                                <a href="{{ route('products.index', array_merge(request()->query(), ['brand' => $category->name])) }}"
                                    class="block text-sm hover:text-luxury-gold {{ request('brand') == $category->name ? 'text-luxury-gold font-semibold' : '' }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="lg:col-span-3">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(auth()->check() && auth()->user()->isAdmin())
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Product
                        </a>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($products as $product)
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden group hover:shadow-xl transition duration-300">
                            <a href="{{ route('products.show', $product) }}" class="block">
                                <div class="aspect-square bg-gray-100 relative overflow-hidden">
                                    @if($product->image_url)
                                        <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-200">
                                            No Image
                                        </div>
                                    @endif
                                    
                                    <!-- Badges -->
                                    <div class="absolute top-3 left-3 space-y-2">
                                        <span class="inline-block bg-luxury-gold text-white text-xs px-2 py-1 rounded">
                                            {{ ucfirst($product->condition_type ?? 'New') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex text-yellow-400">
                                            @for ($i = 0; $i < 5; $i++)
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                            <span class="text-sm ml-1 text-gray-600">(4.8)</span>
                                        </div>
                                    </div>

                                    <h3 class="font-semibold text-lg mb-1 truncate">{{ $product->name }}</h3>
                                    <p class="text-gray-600 text-sm mb-3">{{ $product->brand }}</p>

                                    <div class="flex items-center justify-between">
                                        <span class="text-2xl font-bold text-luxury-gold">${{ number_format($product->price) }}</span>
                                        <span class="bg-luxury-gold text-white text-sm px-4 py-2 rounded hover:bg-luxury-darkGold transition">
                                            View Details
                                        </span>
                                    </div>
                                </div>
                            </a>
                            
                            @if(auth()->check() && auth()->user()->isAdmin())
                                <div class="px-4 pb-4 flex justify-between border-t pt-4">
                                    <a href="{{ route('products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Delete</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full text-center py-16">
                            <div class="w-24 h-24 bg-gray-100 rounded-full mx-auto mb-6 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                            <p class="text-gray-500 mb-6">Try adjusting your filters or search criteria</p>
                            <a href="{{ route('products.index') }}" class="text-luxury-gold font-semibold hover:underline">View All Products</a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
