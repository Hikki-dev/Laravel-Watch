<x-app-layout>
    <div class="bg-white">
        <!-- Hero Section -->
        <div class="relative bg-gray-900 h-64">
            <div class="absolute inset-0">
                <img src="{{ asset('images/hero/hero-bg.jpg') }}" alt="Services" class="w-full h-full object-cover opacity-40">
            </div>
            <div class="relative max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 flex flex-col items-center justify-center h-full text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl font-luxury">Our Services</h1>
                <p class="mt-4 text-xl text-gray-300 max-w-3xl">
                    Expert care for your exceptional timepieces.
                </p>
            </div>
        </div>

        <!-- Services Grid -->
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-24 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Service 1 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-luxury-gold text-white rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Watch Servicing & Repair</h3>
                    <p class="text-gray-500">
                        Our master watchmakers provide comprehensive servicing, from battery replacements to full movement overhauls, ensuring your timepiece runs perfectly.
                    </p>
                </div>

                <!-- Service 2 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-luxury-gold text-white rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Authentication</h3>
                    <p class="text-gray-500">
                        Every watch we sell is rigorously inspected. We also offer third-party authentication services for your own collection to give you peace of mind.
                    </p>
                </div>

                <!-- Service 3 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-luxury-gold text-white rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Valuation & Consignment</h3>
                    <p class="text-gray-500">
                        Looking to sell? We offer competitive valuation and consignment services to help you get the best value for your luxury watch.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
