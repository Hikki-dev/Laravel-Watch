<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased font-sans text-gray-900 bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <!-- Navigation -->
        @include('navigation-menu')


        <!-- Hero Section -->
        <section class="relative h-screen bg-gray-900 overflow-hidden">
            <div class="absolute inset-0">
                <img src="{{ asset('images/hero/hero-bg.jpg') }}" alt="Luxury watches" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black opacity-40"></div>
            </div>

            <div class="relative z-10 flex items-center justify-center h-full">
                <div class="text-center text-white max-w-4xl px-4">
                    <h1 class="text-5xl md:text-7xl font-bold mb-6">
                        Timeless Elegance
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 opacity-90">
                        Discover exquisite luxury timepieces crafted with precision
                    </p>
                    <a href="{{ route('products.index') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white text-lg px-8 py-4 inline-block rounded transition">
                        Explore Collection
                    </a>
                </div>
            </div>
        </section>

        <!-- Featured Brands Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Premium Watch Brands</h2>
                    <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                        Discover timepieces from the world's most prestigious manufacturers.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($categories as $category)
                        <div class="border rounded-lg p-6 text-center group hover:shadow-lg transition">
                            <div class="w-full h-48 bg-gray-100 rounded-lg mb-4 flex items-center justify-center p-6">
                                @if($category->image)
                                    @if(Str::startsWith($category->image, ['http', 'https']))
                                        <img src="{{ $category->image }}" alt="{{ $category->name }}" class="max-w-full max-h-full object-contain">
                                    @else
                                        <img src="{{ asset('images/brands/' . $category->image) }}" alt="{{ $category->name }}" class="max-w-full max-h-full object-contain">
                                    @endif
                                @else
                                    <span class="text-gray-400">{{ $category->name }}</span>
                                @endif
                            </div>
                            <h3 class="text-xl font-semibold mb-2">{{ $category->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($category->description, 100) }}</p>
                            <a href="{{ route('products.index', ['brand' => $category->name]) }}" class="inline-flex items-center text-yellow-600 hover:text-yellow-700 font-medium">
                                View Collection
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Featured Products Section -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Watches</h2>
                    <p class="text-gray-600 text-lg">Curated selection of exceptional timepieces</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($featured_products as $product)
                        <div class="bg-white rounded-lg shadow overflow-hidden group">
                            <div class="aspect-square bg-gray-100 overflow-hidden relative">
                                @if($product->image_url)
                                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-200">
                                        No Image
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="inline-block bg-yellow-600 text-white text-xs px-2 py-1 rounded">
                                        {{ ucfirst($product->condition_type ?? 'New') }}
                                    </span>
                                    <div class="flex text-yellow-400 text-sm">
                                        <span class="mr-1">4.8</span>
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="font-semibold text-lg mb-1 truncate">{{ $product->name }}</h3>
                                <p class="text-gray-600 text-sm mb-3">{{ $product->brand }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xl font-bold text-yellow-600">${{ number_format($product->price) }}</span>
                                    <a href="{{ route('products.show', $product) }}" class="text-sm text-gray-600 hover:text-gray-900 border border-gray-300 px-3 py-1 rounded hover:bg-gray-50">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-8">
                    <a href="{{ route('products.index') }}" class="text-yellow-600 hover:text-yellow-700 font-medium text-lg">View All Watches &rarr;</a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <x-footer />
    </div>
    @livewireScripts
</body>
</html>
