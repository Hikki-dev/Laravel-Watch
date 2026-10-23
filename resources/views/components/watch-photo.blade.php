@props(['src', 'alt' => ''])

{{-- Fills the frame with a blurred copy of the photo's top edge (usually plain studio background) and shows the sharp photo uncropped on top. --}}
<div {{ $attributes->merge(['class' => 'relative w-full h-full overflow-hidden']) }}>
    <img src="{{ $src }}" alt="" aria-hidden="true" loading="lazy" class="absolute inset-0 w-full h-full object-cover object-top scale-110 blur-xl">
    <img src="{{ $src }}" alt="{{ $alt }}" loading="lazy" class="relative w-full h-full object-contain transition duration-700 group-hover:scale-105">
</div>
