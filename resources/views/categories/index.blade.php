<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 font-luxury mb-2">Premium Watch Brands</h1>
                <p class="text-gray-600">
                    Discover timepieces from the world's most prestigious manufacturers.
                </p>
            </div>
            
            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('categories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add New Brand
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($categories as $category)
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 text-center group hover:shadow-xl transition duration-300">
                    <div class="w-full h-48 bg-gray-100 rounded-lg mb-4 flex items-center justify-center p-6">
                        @if($category->image)
                            <img src="{{ asset('images/brands/' . $category->image) }}" alt="{{ $category->name }}" class="max-w-full max-h-full object-contain">
                        @else
                             <!-- Placeholder or Text Logo -->
                            <span class="text-2xl font-luxury text-gray-400">{{ $category->name }}</span>
                        @endif
                    </div>
                    
                    <h3 class="text-xl font-semibold mb-2">{{ $category->name }}</h3>
                    <p class="text-gray-600 mb-4 h-12 overflow-hidden">{{ Str::limit($category->description, 100) }}</p>
                    
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('products.index', ['brand' => $category->name]) }}" class="inline-flex items-center justify-center text-luxury-gold hover:text-luxury-darkGold font-medium">
                            View Collection
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>

                        @if(auth()->check() && auth()->user()->isAdmin())
                            <div class="flex justify-center space-x-4 text-sm mt-2 border-t pt-2">
                                <a href="{{ route('categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
