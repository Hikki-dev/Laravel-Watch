@props(['name', 'height' => 'h-12', 'image' => null])

@php
    // Logos live in public/images/brands/<file>.webp. Matching is by brand name.
    $map = [
        'patek' => 'patek', 'audemars' => 'ap', 'rolex' => 'rolex', 'omega' => 'omega',
        'richard' => 'richard_mille', 'tag' => 'tag_heuer', 'swatch' => 'swatch',
        'seiko' => 'seiko', 'casio' => 'casio', 'citizen' => 'citizen',
    ];
    // Brands outside the map (added by an admin) use their uploaded image, if any.
    $custom = $image ? (str_starts_with($image, 'http') ? $image : asset('images/brands/' . $image)) : null;
    $file = null;
    foreach ($map as $needle => $candidate) {
        if (str_contains(strtolower((string) $name), $needle)) {
            $file = $candidate;
            break;
        }
    }
    $exists = $file && file_exists(public_path("images/brands/{$file}.webp"));
@endphp

@if ($exists)
    <span {{ $attributes->merge(['class' => "logo-plate {$height}"]) }}>
        <img src="{{ asset("images/brands/{$file}.webp") }}" alt="{{ $name }}" loading="lazy" decoding="async">
    </span>
@elseif ($custom)
    <span {{ $attributes->merge(['class' => "logo-plate {$height}"]) }}><img src="{{ $custom }}" alt="{{ $name }}" loading="lazy" decoding="async"></span>
@else
    <span {{ $attributes->merge(['class' => "logo-plate eyebrow {$height}"]) }}>{{ $name }}</span>
@endif
