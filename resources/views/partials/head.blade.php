<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $title ?? config('app.name', 'Laravel') }}</title>

{{-- Apply the saved colour mode before first paint. Light is the default. --}}
<script>
    try {
        document.documentElement.dataset.theme = localStorage.getItem('lux-theme') === 'dark' ? 'dark' : 'light';
    } catch (e) {
        document.documentElement.dataset.theme = 'light';
    }
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
