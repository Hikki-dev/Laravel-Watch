<div class="relative">
    <div class="relative">
        <input 
            wire:model.live.debounce.300ms="search"
            type="text" 
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-luxury-gold focus:border-luxury-gold" 
            placeholder="Search watches..."
        >
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    @if(strlen($search) >= 2)
        <div class="absolute z-50 w-full mt-1 bg-white rounded-md shadow-lg border border-gray-200">
            @if(count($results) > 0)
                <ul class="py-1">
                    @foreach($results as $product)
                        <li>
                            <a href="{{ route('products.show', $product) }}" class="block px-4 py-2 hover:bg-gray-50 flex items-center">
                                @if($product->image_url)
                                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" class="h-8 w-8 object-cover rounded mr-3">
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $product->brand }} - ${{ number_format($product->price, 2) }}</div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="px-4 py-3 text-sm text-gray-500">
                    No products found for "{{ $search }}"
                </div>
            @endif
        </div>
    @endif
</div>
