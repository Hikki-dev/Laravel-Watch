<x-app-layout>
    @php
        $hero = $featured_products->first();
        $heroImage = $hero && $hero->image_url ? asset($hero->image_url) : asset('images/hero/hero-bg.jpg');
        $brandStrip = ['Patek Philippe', 'Audemars Piguet', 'Rolex', 'Omega', 'Richard Mille', 'TAG Heuer', 'Swatch', 'Seiko', 'Casio', 'Citizen'];
    @endphp

    <!-- Hero -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-8">
                <span class="eyebrow reveal reveal-1 block">The world's finest watches</span>
                <h1 class="reveal reveal-2 font-luxury font-light leading-none" style="font-size: clamp(3.25rem, 7vw, 6.25rem)">
                    Watches worth waiting for.
                </h1>
                <p class="reveal reveal-3 text-lg text-gray-500 max-w-md leading-relaxed">
                    Every listing is reviewed by our team before it goes live, so what you see is what you can buy.
                </p>
                <div class="reveal reveal-4 flex flex-wrap gap-4">
                    <a href="{{ route('products.index') }}" class="btn-luxury">Explore watches</a>
                    <a href="{{ route('register') }}" class="btn-luxury-outline">Become a seller</a>
                </div>
            </div>

            <a href="{{ $hero ? route('products.show', $hero) : route('products.index') }}"
                class="reveal reveal-3 group relative block stage border border-gray-200 overflow-hidden aspect-[4/5] lg:aspect-square">
                <img src="{{ $heroImage }}" alt="{{ $hero->name ?? 'Featured watch' }}"
                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-[1200ms] ease-out group-hover:scale-105">
                @if ($hero)
                    <div class="absolute left-0 right-0 bottom-0 bg-white/95 border-t border-gray-200 px-6 py-5 flex items-end justify-between gap-4">
                        <div>
                            <span class="eyebrow block mb-1">{{ $hero->brand }}</span>
                            <span class="font-luxury text-2xl leading-none">{{ $hero->name }}</span>
                        </div>
                        <span class="font-mono text-sm">${{ number_format($hero->price) }}</span>
                    </div>
                @endif
            </a>
        </div>
    </section>

    <!-- Brand strip -->
    <section class="border-y border-gray-200 overflow-hidden" aria-label="Brands">
        <div class="marquee py-7 gap-5">
            @foreach ([1, 2] as $repeat)
                <div class="flex gap-5 pr-5" @if ($repeat === 2) aria-hidden="true" @endif>
                    @foreach ($brandStrip as $brand)
                        <x-brand-logo :name="$brand" height="h-14" style="min-width: 9rem" />
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>

    <!-- Featured watches -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="flex flex-wrap items-end justify-between gap-4 mb-12">
            <div>
                <span class="eyebrow block mb-3">Just listed</span>
                <h2 class="font-luxury font-light text-5xl leading-none">New arrivals</h2>
            </div>
            <a href="{{ route('products.index') }}" class="text-sm uppercase tracking-widest text-luxury-gold border-b border-luxury-gold pb-1">View all watches</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($featured_products as $product)
                <a href="{{ route('products.show', $product) }}" class="card-luxury p-5 flex flex-col gap-5">
                    <div class="aspect-square stage overflow-hidden">
                        @if ($product->image_url)
                            <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" loading="lazy"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">No image</div>
                        @endif
                    </div>
                    <div class="space-y-1.5">
                        <span class="eyebrow block">{{ $product->brand }}</span>
                        <h3 class="font-luxury text-2xl leading-tight">{{ $product->name }}</h3>
                        <span class="font-mono text-sm block pt-1.5">${{ number_format($product->price) }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Brands -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="mb-10">
            <span class="eyebrow block mb-3">Maisons</span>
            <h2 class="font-luxury font-light text-5xl leading-none">Shop by brand</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($categories as $category)
                <a href="{{ route('products.index', ['brand' => $category->name]) }}"
                    class="card-luxury flex items-center justify-between gap-6 p-5">
                    <x-brand-logo :name="$category->name" height="h-16" style="width: 8.5rem" />
                    <span class="flex-1 font-luxury text-2xl">{{ $category->name }}</span>
                    <span class="font-mono text-gray-500" aria-hidden="true">→</span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Sellers -->
    <section class="bg-white border-y border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="space-y-6">
                <span class="eyebrow block">For sellers</span>
                <h2 class="font-luxury font-light leading-none" style="font-size: clamp(2.75rem, 5vw, 4.25rem)">Sell a watch the way it deserves.</h2>
                <p class="text-gray-500 text-lg max-w-lg leading-relaxed">
                    List your piece, our team reviews it, and once it sells your payout is sent through Stripe.
                </p>
                <a href="{{ route('register') }}" class="btn-luxury">Open a seller account</a>
            </div>
            <div class="grid grid-cols-3 border border-gray-200">
                @foreach ([['01', 'List', 'Add photos, details and your price.'], ['02', 'Review', 'We approve it before it goes live.'], ['03', 'Get paid', 'Payout arrives through Stripe.']] as $i => $step)
                    <div class="p-6 space-y-3 {{ $i > 0 ? 'border-l border-gray-200' : '' }}">
                        <span class="font-mono text-xs text-luxury-gold">{{ $step[0] }}</span>
                        <div class="font-luxury text-2xl leading-tight">{{ $step[1] }}</div>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $step[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
