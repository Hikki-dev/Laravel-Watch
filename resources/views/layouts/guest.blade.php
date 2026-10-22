<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            <div class="fixed top-4 right-4 z-50">
                <x-theme-toggle />
            </div>
            {{ $slot }}
        </div>

    </body>
</html>
