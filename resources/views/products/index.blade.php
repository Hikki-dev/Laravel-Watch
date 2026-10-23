<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-10 reveal">
            <span class="eyebrow block mb-3">The collection</span>
            <h1 class="font-luxury font-light text-5xl leading-none mb-3">Fine watches</h1>
            <p class="text-gray-500">
                {{ $products->total() }} {{ Str::plural('timepiece', $products->total()) }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 sticky top-24 border border-gray-200" style="border-radius: 2px">
                    <h3 class="eyebrow mb-5">Filters</h3>

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
                            <button type="submit" class="btn-luxury-outline w-full" style="padding: .7rem 1rem">Apply</button>
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
                        <a href="{{ route('products.create') }}" class="btn-luxury">
                            Add New Product
                        </a>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($products as $product)
                        <div class="card-luxury overflow-hidden group">
                            <a href="{{ route('products.show', $product) }}" class="block p-5">
                                <div class="aspect-square stage relative overflow-hidden mb-5">
                                    @if($product->image_url)
                                        <x-watch-photo :src="asset($product->image_url)" :alt="$product->name" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">No image</div>
                                    @endif

                                    <span class="pill absolute top-3 left-3 bg-white text-gray-800 border border-gray-200">
                                        {{ ucfirst($product->condition_type ?? 'New') }}
                                    </span>
                                </div>

                                <div class="space-y-1.5">
                                    <span class="eyebrow block">{{ $product->brand }}</span>
                                    <h3 class="font-luxury text-2xl leading-tight">{{ $product->name }}</h3>
                                    <span class="font-mono text-sm block pt-1.5">${{ number_format($product->price) }}</span>
                                </div>
                            </a>

                            @if(auth()->check() && auth()->user()->isAdmin())
                                <div class="px-5 pb-5 flex justify-between border-t border-gray-200 pt-4">
                                    <a href="{{ route('products.edit', $product) }}" class="text-luxury-gold text-sm font-medium">Edit</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium">Delete</button>
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
