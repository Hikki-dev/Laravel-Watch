@props(['frames' => [], 'label' => 'Rotate the watch', 'auto' => null])

{{--
    360° viewer. Pass an ordered list of frame URLs (36 to 120 photos of one watch turning).
    Renders nothing below 8 frames so the caller can fall back to a photo gallery.
--}}
@php($frames = array_values($frames ?? []))
@if (count($frames) >= 8)
    <div {{ $attributes->merge(['class' => 's360 s360-live']) }}
        data-spin360="1" data-live="1" data-count="{{ count($frames) }}" data-frame="0"
        data-frames="{{ json_encode($frames) }}"
        @if ($auto) data-auto="{{ $auto }}" @endif
        role="slider" tabindex="0" aria-label="{{ $label }}"
        aria-valuemin="0" aria-valuemax="{{ count($frames) - 1 }}" aria-valuenow="0">
        <div class="s360-layer" style="background-image: url('{{ $frames[0] }}'); background-size: contain; background-position: center"></div>
    </div>
@endif
