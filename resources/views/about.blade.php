<x-app-layout>
    <div class="bg-white">
        <!-- Hero Section -->
        <div class="relative bg-gray-900 h-96">
            <div class="absolute inset-0">
                <img src="{{ asset('images/hero/about-hero.jpg') }}" alt="About Luxwatch" class="w-full h-full object-cover opacity-50">
            </div>
            <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8 flex flex-col items-center justify-center h-full text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl font-luxury">About Luxwatch</h1>
                <p class="mt-6 text-xl text-gray-300 max-w-3xl">
                    Redefining luxury timekeeping since 2023.
                </p>
            </div>
        </div>

        <!-- Our Story -->
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-24 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-16 items-center">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl font-luxury mb-6">Our Story</h2>
                    <p class="text-lg text-gray-500 mb-6">
                        At Luxwatch, we believe that a watch is more than just a tool to tell time; it is a statement of style, a piece of history, and a work of art. Founded by a team of passionate horologists, our mission is to bring the world's most exquisite timepieces to discerning collectors and enthusiasts.
                    </p>
                    <p class="text-lg text-gray-500 mb-6">
                        We curate a collection that spans from the rugged durability of the Rolex Submariner to the intricate complexity of the Patek Philippe Nautilus. Every watch in our inventory is authenticated and serviced to ensure it meets our rigorous standards of quality.
                    </p>
                    <p class="text-lg text-gray-500">
                        Whether you are buying your first luxury watch or adding a holy grail piece to your collection, Luxwatch is dedicated to providing an unparalleled experience of trust, expertise, and elegance.
                    </p>
                </div>
                <div class="mt-10 lg:mt-0 relative">
                    <div class="aspect-w-3 aspect-h-2 rounded-lg overflow-hidden shadow-xl">
                        <img src="{{ asset('images/hero/about-chronos.jpg') }}" alt="Watchmaking craftsmanship" class="object-cover w-full h-full">
                    </div>
                </div>
            </div>
        </div>

        <!-- Values -->
        <div class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-extrabold text-gray-900 font-luxury">Our Values</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <div class="w-12 h-12 bg-luxury-gold text-white rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Authenticity</h3>
                        <p class="text-gray-500">We guarantee the authenticity of every timepiece we sell, backed by expert verification.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <div class="w-12 h-12 bg-luxury-gold text-white rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Excellence</h3>
                        <p class="text-gray-500">We are committed to excellence in every aspect, from our curated selection to our customer service.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <div class="w-12 h-12 bg-luxury-gold text-white rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Timelessness</h3>
                        <p class="text-gray-500">We celebrate the enduring value and beauty of mechanical watchmaking.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
