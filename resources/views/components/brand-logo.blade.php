@props(['name', 'height' => 'h-12'])

@php
    // Logos live in public/images/brands/<file>.webp. Matching is by brand name.
    $map = [
        'patek' => 'patek', 'audemars' => 'ap', 'rolex' => 'rolex', 'omega' => 'omega',
        'richard' => 'richard_mille', 'tag' => 'tag_heuer', 'swatch' => 'swatch',
        'seiko' => 'seiko', 'casio' => 'casio', 'citizen' => 'citizen',
    ];
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
@else
    <span {{ $attributes->merge(['class' => "logo-plate eyebrow {$height}"]) }}>{{ $name }}</span>
@endif
